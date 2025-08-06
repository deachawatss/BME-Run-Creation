import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PreweighInventoryPage } from './preweigh-inventory.page';

const routes: Routes = [
  {
    path: '',
    component: PreweighInventoryPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PreweighInventoryPageRoutingModule {}
