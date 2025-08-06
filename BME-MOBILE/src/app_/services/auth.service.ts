import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { environment } from 'src/environments/environment';
import { HTTP, HTTPResponse  } from '@awesome-cordova-plugins/http/ngx';
import { Storage } from '@ionic/storage-angular';
import { NavController } from '@ionic/angular';
import { Platform } from '@ionic/angular';

const TOKEN_KEY = 'userinfo';

export interface ApiResult {
  results: any[];
  isLogin: boolean;
  nwfphsession: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  server = '';
  constructor(
      private http: HttpClient,
      private storage: Storage,
      private navCtrl: NavController,
      private platform: Platform, 
  ) {}

  async getServer() {
    var myserver =  await this.storage.get('server_type');
    var mys = ''
    console.log(myserver);
    if(myserver == "PRODUCTION")
      mys = 'http://192.168.17.15/bme/api';
    else
      mys = 'http://192.168.17.15/bme/api';

      return mys;
  }

  isLoggedIn = false;
  userdata : any;

  /*
    User Level
      1 - Admin
      112 - Warehouse RM
      113 - Warehouse FG
      0 - Not Mobile User
  */

  async login(uname: string, pword: string): Promise<Observable<ApiResult>> {
    var apiKey = "NWFTH";
    var baseUrl = await this.getServer();
    let url = 'Auth/login';
    var requestHeaders =new HttpHeaders({
        'API-KEY' : apiKey,
        'X-API-KEY' : apiKey,
        'Content-Type': 'application/x-www-form-urlencoded'
      });


    const authparam = new URLSearchParams();
    authparam.set('uname', uname);
    authparam.set('pword', pword);

    return await this.http.post<ApiResult>(
      `${baseUrl}/${url}`, authparam, {headers:requestHeaders}
    );
  }

  async storeUserdata(data:any){
    this.storage.set(TOKEN_KEY, data.results).then((response) => {
      this.navCtrl.navigateRoot('/home');
    });

  }

  async isAuthenticated(){
    await this.get_userinfo();
    return this.isLoggedIn; 
  }

  logout(){
    
    this.storage.remove(TOKEN_KEY).then(()=>{
      this.isLoggedIn = false;
      this.navCtrl.navigateRoot('/login');
    });
  }

  async get_userinfo(){
    this.storage.get('userinfo').then(result => {
      if (result != null) {
        this.userdata = result;
        this.isLoggedIn  = true;
      }else{
        this.isLoggedIn = false;
      }
      }).catch(e => {
        this.isLoggedIn = false;
      });
      return this.userdata;
  }

}
