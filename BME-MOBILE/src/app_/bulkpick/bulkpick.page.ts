import { Component, OnInit } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';
import { ApiService } from '../services/api.service';
import { RunlistPage } from '../modal/runlist/runlist.page';
import { ItemrundataPage } from '../modal/itemrundata/itemrundata.page';
import { AddbulkPage } from '../modal/addbulk/addbulk.page';

@Component({
  selector: 'app-bulkpick',
  templateUrl: './bulkpick.page.html',
  styleUrls: ['./bulkpick.page.scss'],
})
export class BulkpickPage implements OnInit {
  runno = '';
  formula = '';
  total_batch = 0;
  total_weight = 0;
  rm_itemkey = '';
  description = '';
  suggested_lot_no = '';
  binno = '';
  bulk_packsize1 = 0; 
  bulk_packsize2 = 0;
  total_needed1 = 0;
  total_needed2 = 0;
  total_needed3 = 0;
  remaining_topick1 = 0;
  remaining_topick2 = 0;
  remaining_topick3 = 0;
  batchlistdata  = new Array();
  batchlist : any = [];



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

  btnBack(){
    this.navCtrl.navigateRoot('/home');
  }

  loading:any;

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

  async presentRunModal(data:any){
    
    const modal = await this.modalCtrl.create({
      component: RunlistPage,
      handle: false,
      componentProps: {
        mydata : data.results
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  (modelData:any) => {
      
      if (modelData !== null) {
       this.batchlist = [];
       this.batchlistdata = [];
       var myx : any;
       myx =  (modelData.data.batchlist).split(',');
        
       myx.forEach((element: any) => {
        this.batchlist.push({batchno:element});
        this.batchlistdata.push(element);
       });

       this.runno = modelData.data.runno;
       this.formula = modelData.data.formulaid;
       this.total_batch = myx.length;
       this.total_weight = modelData.data.batchsize * myx.length;
       

      }else{
        this.batchlist = [];
        this.batchlistdata = [];
        this.runno = '';
        this.formula = '';
        this.total_batch = 0;
        this.total_weight = 0;
      }
    });

  }

  async presentItemRunModal(data:any){
   
    const modal = await this.modalCtrl.create({
      component: ItemrundataPage,
      handle: false,
      componentProps: {
        mydata : data.results
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  async (modelData:any) => {
      if (modelData !== null) {
        this.suggested_lot_no = modelData.data.suggested.lotno;
        this.binno = modelData.data.suggested.BinNo;
        this.description = modelData.data.Desc1;
        this.rm_itemkey = modelData.data.ItemKey;
        this.bulk_packsize1 = modelData.data.featurevalue;
        this.bulk_packsize2 = modelData.data.packunit;
        this.total_needed1 = modelData.data.Bulk;
        this.total_needed2 = modelData.data.packunit;
        this.total_needed3 = modelData.data.StdQtyDispUom - modelData.data.PartialData;
        //this.remaining_topick1 = modelData.data.Bulk - (modelData.data.pickedsummary.featurevalue ?? 0) ;
        //this.remaining_topick2 = modelData.data.packunit;
        //this.remaining_topick3 = modelData.data.StdQtyDispUom - ( modelData.data.pickedsummary.tqty ?? 0);
        //this.getitemdetails();
        var counter = 0;
        for(var mm of this.batchlistdata){
          this.batchlist[counter] = {
            batchno : mm,
            nopackpick : 0,
            qtypicked : 0,
            noofpacksrem : modelData.data.Bulk,
            qtyrem : this.total_needed3,
            pickedDetailed : [],
            kgstqty: modelData.data.StdQtyDispUom,
            tqty : modelData.data.picked.tqty
          };
          counter++;
        }

        var dta = modelData.data.picked;

        
        for(var mm of dta){
          var idta = this.batchlistdata.indexOf(mm.batchno);
         
          //console.log(idta);
          //this.batchlist[idta].nopackpick = mm.qty / this.bulk_packsize1;
          //this.batchlist[idta].qtypicked = mm.qty;
          //this.batchlist[idta].noofpacksrem = modelData.data.Bulk - (mm.qty / this.bulk_packsize1);
          //this.batchlist[idta].qtyrem = modelData.data.StdQtyDispUom - mm.qty;

          var pickedlist = await (modelData.data.pickedDetail).filter((items:any) =>{
                              if (items.batchno.indexOf(mm.batchno) >= 0) {
                                return true;
                              }
                              else
                                return false;
                          });

          this.batchlist[idta] = {
            batchno : mm.batchno,
            nopackpick : mm.tbulkqty,
            qtypicked : mm.tqty,
            noofpacksrem : modelData.data.Bulk - mm.tbulkqty,
            qtyrem : this.total_needed3 - mm.tqty,
            pickedDetailed : pickedlist,
            statflag : mm.statflag,
            kgstqty: modelData.data.StdQtyDispUom,
            tqty : modelData.data.picked.tqty
          }
        }
      }else{
       
      }
    });

  }

  async presentBulkModal(batchno : string ,data : any){
    var idta = this.batchlistdata.indexOf(batchno);
    const modal = await this.modalCtrl.create({
      component: AddbulkPage,
      handle: false,
      componentProps: {
        mydata : data.results,
        pickedData : this.batchlist[idta]?.pickedDetailed,
        idesc : this.description,
        bagsneed : this.batchlist[idta]?.noofpacksrem,
        kgstqty : this.batchlist[idta]?.kgstqty,
        remaining : this.batchlist[idta]?.qtyrem,
        packunit : this.bulk_packsize2,
        packsize : this.bulk_packsize1,
        itemkey : this.rm_itemkey,
        runno : this.runno,
        batchno : batchno,
        statflag : this.batchlist[idta]?.statflag,
        tqty : this.batchlist[idta]?.qtypicked,
        mdt : this.batchlist[idta],
        suggestedData: this.suggested_lot_no
      },
    });
    this.loading.dismiss();
    await modal.present();

    await modal.onWillDismiss().then(  async (modelData:any) => {
      this.refreshdata();
    });

  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async runsearch(){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    
    await this.loading.present();

    await (await this.api.post({  }, 'Bulkpick/findrun', "")).subscribe(
      async (res) => {
        
        await this.presentRunModal(res);
         
         this.loading.dismiss();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });


  }

  async specificrunsearch(keyCode:number){
    if(keyCode == 13 ){
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();

      await (await this.api.post({ runno : this.runno }, 'Bulkpick/findrun', "")).subscribe(
        async (res) => {
          
          await this.presentRunModal(res);
          
          this.loading.dismiss();
        },
        (err) => {
          this.presentToast("Error");
          this.loading.dismiss();
        });
    }

  }

  async getitemdetails(){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    
    await this.loading.present();
    
    await (await this.api.post({ runno : this.runno, rmitemkey: this.rm_itemkey }, 'Bulkpick/getrunrec', "")).subscribe(
      async (res) => {
          
          if(res != null){
            
           var dta = this.batchlistdata.indexOf(res.batchno);
           
           //this.batchlist[dta].nopackpick = dta.qty / this.bulk_packsize1;
           //this.batchlist[dta].qtypicked = dta.qty;
           //this.batchlist[dta].noofpacksrem = 
           //this.batchlist[dta].qtyrem

          }
         
         this.loading.dismiss();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });

  }

  async getrundetails(runno : any){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    
    await this.loading.present();
    
    await (await this.api.post({ runno : runno }, 'Bulkpick/getruninfo', "")).subscribe(
      async (res) => {
        
        await this.presentItemRunModal(res);
         
         this.loading.dismiss();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });
  }

  async chkcode(){
    var mstr = this.rm_itemkey
    if( this.rm_itemkey.indexOf('(') !== -1 ){
      var mybcode = await this.getbarcode(this.rm_itemkey)
      mstr = mybcode['02']
    }
    
    console.log(mstr)
      return mstr

  }

  async specificgetrundetails(keyCode:number){
    if(keyCode == 13 ){

      this.rm_itemkey = await this.chkcode()
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      
      await (await this.api.post({ runno : this.runno, itemkey: this.rm_itemkey }, 'Bulkpick/getruninfo', "")).subscribe(
        async (res) => {
          
          await this.presentItemRunModal(res);
          
          this.loading.dismiss();
        },
        (err) => {
          this.presentToast("Error");
          this.loading.dismiss();
        });
      }
  }

  async refreshdata(){

    this.loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    
    await this.loading.present();
    
    await (await this.api.post({ runno : this.runno, rm_itemkey : this.rm_itemkey, packsize: this.bulk_packsize1 }, 'Bulkpick/getiteminfo', "")).subscribe(
      async (res) => {
        
        if (res !== null) {
          this.suggested_lot_no = res.result.suggested.lotno;
          this.binno = res.result.suggested.BinNo;
          this.description = res.result.Desc1;
          this.rm_itemkey = res.result.ItemKey;
          this.bulk_packsize1 = res.result.featurevalue;
          this.bulk_packsize2 = res.result.packunit;
          this.total_needed1 = res.result.Bulk;
          this.total_needed2 = res.result.packunit;
          this.total_needed3 = res.result.StdQtyDispUom - res.result.PartialData;
          //this.remaining_topick1 = modelData.data.Bulk - (modelData.data.pickedsummary.featurevalue ?? 0) ;
          //this.remaining_topick2 = modelData.data.packunit;
          //this.remaining_topick3 = modelData.data.StdQtyDispUom - ( modelData.data.pickedsummary.tqty ?? 0);
          //this.getitemdetails();
          var counter = 0;
          for(var mm of this.batchlistdata){
            this.batchlist[counter] = {
              batchno : mm,
              nopackpick : 0,
              qtypicked : 0,
              noofpacksrem : res.result.Bulk,
              qtyrem : this.total_needed3,
              pickedDetailed : [],
              kgstqty: res.result.StdQtyDispUom,
              tqty : res.result.picked.tqty
            };
            counter++;
          }
  
          var dta = res.result.picked;
  
          //console.log(dta);
          for(var mm of dta){
            var idta = this.batchlistdata.indexOf(mm.batchno);
           
            //console.log(idta);
            //this.batchlist[idta].nopackpick = mm.qty / this.bulk_packsize1;
            //this.batchlist[idta].qtypicked = mm.qty;
            //this.batchlist[idta].noofpacksrem = modelData.data.Bulk - (mm.qty / this.bulk_packsize1);
            //this.batchlist[idta].qtyrem = modelData.data.StdQtyDispUom - mm.qty;
  
            var pickedlist = await (res.result.pickedDetail).filter((items:any) =>{
                                if (items.batchno.indexOf(mm.batchno) >= 0) {
                                  return true;
                                }
                                else
                                  return false;
                            });
  
            this.batchlist[idta] = {
              batchno : mm.batchno,
              nopackpick : mm.tbulkqty,
              qtypicked : mm.tqty,
              noofpacksrem : res.result.Bulk - mm.tbulkqty,
              qtyrem : this.total_needed3 - mm.tqty,
              pickedDetailed : pickedlist,
              statflag : mm.statflag,
              kgstqty: res.result.StdQtyDispUom,
              tqty : res.result.picked.tqty
            }
          }

        }
         
         this.loading.dismiss();
       },
       (err) => {
         this.presentToast("Error");
         this.loading.dismiss();
       });

  }

  async addBulk(batchno : any){

    if(batchno != null){
      
      this.loading = await this.loadingCtrl.create({
        message: 'Loading..',
        spinner: 'bubbles',
      });
      
      await this.loading.present();
      
      await (await this.api.post({ runno : this.runno, rmitemkey: this.rm_itemkey, batchno : batchno }, 'Bulkpick/getitemlist', "")).subscribe(
        async (res) => {
            if(res != null){
              this.presentBulkModal(batchno,res.results)
            }
           
           this.loading.dismiss();
         },
         (err) => {
           this.presentToast("Error");
           this.loading.dismiss();
         });
        
    }


  }

}
