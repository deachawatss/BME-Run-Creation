import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PreweighInventoryPageRoutingModule } from './preweigh-inventory-routing.module';

import { PreweighInventoryPage } from './preweigh-inventory.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PreweighInventoryPageRoutingModule
  ],
  declarations: [PreweighInventoryPage]
})
export class PreweighInventoryPageModule {}
