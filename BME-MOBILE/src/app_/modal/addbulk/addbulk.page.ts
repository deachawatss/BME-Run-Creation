import { Component, OnInit } from '@angular/core';
import { AlertController, LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { ApiService } from 'src/app/services/api.service';
import { AuthService } from 'src/app/services/auth.service';
import { AddbulkitemPage } from '../addbulkitem/addbulkitem.page';
import { BulkitemPage } from '../bulkitem/bulkitem.page';

@Component({
  selector: 'app-addbulk',
  templateUrl: './addbulk.page.html',
  styleUrls: ['./addbulk.page.scss'],
})
export class AddbulkPage implements OnInit {
  data : any = []
  pickedData : any = []
  idesc = "";
  itemkey = "";
  bagsneed = 0;
  remaining = 0;
  packunit = '';
  packsize = 0;
  mybarcode = '';
  runno = '';
  batchno = '';
  NoAllocate  = true;
  statflag = '';
  loading:any;
  kgstqty = 0;
  printdata : any  = [];
  tqty = 0;
  mdt :any  =[];
  suggestedData = '';
  constructor(
    private navCtrl: NavController,
    private toastController: ToastController,
    private modalCtrl: ModalController,
    private authservice: AuthService,
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private alertController: AlertController
  ) { }

  async ngOnInit() {
    var mystat = await this.statflag;
    if((this.remaining == 0  ||  this.remaining < this.packsize ) && mystat == 'N'){
      this.NoAllocate = false;
    }
    
  }

  btnSelect(i : any){
    return this.modalCtrl.dismiss('update');
  }

  cancel(){
    return this.modalCtrl.dismiss(null, 'cancel');
  }

  async presentConfirm(msg = '') {
    return new Promise(async (resolve) => {
      const confirmationModal = await this.alertController.create({
        header: 'NWFTH',
        message: msg,
        buttons: [
          {
            text: 'Cancel',
            role: 'cancel',
            handler: () => {
              resolve(false); // User clicked Cancel, resolve with 'false'
            }
          },
          {
            text: 'Confirm',
            handler: () => {
              resolve(true); // User clicked Confirm, resolve with 'true'
            }
          }
        ]
      });
  
      confirmationModal.present();
    });
  }
  
  

  async presentRunModal(data:any){
    
    const modal = await this.modalCtrl.create({
      component: AddbulkitemPage,
      handle: false,
      componentProps: {
        itemdata : data.results,
        results : data.results,
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  async (modelData:any) => {

      if(modelData.data != null){
        this.mybarcode = '(02)' + modelData.data.itemkey + '(10)' + modelData.data.lotno;
        /*
        const alert = await this.alertController.create({
          header:"Bulk Quantity",
          buttons: [
            {
                text: 'Cancel',
                role: 'cancel',
                cssClass: 'secondary',
                handler: () => {
                    
                }
            }, 
            {
                text: 'Ok',
                handler: async (alertData) => { //takes the data 
                 
                 var myqty =(modelData.data.QtyAvailable / this.packsize);
                 var myqq = parseInt(alertData.qty)
                  if( parseInt(myqty.toString()) < myqq){
                    this.presentToast("Not enough stocks!");
                    
                  }else{
                    
                    this.saveData(myqq, modelData.data.binno)
                  }
  
                }
            }
          ],
          inputs: [
            {
              name: 'qty',
              type: 'number',
              placeholder: 'Bulk Qty',
            },
          ],
          
        });
  
        await alert.present();
        */
        const xmodal = await this.modalCtrl.create({
          component: BulkitemPage,
          handle: false,
        });
        if(this.suggestedData != modelData.data.lotno){

          (async () => {
            const confirmed = await this.presentConfirm('You picked different Lot than suggested, Do you still want to continue?');
            if (confirmed) {
             
              await xmodal.present();
  
          await xmodal.onWillDismiss().then(  async (xmodelData:any) => {
            
            if(xmodelData.data != null){
              //KGS
              var myqty = 0
              if(xmodelData.data.itype == 0){
                
                 myqty =(modelData.data.QtyAvailable);
                   var myqq = parseFloat(xmodelData.data.qty)
                    if( parseFloat(myqty.toString()) < myqq){
                      this.presentToast("Not enough stocks!");
                      
                    }else{
                      
                      //console.log(this.kgstqty , (this.tqty + myqq))
                      if( parseFloat((parseFloat(this.tqty.toString()) + myqq ).toFixed(6)) <=  parseFloat((this.kgstqty).toFixed(6)) ){
                        this.tqty += myqq;
                        this.saveData(myqq, modelData.data.binno, 0,xmodelData.data.bulkqty)
                      }
                      else
                      this.presentToast("Greater than required stocks!");
                    }
  
              }else{
  
                 myqty =(modelData.data.QtyAvailable / this.packsize);
                   var myqq = parseFloat(xmodelData.data.bulkqty)
                    if( parseFloat(myqty.toString()) < myqq){
                      this.presentToast("Not enough stocks!");
                      
                    }else{
                      if((this.bagsneed + myqq) <=  this.kgstqty){
                        this.tqty += (this.packsize * myqq);
                        this.saveData(myqq, modelData.data.binno, 1,myqq)
                      }
                      else
                      this.presentToast("Greater than required stocks!");
                    }
  
              }
  
            }
  
          });


            } else {
              // User clicked Cancel or closed the dialog
              // Put your code here for the action to be performed when the user cancels.
            }
          })();

        }else{

         
          await xmodal.present();
  
          await xmodal.onWillDismiss().then(  async (xmodelData:any) => {
            
            if(xmodelData.data != null){
              //KGS
              var myqty = 0
              if(xmodelData.data.itype == 0){
  
                 myqty =(modelData.data.QtyAvailable);
                   var myqq = parseFloat(xmodelData.data.qty)
                    if( parseFloat(myqty.toString()) < myqq){
                      this.presentToast("Not enough stocks!");
                      
                    }else{
                      if((parseFloat(this.tqty.toString()) + myqq ).toFixed(6) <=  (this.kgstqty).toFixed(6) ){
                        this.tqty += myqq;
                        this.saveData(myqq, modelData.data.binno, 0,xmodelData.data.bulkqty)
                      }
                      else
                      this.presentToast("Greater than required stocks!");
                    }
  
              }else{
  
                 myqty =(modelData.data.QtyAvailable / this.packsize);
                   var myqq = parseFloat(xmodelData.data.bulkqty)
                    if( parseFloat(myqty.toString()) < myqq){
                      this.presentToast("Not enough stocks!");
                      
                    }else{
                      if((this.bagsneed + myqq) <=  this.kgstqty){
                        this.tqty += (this.packsize * myqq);
                        this.saveData(myqq, modelData.data.binno, 1,myqq)
                      }
                      else
                      this.presentToast("Greater than required stocks!");
                    }
  
              }
  
            }
  
          });


        }


      }
      
      
    });

  }

  async saveData(qty = 0, binno = '', itype = 0, mbulk = 0){
    var mylot = this.mybarcode
      
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      

        await this.loading.present();
      
        await (await this.api.post({ barcode: this.mybarcode, binno: binno, qty: qty, runno: this.runno, batchno: this.batchno, itype: itype, mbulk : mbulk}, 'Bulkpick/additembulk', "")).subscribe(
          async (res) => {
              if(res != null){

                if(res.msgno == '200'){  
                  this.pickedData = res.data;

                  if(itype == 0){
                    this.remaining -= qty ;
                    this.bagsneed -= (qty / this.packsize);
                  }else{
                    this.remaining -= (qty * this.packsize);
                    this.bagsneed -= qty;
                  }

                  
                 // this.tqty += (qty * this.packsize);
                  if(this.remaining <= 0 ||  this.remaining < this.packsize){
                    this.NoAllocate = false;
                  }

                }else{
                  this.presentToast(res.msg);
                }

              }
            
            this.loading.dismiss();
          },
          (err) => {
            this.presentToast("Error");
            this.loading.dismiss();
          });

      
  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async chcklot(){
    var mylot = this.mybarcode
      
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      
      await (await this.api.post({ barcode : mylot, rmitemkey: this.itemkey}, 'Bulkpick/getlotdata', "")).subscribe(
        async (res) => {
            if(res != null){
              this.presentRunModal(res)
            }
           
           this.loading.dismiss();
         },
         (err) => {
           this.presentToast("Error");
           this.loading.dismiss();
         });

  }

  async isItemEnter(keyCode:number){
    
    if(keyCode == 13){
      this.chcklot()
      /*
      const alert = await this.alertController.create({
        buttons: [
          {
              text: 'Cancel',
              role: 'cancel',
              cssClass: 'secondary',
              handler: () => {
                  
              }
          }, 
          {
              text: 'Ok',
              handler: async (alertData) => { //takes the data 
                chcklot(barcode, qty )
              }
          }
        ],
        inputs: [
          {
            name: 'qty',
            type: 'number',
            placeholder: 'Bulk Qty',
          },
        ],
        
      });

      await alert.present();
      */

    }

  }

  async printme(e:any){
    this.printdata = await this.pickedData[e];
    window.print();
  }

  async btnallocate(){
    
    var mylot = this.mybarcode
      
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });

        await this.loading.present();
      
        await (await this.api.post({batchno: this.batchno, rmitemkey: this.itemkey}, 'Bulkpick/setallocate', "")).subscribe(
          async (res) => {
              if(res != null){

                
                if(res.msgno == '200'){  
                  this.NoAllocate = true;
                  this.presentToast(res.msg);
                }else{
                  this.presentToast(res.msg);
                }
                

              }
            
            this.loading.dismiss();
          },
          (err) => {
            this.presentToast("Error");
            this.loading.dismiss();
          });

      
      
  }
}
