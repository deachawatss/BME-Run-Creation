import { Component, OnInit,ViewChild  } from '@angular/core';
import { ApiService } from '../services/api.service';
import {  LoadingController,ToastController,ModalController   } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { ItemlotPage } from '../modal/itemlot/itemlot.page';
import { BinPage } from '../modal/bin/bin.page';
import { AuthService } from '../services/auth.service';
import { IonInput } from '@ionic/angular';
@Component({
  selector: 'app-putaway',
  templateUrl: './putaway.page.html',
  styleUrls: ['./putaway.page.scss'],
})
export class PutawayPage implements OnInit {


  ProdCode = "";
  BinNo = "";
  ItemKey = "";
  LotNo = "";
  Location = "";
  UOM = "";
  QtyOnHand = 0;
  QtyAvailable = 0;
  ExpDate = "";
  PutawayQty = 0;
  NewBinNo = "";
  loading:any;
  docno = "";
  constructor(
    private api : ApiService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private navCtrl: NavController,
    private modalCtrl: ModalController,
    private authservice: AuthService
  ) { }

  ngOnInit() {
  }

  async presentItemLotModal(data:any){
    const modal = await this.modalCtrl.create({
      component: ItemlotPage,
      handle: false,
      componentProps: {
        itemdata : data,
        results: data
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  (modelData:any) => {
      if (modelData !== null) {
        this.ProdCode = ("(02)" + modelData.data.ItemKey + "(10)" +  modelData.data.LotNo);
        this.BinNo = modelData.data.BinNo;
        this.ItemKey = modelData.data.ItemKey;
        this.LotNo = modelData.data.LotNo;
        this.Location = modelData.data.LocationKey;
        this.UOM = modelData.data.Stockuomcode;
        this.QtyOnHand = modelData.data.QtyOnHand;
        this.QtyAvailable = (modelData.data.QtyOnHand - modelData.data.QtyCommitSales);
        this.ExpDate = modelData.data.DateExpiry;
        this.PutawayQty = (modelData.data.QtyOnHand - modelData.data.QtyCommitSales);
      }else{
        this.ProdCode = "";
        this.BinNo = "";
        this.ItemKey = "";
        this.LotNo = "";
        this.Location = "";
        this.UOM = "";
        this.QtyOnHand = 0;
        this.QtyAvailable = 0;
        this.ExpDate = "";
        this.PutawayQty = 0;
        this.NewBinNo = "";
        this.docno = "";
      }
      
    });

  }


  async presentBinModal(data:any){
    const modal = await this.modalCtrl.create({
      component: BinPage,
      handle: false,
      componentProps: {
        bindata : data,
        results: data
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  (modelData:any) => {
      if (modelData !== null) {
        this.NewBinNo = modelData.data.BinNo;
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
  
  async btnLotSearch(){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles', 
    });
    await this.loading.present();
    
    await (await this.api.post({ LotNo : this.LotNo },  'putaway/findlot', "")).subscribe(
      async (res) => {
        if(res.msg != ""){
          this.presentToast(res.msg);
        }
        await this.presentItemLotModal(res.results);
        
      },
      (err) => {
        this.presentToast("Cannot connect to server");
        this.loading.dismiss();
        this.ProdCode = "";
        this.BinNo = "";
        this.ItemKey = "";
        this.LotNo = "";
        this.Location = "";
        this.UOM = "";
        this.QtyOnHand = 0;
        this.QtyAvailable = 0;
        this.ExpDate = "";
        this.PutawayQty = 0;
        this.NewBinNo = "";
        this.docno = "";
      }
    );

  }

  async btnItemSearch(){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    (await this.api.findItemLotpost({ "prodcode": this.ProdCode }, 'putaway/findlot', '')).subscribe(
      async (res) => {
        if(res.msg != ""){
          this.presentToast(res.msg);
        }
        await this.presentItemLotModal(res.results);
        
      },
      (err) => {
        this.presentToast("Cannot connect to server");
        this.loading.dismiss();
        this.ProdCode = "";
        this.BinNo = "";
        this.ItemKey = "";
        this.LotNo = "";
        this.Location = "";
        this.UOM = "";
        this.QtyOnHand = 0;
        this.QtyAvailable = 0;
        this.ExpDate = "";
        this.PutawayQty = 0;
        this.NewBinNo = "";
        this.docno = "";
      }
    );
  }

  async btnBinSearch(){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    await (await this.api.post({ "BinNo": this.NewBinNo, 'location': this.Location },  'putaway/findbin', "")).subscribe(
      async (res) => {
        await this.presentBinModal(res.results);
        
      },
      (err) => {
        this.presentToast("Cannot connect to server");
        this.loading.dismiss();
        
      }
    );
  }

  async btnTransfer(){
    const userinfo = await this.authservice.get_userinfo();
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();
    
    const pp = {
      "lotno": this.LotNo,
      "putaway": this.PutawayQty,
      "tobin": this.NewBinNo,
      "itemkey": this.ItemKey,
      "bin" : this.BinNo,
      "sys_enter_by" : userinfo.userid,
      "uname": userinfo.uname,
      "docno" : this.docno
    };

    if(this.PutawayQty <= this.PutawayQty){
      (await this.api.transferItem(pp, 'putaway/sendputaway', '')).subscribe(
        async (res:any) => {
          //await this.presentBinModal(res.results);
          this.loading.dismiss();
          this.ProdCode = "";
          this.BinNo = "";
          this.ItemKey = "";
          this.LotNo = "";
          this.Location = "";
          this.UOM = "";
          this.QtyOnHand = 0;
          this.QtyAvailable = 0;
          this.ExpDate = "";
          this.PutawayQty = 0;
          this.NewBinNo = "";
          this.presentToast(res.msg);
          this.docno = "";
        },
        (err) => {
          this.presentToast("Cannot connect to server");
          this.loading.dismiss();
          this.ProdCode = "";
          this.BinNo = "";
          this.ItemKey = "";
          this.LotNo = "";
          this.Location = "";
          this.UOM = "";
          this.QtyOnHand = 0;
          this.QtyAvailable = 0;
          this.ExpDate = "";
          this.PutawayQty = 0;
          this.NewBinNo = "";
          this.docno = "";
        }
      );
    }else{
      this.presentToast("Putaway quantity is greater than stock on Hand!");
      this.loading.dismiss();
    }
    
  
    
  }

  async isItemEnter(keyCode:number){
    
    if(keyCode == 13){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      await this.loading.present();
  
      await (await this.api.post({ LotNo : this.LotNo },  'putaway/findlot', "")).subscribe(
        async (res) => {
          if(res.msg != ""){
            this.presentToast(res.msg);
          }
          await this.presentItemLotModal(res.results);
          
        },
        (err) => {
          this.presentToast("Cannot connect to server");
          this.loading.dismiss();
          this.ProdCode = "";
          this.BinNo = "";
          this.ItemKey = "";
          this.LotNo = "";
          this.Location = "";
          this.UOM = "";
          this.QtyOnHand = 0;
          this.QtyAvailable = 0;
          this.ExpDate = "";
          this.PutawayQty = 0;
          this.NewBinNo = "";
          this.docno = "";
        }
      );
    }

  }

  itemclearInput(){
        this.ProdCode = "";
        this.BinNo = "";
        this.ItemKey = "";
        this.LotNo = "";
        this.Location = "";
        this.UOM = "";
        this.QtyOnHand = 0;
        this.QtyAvailable = 0;
        this.ExpDate = "";
        this.PutawayQty = 0;
        this.NewBinNo = "";
        this.docno = "";
  }

  btnBack(){
    this.navCtrl.navigateRoot('/home');

  }
}
