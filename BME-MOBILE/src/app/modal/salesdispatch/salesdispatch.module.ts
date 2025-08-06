import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { SalesdispatchPageRoutingModule } from './salesdispatch-routing.module';

import { SalesdispatchPage } from './salesdispatch.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    SalesdispatchPageRoutingModule
  ],
  declarations: [SalesdispatchPage]
})
export class SalesdispatchPageModule {}
