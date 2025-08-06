import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ItemrundataPage } from './itemrundata.page';

const routes: Routes = [
  {
    path: '',
    component: ItemrundataPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ItemrundataPageRoutingModule {}
