import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AddbulkPage } from './addbulk.page';

const routes: Routes = [
  {
    path: '',
    component: AddbulkPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AddbulkPageRoutingModule {}
