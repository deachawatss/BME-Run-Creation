import { Component, OnInit } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import * as moment from 'moment';
import { SalesdispatchPage } from '../modal/salesdispatch/salesdispatch.page';
@Component({
  selector: 'app-dispatch',
  templateUrl: './dispatch.page.html',
  styleUrls: ['./dispatch.page.scss'],
})
export class DispatchPage implements OnInit {
  drno = '';
  ordno = '';
  delivery_date = '';
  customer = '';
  loading:any;
  mydata: any;
  mydatarow: any;
  constructor(
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private navCtrl: NavController,
    private modalCtrl: ModalController,
    private authservice: AuthService,
  ) { }

  ngOnInit() {
  }

  btnBack(){
    this.navCtrl.navigateRoot('/home');
  }

  async presentItemModal(){
    const modal = await this.modalCtrl.create({
      component: SalesdispatchPage,
      handle: false,
      componentProps: {
       drno:this.drno,
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  (modelData:any) => {
      if (modelData !== null) {
       // this.NewBinNo = modelData.data.BinNo;
      }
    });

  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }
  
  async isItemEnter(keyCode:number){
    
    if(keyCode == 13){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      await this.loading.present();
      
      await (await this.api.post({ "drno": this.drno }, 'Dispatch/finddr', "")).subscribe(
        async (res) => {
           if(res.msg == ""){
             this.mydata = res.results;
             this.mydatarow = res.drRow;
             this.ordno = res.drRow.OrdNo;
             this.delivery_date = moment(res.drRow.shipdate).format('LL'); ;
             this.customer = res.drRow.Custname;
           }
   
           this.loading.dismiss();
         },
         (err) => {
           this.presentToast("Error");
           this.loading.dismiss();
         });
        
    }
  }

  async scanItem(){
   

    await this.presentItemModal();
  }
}
