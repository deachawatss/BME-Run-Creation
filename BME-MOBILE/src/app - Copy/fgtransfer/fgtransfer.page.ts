import { Component, OnInit,ViewChild } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { ApiService } from '../services/api.service';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-fgtransfer',
  templateUrl: './fgtransfer.page.html',
  styleUrls: ['./fgtransfer.page.scss'],
})
export class FGTransferPage implements OnInit {
  batchticket = "";
  BinNo = "";
  item = "";
  kgsperbag = 0;
  @ViewChild('frmbatchticket') frmbt: any ;
  @ViewChild('frmitem') frmitem: any ;
  @ViewChild('frmbin') frmbin: any ;
  @ViewChild('frmkgs') frmkgs: any ;
  @ViewChild('btntrans') btntrans: any ;
  
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

  async btnTransfer(){
  
    var mybintransfer = await this.api.post2({batchticket : this.batchticket , BinNo: this.BinNo, item: this.item, kgsperbag : this.kgsperbag},'Fgtransfer/transfer','');
    
    if(mybintransfer.msgno == 200){
      
    }else{
        this.api.presentAlert(mybintransfer.msg);
    }

    this.batchticket = "";
    this.BinNo = "";
    this.item = "";
    this.kgsperbag = 0;
    
  }
 
  itemclearInput(){
    this.batchticket = "";
    this.BinNo = "";
    this.item = "";
    this.kgsperbag = 0;
  }

  txtbatchticket(keyCode:number){
    if(keyCode == 13){
      this.frmitem.setFocus();
    }
  }

  txtitem(keyCode:number){
    if(keyCode == 13){
      this.frmbin.setFocus();
    }
  }

  txtbin(keyCode:number){
    if(keyCode == 13){
      this.frmkgs.setFocus();
    }
  }

  txtkgs(keyCode:number){
    if(keyCode == 13){
      this.btntrans.setFocus();
    }
  }
  

 async btnBTCode(){
    var myparam = {
      mytemplate : "AUTOPRINT.ZPL",
      param : "^mybarcode^FD"+this.batchticket+"^FS"
    };
    await this.api.getprint('',myparam);
  }
}
