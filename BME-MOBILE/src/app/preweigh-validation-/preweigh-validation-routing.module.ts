import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PreweighValidationPage } from './preweigh-validation.page';

const routes: Routes = [
  {
    path: '',
    component: PreweighValidationPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PreweighValidationPageRoutingModule {}
