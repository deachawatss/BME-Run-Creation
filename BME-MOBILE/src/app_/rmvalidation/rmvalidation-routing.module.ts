import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RMvalidationPage } from './rmvalidation.page';

const routes: Routes = [
  {
    path: '',
    component: RMvalidationPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RMvalidationPageRoutingModule {}
