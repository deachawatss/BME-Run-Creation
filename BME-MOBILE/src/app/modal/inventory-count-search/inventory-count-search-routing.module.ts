import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InventoryCountSearchPage } from './inventory-count-search.page';

const routes: Routes = [
  {
    path: '',
    component: InventoryCountSearchPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InventoryCountSearchPageRoutingModule {}
