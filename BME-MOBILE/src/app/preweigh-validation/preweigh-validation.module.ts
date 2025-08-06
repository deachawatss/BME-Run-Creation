import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PreweighValidationPageRoutingModule } from './preweigh-validation-routing.module';

import { PreweighValidationPage } from './preweigh-validation.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PreweighValidationPageRoutingModule
  ],
  declarations: [PreweighValidationPage]
})
export class PreweighValidationPageModule {}
