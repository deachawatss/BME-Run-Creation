import { Component, OnInit } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController,AlertController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
@Component({ 
  selector: 'app-inventory-count',
  templateUrl: './inventory-count.page.html',
  styleUrls: ['./inventory-count.page.scss'],
})
export class InventoryCountPage implements OnInit {

  BinNo = '';
  ItemKey = '';
  Desc1 = '';
  LotNo = '';

  search_bin = '';
  search_barcode = '';
  qty = '';

  period = '';
  invid = 0;

  loading : any;

  constructor(
    private navCtrl: NavController,
    private toastController: ToastController,
    private modalCtrl: ModalController,
    private authservice: AuthService,
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private alertController: AlertController,
  ) { }

  ngOnInit() {
  }

  btnBack(){
    this.navCtrl.navigateRoot('/inventory');
  }

  async tfind(keyCode:number){

    if(keyCode == 13 && this.search_barcode != '' && this.search_bin != ''){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      await this.loading.present();
  
      (await this.api.post({ "binno": this.search_bin , 'barcode' : this.search_barcode }, 'InventoryCount/findInvCount', "")).subscribe(
        async (res) => {
          if(res.msg != ""){
            this.presentToast(res.msg);
          }else{
            this.ItemKey = res.data.itemkey;
            this.Desc1 =  res.data.desc;
            this.LotNo =  res.data.lotno;
            this.qty = res.data.qty;
            this.period = res.data.period;
            this.invid = res.data.id
          }
          //await this.presentItemLotModal(res.results);
         
        },
        (err) => {
          this.presentToast("Cannot connect to server");
          this.BinNo = '';
          this.ItemKey = '';
          this.Desc1 = '';
          this.LotNo = '';
          this.invid = 0;
          this.search_bin = '';
          this.search_barcode = '';
          this.qty = '';
          this.period = '';
          
        }
      );
      this.loading.dismiss();
    }

  }

  async btnSave(){
    if(this.search_barcode != '' && this.search_bin != '' && (this.qty != '' || parseFloat(this.qty) > 0 )){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      await this.loading.present();
      
      (await this.api.post({ "binno": this.search_bin , 'barcode' : this.search_barcode, 'qty' : this.qty , 'invid' : this.invid}, 'InventoryCount/saveInvCount', "")).subscribe(
        async (res) => {
          if(res.msgno != "200"){
            this.api.presentAlert(res.msg);
          }else{
            this.presentToast(res.msg);
          }
            this.BinNo = '';
            this.ItemKey = '';
            this.Desc1 = '';
            this.LotNo = '';
          
            this.search_bin = '';
            this.search_barcode = '';
            this.qty = '';
            this.period = '';
            this.invid = 0;
          
        },
        (err) => {
          this.presentToast("Cannot connect to server");
          this.BinNo = '';
          this.ItemKey = '';
          this.Desc1 = '';
          this.LotNo = '';
        
          this.search_bin = '';
          this.search_barcode = '';
          this.qty = '';
          this.period = '';
          this.invid = 0;
        }
      );
      this.loading.dismiss();
    }
  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

}
