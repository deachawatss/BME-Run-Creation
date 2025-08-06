import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BulkitemPageRoutingModule } from './bulkitem-routing.module';

import { BulkitemPage } from './bulkitem.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BulkitemPageRoutingModule
  ],
  declarations: [BulkitemPage]
})
export class BulkitemPageModule {}
