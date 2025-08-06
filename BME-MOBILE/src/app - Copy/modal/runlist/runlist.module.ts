import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RunlistPageRoutingModule } from './runlist-routing.module';

import { RunlistPage } from './runlist.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RunlistPageRoutingModule
  ],
  declarations: [RunlistPage]
})
export class RunlistPageModule {}
