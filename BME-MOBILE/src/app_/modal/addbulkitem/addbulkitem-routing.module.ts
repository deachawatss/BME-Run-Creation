import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AddbulkitemPage } from './addbulkitem.page';

const routes: Routes = [
  {
    path: '',
    component: AddbulkitemPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AddbulkitemPageRoutingModule {}
