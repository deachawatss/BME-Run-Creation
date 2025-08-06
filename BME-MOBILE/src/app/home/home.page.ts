import { Component } from '@angular/core';
import { NavController } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';
import { Storage } from '@ionic/storage-angular';
import { Platform } from '@ionic/angular';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage {

  constructor(
    private authService: AuthService,
    private navCtrl: NavController,
    private platform: Platform, 
    private storage: Storage
  ) {}

  server = '';
  
  menu : any = [
    {
      ulvl: ["1","113","112"],
      title: "PUTAWAY / BIN TRANSFER",
      link:"/putaway",
      icon: "fa-solid fa-dolly"
      //icon: "cube-outline"
    },
    {
      ulvl: ["1","113"],
      title: "NEW FG TRANSFER",
      link:"/fgtransfer",
      icon: "fa-solid fa-boxes-packing"
     // icon: "cube-outline"
    },
    {
      ulvl: ["1","113"],
      title: "SALES",
      link:"/dispatch",
      icon: "fa-solid fa-truck-ramp-box"
      //icon: "file-tray-stacked-outline"
    },
    {
      ulvl: ["1","112"],
      title: "BULK PICKING",
      link:"/bulkpick",
      //icon: "file-tray-stacked-outline"
      icon: "fa-solid fa-boxes-stacked"
    },
    {
      ulvl: ["1","112"],
      title: "BULK RM VALIDATION",
      link:"/bulkrmvalidation",
      //icon: "file-tray-stacked-outline"
      icon: "fa-solid fa-boxes-stacked"
    },
    {
      ulvl: ["1"],
      title: "RM VALIDATION",
      link:"/rmvalidation",
      icon: "fa-solid fa-clipboard-check"
      //icon: "shield-checkmark-outline"
    },
    {
      ulvl: ["1","114"],
      title: "PREWEIGH VALIDATION",
      link:"/preweigh-validation",
      icon: "fa-solid fa-clipboard-check"
      //icon: "shield-checkmark-outline"
    },
    {
      ulvl: ["1","114"],
      title: "PREWEIGH INVENTORY",
      link:"/preweigh-inventory",
      icon: "fa-solid fa-warehouse"
      //icon: "shield-checkmark-outline"
    },
    {
      ulvl: ["1","113","112","114"],
      title: "INVENTORY",
      link:"/inventory",
      icon: "fa-solid fa-boxes-packing"
      //icon: "shield-checkmark-outline"
    },
    {
      ulvl: ["1"],
      title: "SYSTEM SETTINGS",
      link:"admin/settings",
      icon: "fas fa-tools"
      //icon: "shield-checkmark-outline"
    },
   /*
    {
      ulvl: ["1"],
      title: "ZPL PRINT",
      link:"/zplprint",
      icon: "file-tray-stacked-outline"
    },
    */
  ];

  menu2 : any = [];

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
  
  async btnGo(i:number){
    this.navCtrl.navigateRoot(this.menu2[i].link);
  }
  
  async btnPutaway(){
    this.navCtrl.navigateRoot('/putaway');

  }

  async btnItemSearch(){
    this.navCtrl.navigateRoot('/itemsearch');

  }

  async btnSales(){
    this.navCtrl.navigateRoot('/picklist');

  }

  async btnLogout(){
    this.authService.logout();
  }

}
