import { Component } from '@angular/core';
import { NavController } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';
import { Storage } from '@ionic/storage-angular';
import { Platform } from '@ionic/angular';

@Component({
  selector: 'app-inventory',
  templateUrl: './inventory.page.html',
  styleUrls: ['./inventory.page.scss'],
})
export class InventoryPage {
  
  menu : any = [
    {
      ulvl: ["1","113","112","114"],
      title: "INVENTORY COUNT",
      link:"/inventory-count",
      icon: "fas fa-file-alt icon-css"
      //icon: "cube-outline"
    },
    {
      ulvl: ["1","113","112","114"],
      title: "INVENTORY VALIDATION",
      link:"/inventory-validation",
      icon: "fas fa-clipboard-check icon-css"
      //icon: "cube-outline"
    },
    {
      ulvl: ["1","113","112","114"],
      title: "INVENTORY SEARCH",
      link:"/itemsearch",
      icon: "fas fa-search icon-css"
      //icon: "cube-outline"
    },
  ];

  menu2 : any = [];
  server = '';

  constructor(
    private authService: AuthService,
    private navCtrl: NavController,
    private platform: Platform, 
    private storage: Storage
  ) {}

  async ngOnInit() {
    const uu = await this.authService.get_userinfo();
    this.server = await this.storage.get('server_type');
    this.menu2 = await this.menu.filter((items:any) =>{
      if (items.ulvl.indexOf(uu.ulvl) >= 0) {
        return true;
      }
      else
        return false;
    });
    
  }

  btnBack(){
    this.navCtrl.navigateRoot('/home');
  }

  async btnGo(i:number){
    this.navCtrl.navigateRoot(this.menu2[i].link);
  }

}
