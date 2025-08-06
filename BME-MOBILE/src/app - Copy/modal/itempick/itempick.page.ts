import { Component, OnInit,ViewChild } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController, AlertController } from '@ionic/angular';
import { ApiService } from 'src/app/services/api.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-itempick',
  templateUrl: './itempick.page.html',
  styleUrls: ['./itempick.page.scss'],
})
export class ItempickPage implements OnInit {

  prodcode = '';
  drno = '';
  qtytarget = 0; 
  customer = '';
  loading:any;
  mydata: any;
  drlist: any;
  itemkey = "";
  eccncode = '';
  tosave :any = {};
  mystat = 0;
  statconfirm = true;
  statsave = true;
  rownum = 0;
  constructor(
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private navCtrl: NavController,
    private modalCtrl: ModalController,
    private alertController: AlertController,
    private authservice: AuthService
  ) { }

  async getbarcode(str = ''){
    var mybarcode ={
      "barcode" : str,
      "02" : "",
      "10" : "",
      "17" : "",
    };
    mybarcode["barcode"] = str;
    var pp = str.split("(", 3); 
    var ppx : any;
    for (var val of pp) {
      
      if(val.length > 0){
          ppx = val.split(")", 3); 
          if(ppx[1]){
            //mybarcode[ ppx[0] ] = ppx[1];
            switch(ppx[0]){
              case "02":{
                mybarcode["02"] = ppx[1];
                break;
              }
              case "10":{
                mybarcode["10"] = ppx[1];
                break;
              }
            }
          }
      }

    }

    return mybarcode;
  }

  async popUpQty(keyCode:number) {
    if(keyCode == 13 && this.prodcode != ''){
      var mybarcode = await this.getbarcode(this.prodcode) ;
     
      var xdlist = await this.drlist.filter((items:any,myitem:any) =>{

        if (items.LotNo == mybarcode["10"]  && items.ItemKey == mybarcode["02"] ) {
          return true;
        }
        else{
          return false;
        }
          
      });
      if(xdlist.length > 0){

        const alert = await this.alertController.create({
          buttons: [
            {
                text: 'Cancel',
                role: 'cancel',
                cssClass: 'secondary',
                handler: () => {
                    console.log('Confirm Cancel');
                }
            }, 
            {
                text: 'Ok',
                handler: async (alertData) => { //takes the data 
                  var mybarcode = await this.getbarcode(this.prodcode) ;
                  
                  if(alertData.qty > 0){
                  this.statconfirm = false;
                  var dlist = this.drlist.filter((items:any,myitem:any) =>{

                      if (items.LotNo.indexOf(mybarcode["10"]) >= 0 ) {

                        if(this.drlist[myitem].ItemKey == mybarcode["02"]){
                        
                          if(alertData.qty == this.drlist[myitem].QtyIssued){
                            var tosave = {
                              "lotcode":mybarcode["10"],
                              "barcode":this.prodcode,
                              "itemkey":this.itemkey,
                              "drno":this.drno,
                              "qty":alertData.qty,
                              "BinNo":this.drlist[myitem].BinNo,
                              "RowNum":items.RowNum
                            };
      
                            this.tosave[mybarcode["10"]] = tosave;
                            this.drlist[myitem].qty = alertData.qty;
                            this.drlist[myitem].wt = alertData.qty * parseInt(this.drlist[myitem].Manufacturer);
                          }else{
                            this.presentToast("Quantity Mismatch");
                          }
                        
                        }else{
                          this.presentToast("Invalid Itemkey");
                        }

                        if(items.qty == undefined || items.qty == '' || items.qty == 0 || items.qty == null){
                          this.statconfirm = true;
                        }
                        return true;
                      }
                      else{
                        if(items.qty == undefined || items.qty == '' || items.qty == 0 || items.qty == null){
                          this.statconfirm = true;
                        }
                        return false;
                      }
                        
                    });

                    if(dlist.length == 0){
                      this.presentToast("Item is not in the list");
                    }

                        

                  }else{
                    this.presentToast("Invalid Quantity");
                  }
                  
                  
                }
            }
          ],
          inputs: [
            {
              name: 'qty',
              type: 'number',
              placeholder: 'Qty',
            },
          ],
          
        });
        await alert.present();
      }else{
        this.presentToast("Item is not in the list");
      }

    
   }
  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async chckstat(data:any){
    var confstat: any = [];
    var savestat: any = [];
    var finishstat: any = [];
    var dlist = this.drlist.filter((items:any,myitem:any) =>{
        switch(items.statflag){
          case 'C':{
            confstat.push(items);
            break;
          }
          case 'S':{
            savestat.push(items);
            break;
          }
          case 'F':{
            finishstat.push(items);
            break;
          }
          default:{
            break;
          }
            
        }
        return true;
    });

    
    switch(true){

      //all with S stat
      case (savestat.length == data.length):{
        this.statconfirm = false;
        this.statsave = true;
        break;
      }

      //all with C stat
      case (confstat.length == data.length):{
        this.statconfirm = true;
        this.statsave = true;
        break;
      }

      //all with F stat
      case (finishstat.length == data.length):{
        this.statconfirm = true;
        this.statsave = true;
        break;
      }

      //Finish
      default:{
        
        this.statconfirm = true;
        this.statsave = false;

        break;
      }
      
    }
  }

 async ngOnInit() {

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "drno": this.drno, "itemkey": this.itemkey, "rownum": this.rownum }, 'Dispatch/scanitemhdrlist', "")).subscribe(
      async (res) => {
         if(res.msg == ""){
          // this.mydata = res.drdetails;
           this.eccncode = res.drRow.Eccncode;
           this.drlist = res.drlist;

          var dlist = this.drlist.filter((items:any,myitem:any) =>{
           
            if (items.qty != '' && items.qty != null) {
              var tosave = {
                "lotcode": items.LotNo,
                "itemkey": items.ItemKey,
                "drno":this.drno,
                "qty": items.qty,
                "BinNo": items.BinNo,
                "RowNum":items.RowNum
              };

              this.tosave[items.LotNo] = tosave;
              return true;
            }
            else
              return false;
          });

          await this.chckstat(this.drlist);
         }
 
         this.loading.dismiss();
         
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });
      
  }
  
  cancel() {
    return this.modalCtrl.dismiss(null, 'cancel');
  }

  pickitem(itemkey=""){

  }

  async btnsave(){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "drno": this.drno,"itemkey": this.itemkey, lotlist: JSON.stringify(this.tosave) }, 'Dispatch/savedispatch', "")).subscribe(
      async (res) => {
         if(res.msg == ""){
           //this.mydata = res.results;
         }
 
         
         this.loading.dismiss();
         await this.ngOnInit();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });

  }

  async btnConfirm(){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "drno": this.drno,"itemkey": this.itemkey, lotlist: JSON.stringify(this.tosave) }, 'Dispatch/confirmdispatch', "")).subscribe(
      async (res) => {
         if(res.msg == ""){
           //this.mydata = res.results;
         }
 
         this.loading.dismiss();
         await this.ngOnInit();
         this.statsave = true;
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });


  }

  async frmConfirm(){
    return false;
  }
  async frmsave(){
    
  }

}
