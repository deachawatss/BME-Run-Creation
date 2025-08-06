import { Component, OnInit } from '@angular/core';
import {  LoadingController,ToastController,ModalController   } from '@ionic/angular';
@Component({
  selector: 'app-bin',
  templateUrl: './bin.page.html',
  styleUrls: ['./bin.page.scss'],
})
export class BinPage implements OnInit {
  bindata:any;
  public results:any;
  constructor(
    private modalCtrl: ModalController
  ) { }

  ngOnInit() {
  }
  cancel() {
    return this.modalCtrl.dismiss(null, 'cancel');
  }

  btnSelect(ind:any){
    return this.modalCtrl.dismiss(this.results[ind]);
  }

  async handleChange(event:any){
    const query = event.target.value.toLowerCase();
    
    this.results = await this.bindata.filter((items:any) =>{
      if (items.BinNo.indexOf(query) >= 0) {
        return true;
      }
      if (items.Description.indexOf(query) >= 0) {
        return true;
      }
      else
        return false;
    });

  }
}
