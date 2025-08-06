import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController, AlertController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-preweigh-validation',
  templateUrl: './preweigh-validation.page.html',
  styleUrls: ['./preweigh-validation.page.scss'],
})
export class PreweighValidationPage implements OnInit {

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

  async searchprod(){
    if(this.batchticket != ''){
      console.log(this.batchticket)
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();

      (await this.api.post({ "batchticket": this.batchticket }, 'PreweighValidation/getbatch', "")).subscribe(
        async (res) => {
           if(res.msg == ""){
            this.statbatch = (res.norecord > 0 ? false : true);
            this.formulaid = res.formulaid;
            this.mylist = res.results;
           }else{
            this.presentToast(res.msg);
           }
   
           this.loading.dismiss();
         },
         (err) => {
           this.presentToast("Error");
           this.loading.dismiss();
         });

         

    }
  }

  async searchRM(){
    if(this.batchticket != '' && this.prodcode != ''){
      
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      var mybarcode = await this.getbarcode(this.prodcode) ;
      this.mydata = [];
      var count = 0;
      //var xdlist = await this.mylist.filter(async (items:any,myitem:any) =>{
     
        //if(items.itemkey == mybarcode['02'] && items.lotno == mybarcode['10']){
          /*
            Process PNITEM
          */

          console.log(this.prodcode)
          //this.presentToast(await this.prodcode);
          count = 1;

          (await this.api.post({ "batchticket": await this.batchticket,"prodcode": await this.prodcode }, 'PreweighValidation/setitem', "")).subscribe(
            async (res) => {
               if(res.msg == ""){
                
                  if(res.valid != 'Y'){
                   // this.presentToast("Invalid Raw Material");
                    this.prodcode = '';
                    this.presentAlert('Item not included in the list.');
                  }
                  this.mylist = res.results;
               }else{
                //this.presentToast(res.msg);
                this.presentAlert(res.msg);
               }
       
             //  this.loading.dismiss();
             },
             (err) => {
               this.presentToast("Error");
             //  this.loading.dismiss();
        });

          //return false;
        //}else{
        //  this.mydata.push(items);
        //  return true;
        //}

      //});


      this.mylist = await this.mydata;

      this.loading.dismiss();
    }
  }  

  async presentAlert(msg = '') {
    const alert = await this.alertController.create({
      header: 'Alert',
      message: msg,
      buttons: ['OK']
    });
  
    await alert.present();
  }

}
