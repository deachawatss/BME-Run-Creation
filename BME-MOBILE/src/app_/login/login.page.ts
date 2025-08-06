import { Component, OnInit } from '@angular/core';
import { environment } from 'src/environments/environment';
import {  LoadingController,ToastController  } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';
import { AuthGuard } from '../guards/auth.guard';
import { NavController } from '@ionic/angular';


@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
  uname: any;
  pword: any;

  constructor(
    private authService: AuthService,
    private loadingCtrl: LoadingController,
    private toastController: ToastController,
    private authGuard : AuthGuard,
    private navCtrl: NavController,
  ) { }


  async ngOnInit() {
    
    const authed = await this.authService.isAuthenticated();
    
    if(authed){
      this.navCtrl.navigateRoot('/home');
    }
  
  }

  async presentToast(msg:string) {
    const toast = await this.toastController.create({
      message: msg,
      duration: 5000
    });
    toast.present();
  }

  async btnlogin(){

    const loading = await this.loadingCtrl.create({
      message: 'Loading..',
      spinner: 'bubbles',
    });
    await loading.present();

    (await this.authService.login(this.uname, this.pword)).subscribe(
      async (res) => {
        loading.dismiss();
          
          if(res.isLogin == true){
            var udata:any = res.results;
            
            if(udata.ulvl == 0){
              this.presentToast("Invalid Username or Password");
            }else{
              this.presentToast("Successfully Login");
              await this.authService.storeUserdata(res);
              await this.authService.isAuthenticated();

            }

          }else{
            this.presentToast("Invalid Username or Password");
            
          }

      },
      (err) => {
        this.presentToast("Cannot connect to server");
        loading.dismiss();
      }
    );


  }

}
