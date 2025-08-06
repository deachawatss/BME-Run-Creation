import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-addbulkitem',
  templateUrl: './addbulkitem.page.html',
  styleUrls: ['./addbulkitem.page.scss'],
})
export class AddbulkitemPage implements OnInit {
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
      if (items.itemkey.indexOf(query) >= 0) {
        return true;
      }
      if (items.lotno.indexOf(query) >= 0) {
        return true;
      }
      else
        return false;
    });

  }

}
