import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ItemlotPageRoutingModule } from './itemlot-routing.module';

import { ItemlotPage } from './itemlot.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ItemlotPageRoutingModule
  ],
  declarations: [ItemlotPage]
})
export class ItemlotPageModule {}
