import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BulkrmvalidationPage } from './bulkrmvalidation.page';

const routes: Routes = [
  {
    path: '',
    component: BulkrmvalidationPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BulkrmvalidationPageRoutingModule {}
