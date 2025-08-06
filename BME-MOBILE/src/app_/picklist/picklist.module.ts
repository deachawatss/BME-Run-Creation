import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PicklistPageRoutingModule } from './picklist-routing.module';

import { PicklistPage } from './picklist.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PicklistPageRoutingModule
  ],
  declarations: [PicklistPage]
})
export class PicklistPageModule {}
