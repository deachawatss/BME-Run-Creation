import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AddbulkPageRoutingModule } from './addbulk-routing.module';

import { AddbulkPage } from './addbulk.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AddbulkPageRoutingModule
  ],
  declarations: [AddbulkPage]
})
export class AddbulkPageModule {}
