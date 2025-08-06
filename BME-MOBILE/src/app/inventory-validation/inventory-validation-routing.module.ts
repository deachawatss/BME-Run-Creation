import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InventoryValidationPage } from './inventory-validation.page';

const routes: Routes = [
  {
    path: '',
    component: InventoryValidationPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InventoryValidationPageRoutingModule {}
