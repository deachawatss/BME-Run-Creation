import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BatchtransferPage } from './batchtransfer.page';

const routes: Routes = [
  {
    path: '',
    component: BatchtransferPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BatchtransferPageRoutingModule {}
