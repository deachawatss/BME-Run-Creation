import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController, AlertController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-preweigh-inventory',
  templateUrl: './preweigh-inventory.page.html',
  styleUrls: ['./preweigh-inventory.page.scss'],
})
export class PreweighInventoryPage implements OnInit {
  loading:any;
  prodcode = '';
  lotno = '';
  itemkey = '';
  soh = 0;
  toadjust = 0;
  committed = 0;
  mydata : any;
  mylist : any;
  statbatch = true;
  reqtype = 'PC';

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

  async searchRM(){
    //if(keyCode == 13 ){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      var mybarcode = await this.getbarcode(this.prodcode) ;
      this.mydata = [];
      var count = 0;

          count = 1;

          (await this.api.post({ "prodcode":this.prodcode }, 'PreweighInventory/getRM', "")).subscribe(
            async (res) => {
               if(res.msg == ""){

                this.lotno = res.LotNo;
                this.itemkey = res.ItemKey;
                this.soh = res.SOH;
                this.committed = res.Committed;
                this.toadjust = res.SOH;
                this.mylist = res.Request;
               }else{
                this.presentToast(res.msg);
               }
       
             },
             (err) => {
               this.presentToast("Error");
        });

      if(count == 0){
        this.presentAlert()
      }

      this.mylist = await this.mydata;

      this.loading.dismiss();
    //}
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Alert',
      message: 'Item not included in the list.',
      buttons: ['OK']
    });
  
    await alert.present();
  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async btnRequest(){
    const userinfo = await this.authservice.get_userinfo();
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();
    
    const pp = {
      "prodcode": this.prodcode,
      "soh" : this.soh,
      "actualqty" : this.toadjust,
      "reqtype" : this.reqtype
    };

    (await this.api.post(pp, 'PreweighInventory/genRequest', "")).subscribe(
      async (res) => {
         if(res.msgno == "200"){
          this.mylist = res.Request;
         }

         this.presentToast(res.msg);
         this.loading.dismiss();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
    });
    
  }

  async itemclearInput(){
    this.toadjust = 0;
  }
}
