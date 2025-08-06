import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ItemsummaryPageRoutingModule } from './itemsummary-routing.module';

import { ItemsummaryPage } from './itemsummary.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ItemsummaryPageRoutingModule
  ],
  declarations: [ItemsummaryPage]
})
export class ItemsummaryPageModule {}
