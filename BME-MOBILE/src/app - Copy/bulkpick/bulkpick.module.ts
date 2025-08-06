import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BulkpickPageRoutingModule } from './bulkpick-routing.module';

import { BulkpickPage } from './bulkpick.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BulkpickPageRoutingModule
  ],
  declarations: [BulkpickPage]
})
export class BulkpickPageModule {}
