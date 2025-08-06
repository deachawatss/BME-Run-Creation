import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RunlistPage } from './runlist.page';

const routes: Routes = [
  {
    path: '',
    component: RunlistPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RunlistPageRoutingModule {}
