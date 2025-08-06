import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { FGTransferPageRoutingModule } from './fgtransfer-routing.module';

import { FGTransferPage } from './fgtransfer.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    FGTransferPageRoutingModule
  ],
  declarations: [FGTransferPage]
})
export class FGTransferPageModule {}
