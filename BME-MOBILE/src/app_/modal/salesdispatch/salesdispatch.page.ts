import { Component, OnInit } from '@angular/core';
import { LoadingController, ToastController, NavController, ModalController } from '@ionic/angular';
import { ApiService } from 'src/app/services/api.service';
import { AuthService } from 'src/app/services/auth.service';
import { ItempickPage } from '../itempick/itempick.page';
@Component({
  selector: 'app-salesdispatch',
  templateUrl: './salesdispatch.page.html',
  styleUrls: ['./salesdispatch.page.scss'],
})
export class SalesdispatchPage implements OnInit {

  drno = '';
  ordno = '';
  delivery_date = '';
  customer = '';
  loading:any;
  mydata: any;
  statconfirm = true;
  statsave = true;
  constructor(
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private navCtrl: NavController,
    private modalCtrl: ModalController,
    private authservice: AuthService
  ) { }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async chckstatus(data:any){

    var dlist = data.filter((items:any) =>{
      if(items.mytotal == items.mystat ){
        return true;
      }
      else
        return false;
    });

    if(data.length == dlist.length){
      this.statconfirm = false;
    }
    else{
      this.statconfirm = true;
    }

  }

 async ngOnInit() {

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "drno": this.drno }, 'Dispatch/scandr', "")).subscribe(
      async (res) => {
         if(res.msg == ""){
           this.mydata = res.results;
           this.chckstatus(this.mydata);
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

  
  async pickitem(itemkey="",qtytarget=0, rownum = 0){

      const modal = await this.modalCtrl.create({
        component: ItempickPage,
        handle: false,
        componentProps: {
         drno:this.drno,
         itemkey: itemkey,
         qtytarget: qtytarget,
         rownum:rownum
        },
      });
      this.loading.dismiss();
      await modal.present();
  
      await modal.onWillDismiss().then(  async (modelData:any) => {
        await this.ngOnInit();
      });
  }

  async btnconfirm(){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "drno": this.drno }, 'Dispatch/saveSalesDr', "")).subscribe(
      async (res) => {
         this.statconfirm = true;
         this.loading.dismiss();
         this.presentToast(res.msg);
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });
      

  }

}
