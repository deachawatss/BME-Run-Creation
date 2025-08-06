import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RMvalidationPageRoutingModule } from './rmvalidation-routing.module';

import { RMvalidationPage } from './rmvalidation.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RMvalidationPageRoutingModule
  ],
  declarations: [RMvalidationPage]
})
export class RMvalidationPageModule {}
