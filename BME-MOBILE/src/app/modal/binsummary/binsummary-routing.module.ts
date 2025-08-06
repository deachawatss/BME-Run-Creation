import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { BinsummaryPage } from './binsummary.page';

const routes: Routes = [
  {
    path: '',
    component: BinsummaryPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BinsummaryPageRoutingModule {}
