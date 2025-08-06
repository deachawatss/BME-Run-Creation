import { Component, OnInit } from '@angular/core';
import { LoadingController, ModalController, NavController, ToastController } from '@ionic/angular';
import { ApiService } from 'src/app/services/api.service';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-runlist',
  templateUrl: './runlist.page.html',
  styleUrls: ['./runlist.page.scss'],
})
export class RunlistPage implements OnInit {
  mydata : any
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

  btnSelect(i : any){
    return this.modalCtrl.dismiss(this.mydata[i]);
  }

  cancel(){
    return this.modalCtrl.dismiss(null, 'cancel');
  }

}
