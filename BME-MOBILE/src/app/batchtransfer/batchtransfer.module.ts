import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BatchtransferPageRoutingModule } from './batchtransfer-routing.module';

import { BatchtransferPage } from './batchtransfer.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BatchtransferPageRoutingModule
  ],
  declarations: [BatchtransferPage]
})
export class BatchtransferPageModule {}
