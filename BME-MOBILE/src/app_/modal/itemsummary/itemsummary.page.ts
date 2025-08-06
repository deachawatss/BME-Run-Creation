import { Component, OnInit } from '@angular/core';
import { DecimalPipe } from '@angular/common';
import { ModalController, NavController } from '@ionic/angular';

@Component({
  selector: 'app-itemsummary',
  templateUrl: './itemsummary.page.html',
  styleUrls: ['./itemsummary.page.scss'],
})
export class ItemsummaryPage implements OnInit {
  itemkey = "";
  uom = "";
  description = "";
  QtyOnHand = 0;
  QtyOnOrder = 0;
  QtyOnHandAvailable = 0;
  QtyOnOrderRemn = 0;
  QtyCommited = 0;
  QtyOnHandData: any = [];
  QtyOnOrderData: any = [];


  constructor(
    private navCtrl : NavController,
    private modalCtrl: ModalController
  ) { }

  async ngOnInit() {
    
   await this.QtyOnHandData.forEach((v:any,k:any)=>{
    //this.QtyOnHand += this.QtyOnHandData[k]['QtyOnHand'];
    this.QtyOnHand += v.QtyOnHand;
    this.QtyOnHandAvailable += (v.QtyOnHand - v.QtyCommitSales);
   });

   await this.QtyOnOrderData.forEach((v:any,k:any)=>{
    //this.QtyOnOrder += this.QtyOnOrderData[k]['Qtyord'];
    //this.QtyOnOrderRemn += this.QtyOnOrderData[k]['Qtyremn'];
    this.QtyOnOrder += v.Qtyord;
    this.QtyOnOrderRemn += v.Qtyremn;
   });
   

   

  }

  cancel() {
    return this.modalCtrl.dismiss(null, 'cancel');
  }

}
