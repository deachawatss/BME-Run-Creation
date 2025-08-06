import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AddbulkitemPageRoutingModule } from './addbulkitem-routing.module';

import { AddbulkitemPage } from './addbulkitem.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AddbulkitemPageRoutingModule
  ],
  declarations: [AddbulkitemPage]
})
export class AddbulkitemPageModule {}
