import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage-angular';
@Injectable({
  providedIn: 'root'
})
export class StorageService {

  constructor(private storage: Storage) { }

  async saveData(key = '', value : any) {
    await this.storage.set('key', 'value');
  }
  
  async getData(key = '') {
    const value = await this.storage.get(key);
    return value;
  }

  async removeData() {
    await this.storage.remove('key');
  }
}
