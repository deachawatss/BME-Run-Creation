import { Component } from '@angular/core';
import { Storage } from '@ionic/storage-angular';
import { Platform } from '@ionic/angular';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  
  constructor(private platform: Platform, private storage: Storage) {
    this.initializeApp();
  }

  initializeApp() {
    this.platform.ready().then(async () => {
      const isFirstLaunch = await this.storage.get('isFirstLaunch');
      const serveris = await this.storage.get('server_type');

      if (!isFirstLaunch) {
        // Set initial value
        await this.storage.set('server_type', 'TEST');
        // Set flag to indicate first launch
        await this.storage.set('isFirstLaunch', true);
      }

      if(!serveris)
        await this.storage.set('server_type', 'TEST');
    });
  }

  async ngOnInit() {
    await this.storage.create();
  }
}
