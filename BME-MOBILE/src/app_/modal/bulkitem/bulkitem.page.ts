import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { AlertController } from '@ionic/angular';
@Component({
  selector: 'app-bulkitem',
  templateUrl: './bulkitem.page.html',
  styleUrls: ['./bulkitem.page.scss'],
})
export class BulkitemPage implements OnInit {
  

  constructor(
    private modalCtrl: ModalController,
    private  alertController: AlertController
  ) { }

  ngOnInit() {
  }
  
  myinputtype = 1;
  qty = '';
  bulkqty = '';
  isKgs = true;
  async setInputType(e : any){
    this.myinputtype = e;
    
    if(this.myinputtype == 1)
      this.isKgs = true
    else
      this.isKgs = false
    

  }

  async presentAlert(msg = '') {
    const alert = await this.alertController.create({
      header: 'NWFTH',
      message: msg,
      buttons: ['OK'],
    });

    await alert.present();
  }
  
  async mclose(e: any){
    if(e == 1){

      if(this.myinputtype == 0){

        if(parseFloat(this.qty) > 0 && parseFloat(this.bulkqty) > 0 ){
          return this.modalCtrl.dismiss({qty:this.qty, bulkqty:this.bulkqty, itype: this.myinputtype});
        }else{
          this.presentAlert("KGs and Bulk quantity is required")
          return false;
        }

      }else{

        if(parseFloat(this.bulkqty) > 0 ){
          return this.modalCtrl.dismiss({qty:this.qty, bulkqty:this.bulkqty, itype: this.myinputtype});
        }else{
          this.presentAlert("Bulk quantity is required")
          return false;
        }

      }

      
    }else{
      return this.modalCtrl.dismiss(null, 'cancel');
    }
  }
}
