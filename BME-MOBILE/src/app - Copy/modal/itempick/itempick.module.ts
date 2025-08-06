import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ItempickPageRoutingModule } from './itempick-routing.module';

import { ItempickPage } from './itempick.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ItempickPageRoutingModule
  ],
  declarations: [ItempickPage]
})
export class ItempickPageModule {}
