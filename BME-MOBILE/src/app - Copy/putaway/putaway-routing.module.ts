import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PutawayPage } from './putaway.page';

const routes: Routes = [
  {
    path: '',
    component: PutawayPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PutawayPageRoutingModule {}
