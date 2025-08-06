import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController, AlertController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-batchtransfer',
  templateUrl: './batchtransfer.page.html',
  styleUrls: ['./batchtransfer.page.scss'],
})
export class BatchtransferPage implements OnInit {
  loading:any;
  
  mydata : any;
  mylist : any;
  statbatch = true;

  batchno : any;
  binno : any;
  bulkpartial : string = 'both';;

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

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async transfer(){
    const userinfo = await this.authservice.get_userinfo();
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    if(
      this.batchno != '' &&
      this.binno != '' &&
      this.bulkpartial != ''
    ){

      const pp = {
        "batchno": this.batchno,
        "binno": this.binno,
        "bulkpartial": this.bulkpartial,
        "sys_enter_by" : userinfo.userid,
        "uname": userinfo.uname
      };
      
      (await this.api.transferItem(pp, 'Batchtransfer/saverec', '')).subscribe(
        async (res:any) => {
          //await this.presentBinModal(res.results);
          this.loading.dismiss();
          this.batchno = "";
          this.binno = "";
          //this.bulkpartial = "";
          //this.presentToast(res.msg);
        },
        (err) => {
          this.presentToast("Cannot connect to server");
          this.loading.dismiss();
          this.batchno = "";
          this.binno = "";
          //this.bulkpartial = "";
        }
      );

      await (await this.api.post({ "batchno": this.batchno, 'binno': this.binno, 'bulkpartial' :this.bulkpartial }, 'Batchtransfer/saverec', "")).subscribe(
        async (res) => {
            if(res.msg != ""){
              this.presentToast(res.msg);
            }
            this.batchno = "";
            this.binno = "";
            this.loading.dismiss();
         },
         (err) => {
            this.presentToast("Cannot connect to server");
            this.batchno = "";
            this.binno = "";
            this.loading.dismiss();
      });

      
    }else{
      this.presentToast("Please fill Batch No and Bin No.");
      this.loading.dismiss();
    }

  }
}
