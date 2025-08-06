import { HttpClient, HttpHeaders  } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AlertController, ToastController } from '@ionic/angular';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { AuthService } from './auth.service';
import { Platform } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';

export interface ApiResult {
  results: any[];
  nwfphsession: string;
  norecord: number;
  msg: string;
}

export interface ApiMsgResult {
  msgno: string,
  msg: number
}

@Injectable({
  providedIn: 'root',
})
export class ApiService {

  server = '';

  constructor(
      private http: HttpClient,
      private toastController:ToastController,
      private authapi: AuthService,
      private alertController: AlertController,
      private platform: Platform, 
      private storage: Storage
      ) {}

    async getServer() { 
      var myserver =  await this.storage.get('server_type');
      var mys = ''
      if(myserver == "PRODUCTION")
        mys = 'http://192.168.17.15/bme/api';
      else
        mys = 'http://192.168.17.15/bme/api';

        return mys;
    }

    apiResult : any =[];

    async presentToast(msg:string) {
      const toast = await this.toastController.create({
        message: msg,
        duration: 5000
      });
      toast.present();
    }

    async presentAlert(msg:string) {
      var mm = msg.split("\n");
      const alert = await this.alertController.create({
        header: 'Alert',
        subHeader: mm[0],
        message: mm[1],
        buttons: ['OK'],
      });
  
      await alert.present();
    }

    async post(param: any, url: string, nwfphsession: string):  Promise<Observable<any>>  {
      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession,
          'Content-Type' : 'application/x-www-form-urlencoded',
        });
      
      const userinfo = await this.authapi.get_userinfo();
      const params = new URLSearchParams(param);
      params.append("nwfph-session",nwfphsession);
      params.append("uname",userinfo.uname);
      params.append("sys_enter_by",userinfo.uname);

      return this.http.post<any>(
        `${baseUrl}/${url}`, params,{ headers:requestHeaders }
      );
    }

    async post2(param: any, url: string, nwfphsession: string): Promise<any>{

      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession,
          'Content-Type' : 'application/x-www-form-urlencoded',
        });
      
      const userinfo = await this.authapi.get_userinfo();
      const params = new URLSearchParams(param);
      params.append("nwfph-session",userinfo.nwfphsession);
      params.append("uname",userinfo.uname);


      try {
          var mdata = await this.http.post<any>(
          `${baseUrl}/${url}`, params,{ headers:requestHeaders }
          ).toPromise();

          /*
            Additional Security
          */
      
          return mdata;
      } catch (error) {
        this.presentAlert("Cannot connect to server!");
        return [];
      }

      
    }

   async findItemLotpost(param: any, url: string, nwfphsession: string): Promise<Observable<ApiResult>> {
      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession,
          'Content-Type': 'application/x-www-form-urlencoded'
        });
      
      const params = new URLSearchParams();
      params.append("prodcode",param.prodcode);
      params.append("nwfph-session",nwfphsession);

      return this.http.post<ApiResult>(
        `${baseUrl}/${url}`, params,{ headers:requestHeaders }
      );
    } 

    async findBinpost(param: any, url: string, nwfphsession: string): Promise<Observable<ApiResult>> {
      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();;
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession,
          'Content-Type': 'application/x-www-form-urlencoded'
        });
      
      const params = new URLSearchParams();
      params.append("BinNo",param.NewBinNo);
      params.append("nwfph-session",nwfphsession);

      return this.http.post<ApiResult>(
        `${baseUrl}/${url}`, params,{ headers:requestHeaders }
      );
    }

   async transferItem(param: any, url: string, nwfphsession: string): Promise<Observable<ApiResult>> {
      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession,
          'Content-Type': 'application/x-www-form-urlencoded'
        });
      
      const params = new URLSearchParams();
      params.append("lotno",param.lotno);
      params.append("putaway",param.putaway);
      params.append("tobin",param.tobin);
      params.append("itemkey",param.itemkey);
      params.append("bin",param.bin);
      params.append("docno",param.docno);
      params.append("sys_enter_by",param.sys_enter_by);
      params.append("uname",param.uname);
      params.append("nwfph-session",nwfphsession);

      return this.http.post<ApiResult>(
        `${baseUrl}/${url}`, params,{ headers:requestHeaders }
      );
    }

    async get(url: string, nwfphsession: string): Promise<Observable<ApiMsgResult>> {
      var apiKey = "NWFTH";
      var baseUrl = await this.getServer();
      var requestHeaders =new HttpHeaders({
          'X-API-KEY' : apiKey,
          'nwfph-session' : nwfphsession
        });

      return this.http.get<ApiMsgResult>(
        `${baseUrl}/${url}`,{ headers:requestHeaders }
      );
    }

    async getprint(url: string, param:any): Promise<any> {

      var baseUrl = await this.getServer();
      
      var requestHeaders =new HttpHeaders({
       // 'Content-Type' : 'application/x-www-form-urlencoded',
        });

      

      const params = new URLSearchParams();
      params.append("param","^XA^XFE:" + param.mytemplate + "^FS" + param.param + "^XZ");
      /*
      const userinfo = await this.authapi.get_userinfo();
     
      params.append("nwfph-session",userinfo.nwfphsession);
      params.append("uname",userinfo.uname);
      */

      try {
          var mdata = await this.http.post<any>(
          `${baseUrl}/${url}`, params,{ headers:requestHeaders }
          ).toPromise();

          /*
            Additional Security
          */
      
          return mdata;
      } catch (error) {
        this.presentAlert("Cannot connect to server!");
        return [];
      }


    }

}
