import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InventoryCountSearchPageRoutingModule } from './inventory-count-search-routing.module';

import { InventoryCountSearchPage } from './inventory-count-search.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InventoryCountSearchPageRoutingModule
  ],
  declarations: [InventoryCountSearchPage]
})
export class InventoryCountSearchPageModule {}
