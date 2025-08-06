import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InventoryCountPageRoutingModule } from './inventory-count-routing.module';

import { InventoryCountPage } from './inventory-count.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InventoryCountPageRoutingModule
  ],
  declarations: [InventoryCountPage]
})
export class InventoryCountPageModule {}
