import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { FGTransferPage } from './fgtransfer.page';

const routes: Routes = [
  {
    path: '',
    component: FGTransferPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class FGTransferPageRoutingModule {}
