import { Component, OnInit } from '@angular/core';
import {  LoadingController,ToastController,ModalController   } from '@ionic/angular';
@Component({
  selector: 'app-itemlot',
  templateUrl: './itemlot.page.html',
  styleUrls: ['./itemlot.page.scss'],
})
export class ItemlotPage implements OnInit {
  public itemdata:any = [];
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
    
    this.results = await this.itemdata.filter((items:any) =>{
      if (items.ItemKey.indexOf(query) >= 0) {
        return true;
      }
      if (items.LotNo.indexOf(query) >= 0) {
        return true;
      }
      else
        return false;
    });

  }
  
}
