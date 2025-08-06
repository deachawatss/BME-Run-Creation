import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PicklistPage } from './picklist.page';

const routes: Routes = [
  {
    path: '',
    component: PicklistPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PicklistPageRoutingModule {}
