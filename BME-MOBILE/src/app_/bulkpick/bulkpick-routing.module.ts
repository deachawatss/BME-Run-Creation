import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BulkpickPage } from './bulkpick.page';

const routes: Routes = [
  {
    path: '',
    component: BulkpickPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BulkpickPageRoutingModule {}
