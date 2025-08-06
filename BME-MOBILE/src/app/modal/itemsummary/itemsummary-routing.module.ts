import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ItemsummaryPage } from './itemsummary.page';

const routes: Routes = [
  {
    path: '',
    component: ItemsummaryPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ItemsummaryPageRoutingModule {}
