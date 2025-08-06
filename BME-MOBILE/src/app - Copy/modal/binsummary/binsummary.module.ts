import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BinsummaryPageRoutingModule } from './binsummary-routing.module';

import { BinsummaryPage } from './binsummary.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BinsummaryPageRoutingModule
  ],
  declarations: [BinsummaryPage]
})
export class BinsummaryPageModule {}
