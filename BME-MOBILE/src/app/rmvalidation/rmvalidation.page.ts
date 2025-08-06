import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController, AlertController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-rmvalidation',
  templateUrl: './rmvalidation.page.html',
  styleUrls: ['./rmvalidation.page.scss'],
})
export class RMvalidationPage implements OnInit {
  loading:any;
  prodcode = '';
  batchticket = '';
  formulaid = '';

  mydata : any;
  mylist : any;
  statbatch = true;

  constructor(
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private navCtrl: NavController,
    private modalCtrl: ModalController,
    private alertController: AlertController,
    private authservice: AuthService
  ) { }

  ngOnInit() {
  }

  btnBack(){
    this.navCtrl.navigateRoot('/home');
  }
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

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async searchprod(keyCode:number){
    if(keyCode == 13 && this.batchticket != ''){

      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();

      (await this.api.post({ "batchticket": this.batchticket }, 'RMValidation/getbatch', "")).subscribe(
        async (res) => {
           if(res.msg == ""){
            this.statbatch = (res.norecord > 0 ? false : true);
            this.formulaid = res.formulaid;
            this.mylist = res.results;
           }
   
           this.loading.dismiss();
         },
         (err) => {
           this.presentToast("Error");
           this.loading.dismiss();
         });

         

    }
  }

  async searchRM(keyCode:number){
    if(keyCode == 13 && this.batchticket != ''){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      var mybarcode = await this.getbarcode(this.prodcode) ;
      this.mydata = [];
      var count = 0;
      var xdlist = await this.mylist.filter(async (items:any,myitem:any) =>{
     
        if(items.itemkey == mybarcode['02'] && items.lotno == mybarcode['10']){
          /*
            Process PNITEM
          */
          count = 1;

          (await this.api.post({ "batchticket": this.batchticket,"Lineid": items.Lineid }, 'RMValidation/setitem', "")).subscribe(
            async (res) => {
               if(res.msg == ""){
               }
       
             //  this.loading.dismiss();
             },
             (err) => {
               this.presentToast("Error");
             //  this.loading.dismiss();
        });

          return false;
        }else{
          this.mydata.push(items);
          return true;
        }

      });

      if(count == 0){
        this.presentAlert()
      }

      this.mylist = await this.mydata;

      this.loading.dismiss();
    }
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Alert',
      message: 'Item not included in the list.',
      buttons: ['OK']
    });
  
    await alert.present();
  }




}
