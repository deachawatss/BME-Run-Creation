import { Component, OnInit } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';
import { ItemsummaryPage } from '../modal/itemsummary/itemsummary.page';
import { BinsummaryPage } from '../modal/binsummary/binsummary.page';
@Component({
  selector: 'app-itemsearch',
  templateUrl: './itemsearch.page.html',
  styleUrls: ['./itemsearch.page.scss'],
})
export class ItemsearchPage implements OnInit {
  ProdCode : string = "";
  LotCode : string = "";
  Itemkey : string = "";
  BinNo : string = "";
  mydata : any;
  search : string = "";
  searchtype :string = "";


  constructor(
    private navCtrl: NavController,
    private toastController: ToastController,
    private modalCtrl: ModalController,
    private authservice: AuthService,
    private api : ApiService,
    private loadingCtrl: LoadingController,
  ) { }

  ngOnInit() {
  }

  loading:any;

  async presentItemModal(data:any){
    const modal = await this.modalCtrl.create({
      component: ItemsummaryPage,
      handle: false,
      componentProps: {
        itemkey : data.itemkey,
        uom: data.uom,
        description: data.description,
        //QtyOnHand: data,
        //QtyOnOrder: data,
       // QtyCommited: data,
        QtyOnHandData: data.onhand,
        QtyOnOrderData: data.podata,
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

  async presentBinModal(data:any){
    const modal = await this.modalCtrl.create({
      component: BinsummaryPage,
      handle: false,
      componentProps: {
        bindesc : data.Description,
        binname: data.BinNo,
        bindata: data.itemdetails,
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

  async btnSearch(){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });

    await this.loading.present();


    switch(this.searchtype){

      case 'prodcode':{
          await (await this.api.post({ "prodcode": this.search, 'searchType': this.searchtype }, 'ItemSearch/findlot', "")).subscribe(
          async (res) => {
             if(res.msg == ""){
               this.mydata = res.results;
             }
     
             this.loading.dismiss();
           },
           (err) => {
             this.presentToast("Error");
             this.loading.dismiss();
           });

        break;
      }

      case 'itemkey':{

        await (await this.api.post({ "itemkey": this.search, 'searchType': this.searchtype }, 'ItemSearch/findlot', "")).subscribe(
          async (res) => {
             if(res.msg == ""){
               this.mydata = res.results;
             }
     
             this.loading.dismiss();
           },
           (err) => {
             this.presentToast("Error");
             this.loading.dismiss();
           });

        break;
      }

      case 'lot':{

        await (await this.api.post({ "lotno": this.search, 'searchType': this.searchtype }, 'ItemSearch/findlot', "")).subscribe(
          async (res) => {
             if(res.msg == ""){
               this.mydata = res.results;
             }
     
             this.loading.dismiss();
           },
           (err) => {
             this.presentToast("Error");
             this.loading.dismiss();
           });

        break;
      }

      case 'bin':{

        await (await this.api.post({ "binno": this.search, 'searchType': this.searchtype }, 'ItemSearch/findlot', "")).subscribe(
          async (res) => {
             if(res.msg == ""){
               this.mydata = res.results;
             }
     
             this.loading.dismiss();
           },
           (err) => {
             this.presentToast("Error");
             this.loading.dismiss();
           });

        break;
      }

      default:{
        this.loading.dismiss();
        break;
      }

    }

    
  }

  async btnItemSearch(ikey:string){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();
    
    (await this.api.post({ "itemkey": ikey }, 'itemsearch/itemsummary', '')).subscribe(
      async (res) => {
        if(res.msg != ""){
          this.presentToast(res.msg);
        }
        await this.presentItemModal(res);
        
      },
      (err) => {
        this.presentToast("Cannot connect to server");
        this.loading.dismiss();
      }
    );
  }

  async btnBinSearch(BinNo:string){
    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await this.loading.present();

    (await this.api.post({ "BinNo": BinNo }, 'itemsearch/binsummary', '')).subscribe(
      async (res) => {
        if(res.msg != ""){
          this.presentToast(res.msg);
        }
        await this.presentBinModal(res);
        
      },
      (err) => {
        this.presentToast("Cannot connect to server");
        this.loading.dismiss();
      }
    );
  }

  txtclear(){
    this.search = '';
  }

}
