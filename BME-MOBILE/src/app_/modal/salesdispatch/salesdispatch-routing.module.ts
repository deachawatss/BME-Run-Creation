import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SalesdispatchPage } from './salesdispatch.page';

const routes: Routes = [
  {
    path: '',
    component: SalesdispatchPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SalesdispatchPageRoutingModule {}
