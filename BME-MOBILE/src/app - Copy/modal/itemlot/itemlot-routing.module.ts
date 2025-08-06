import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ItemlotPage } from './itemlot.page';

const routes: Routes = [
  {
    path: '',
    component: ItemlotPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ItemlotPageRoutingModule {}
