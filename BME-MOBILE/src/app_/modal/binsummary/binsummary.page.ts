import { Component, OnInit } from '@angular/core';
import { ModalController, NavController } from '@ionic/angular';
@Component({
  selector: 'app-binsummary',
  templateUrl: './binsummary.page.html',
  styleUrls: ['./binsummary.page.scss'],
})
export class BinsummaryPage implements OnInit {

  bindesc = "";
  binname = "";
  binItemCount = 0;
  bindata: any = [];

  constructor(
    private modalCtrl: ModalController
  ) { }

  async ngOnInit() {

    await this.bindata.forEach((v:any,k:any)=>{
      //this.QtyOnHand += this.QtyOnHandData[k]['QtyOnHand'];
      this.binItemCount++;
     });
  }

  cancel() {
    return this.modalCtrl.dismiss(null, 'cancel');
  }

}
