import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';

import { IonicModule, IonicRouteStrategy } from '@ionic/angular';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { HttpClientModule  } from '@angular/common/http';
import { HTTP, HTTPResponse  } from '@awesome-cordova-plugins/http/ngx';
import { IonicStorageModule} from '@ionic/storage-angular';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { Storage } from '@ionic/storage';

@NgModule({
  declarations: [AppComponent],
  imports: [BrowserModule, IonicModule.forRoot(), AppRoutingModule, HttpClientModule, NgxDatatableModule, IonicStorageModule.forRoot()],
  providers: [
      { provide: RouteReuseStrategy, useClass: IonicRouteStrategy},
      { provide: HTTP, useClass: HTTP},
      { provide: Storage, useClass: Storage},
      
    ],
  bootstrap: [AppComponent],
})
export class AppModule {}
