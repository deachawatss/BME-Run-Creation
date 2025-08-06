import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InventoryValidationPageRoutingModule } from './inventory-validation-routing.module';

import { InventoryValidationPage } from './inventory-validation.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InventoryValidationPageRoutingModule
  ],
  declarations: [InventoryValidationPage]
})
export class InventoryValidationPageModule {}
