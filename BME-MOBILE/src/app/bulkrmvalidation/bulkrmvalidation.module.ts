import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BulkrmvalidationPageRoutingModule } from './bulkrmvalidation-routing.module';

import { BulkrmvalidationPage } from './bulkrmvalidation.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BulkrmvalidationPageRoutingModule
  ],
  declarations: [BulkrmvalidationPage]
})
export class BulkrmvalidationPageModule {}
