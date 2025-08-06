import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PutawayPageRoutingModule } from './putaway-routing.module';

import { PutawayPage } from './putaway.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PutawayPageRoutingModule
  ],
  declarations: [PutawayPage]
})
export class PutawayPageModule {}
