import { Component, OnInit } from '@angular/core';
import { Config, NavController, Platform, ToastController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';


@Component({
  selector: 'app-settings',
  templateUrl: './settings.page.html',
  styleUrls: ['./settings.page.scss'],
})
export class SettingsPage implements OnInit {
  
  server = '';

  constructor(
    private platform: Platform, 
    private storage: Storage,
    private navCtrl: NavController,
    private toastController: ToastController,
    ) { }

  async ngOnInit() {
    this.server = await this.storage.get('server_type');
  }

  async saveServer(){
    await this.storage.set('server_type', this.server);
  }
  
  btnBack(){
    this.navCtrl.navigateRoot('/home');

  }

}
