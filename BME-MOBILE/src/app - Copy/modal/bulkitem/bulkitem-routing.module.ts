import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BulkitemPage } from './bulkitem.page';

const routes: Routes = [
  {
    path: '',
    component: BulkitemPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BulkitemPageRoutingModule {}
