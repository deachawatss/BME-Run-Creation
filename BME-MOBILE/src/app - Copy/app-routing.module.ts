import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './guards/auth.guard';
const routes: Routes = [
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then( m => m.HomePageModule),
    canActivate: [AuthGuard],
  },
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'putaway',
    loadChildren: () => import('./putaway/putaway.module').then( m => m.PutawayPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'picklist',
    loadChildren: () => import('./picklist/picklist.module').then( m => m.PicklistPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'itemsearch',
    loadChildren: () => import('./itemsearch/itemsearch.module').then( m => m.ItemsearchPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'itemlot',
    loadChildren: () => import('./modal/itemlot/itemlot.module').then( m => m.ItemlotPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'bin',
    loadChildren: () => import('./modal/bin/bin.module').then( m => m.BinPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'itemsummary',
    loadChildren: () => import('./modal/itemsummary/itemsummary.module').then( m => m.ItemsummaryPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'binsummary',
    loadChildren: () => import('./modal/binsummary/binsummary.module').then( m => m.BinsummaryPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'fgtransfer',
    loadChildren: () => import('./fgtransfer/fgtransfer.module').then( m => m.FGTransferPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'dispatch',
    loadChildren: () => import('./dispatch/dispatch.module').then( m => m.DispatchPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'salesdispatch',
    loadChildren: () => import('./modal/salesdispatch/salesdispatch.module').then( m => m.SalesdispatchPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'itempick',
    loadChildren: () => import('./modal/itempick/itempick.module').then( m => m.ItempickPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'bulkpick',
    loadChildren: () => import('./bulkpick/bulkpick.module').then( m => m.BulkpickPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'runlist',
    loadChildren: () => import('./modal/runlist/runlist.module').then( m => m.RunlistPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'itemrundata',
    loadChildren: () => import('./modal/itemrundata/itemrundata.module').then( m => m.ItemrundataPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'addbulk',
    loadChildren: () => import('./modal/addbulk/addbulk.module').then( m => m.AddbulkPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'addbulkitem',
    loadChildren: () => import('./modal/addbulkitem/addbulkitem.module').then( m => m.AddbulkitemPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'rmvalidation',
    loadChildren: () => import('./rmvalidation/rmvalidation.module').then( m => m.RMvalidationPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'bulkitem',
    loadChildren: () => import('./modal/bulkitem/bulkitem.module').then( m => m.BulkitemPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'admin/settings',
    loadChildren: () => import('./admin/settings/settings.module').then( m => m.SettingsPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'admin/home',
    loadChildren: () => import('./admin/home/home.module').then( m => m.HomePageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'preweigh-validation',
    loadChildren: () => import('./preweigh-validation/preweigh-validation.module').then( m => m.PreweighValidationPageModule),
    canActivate: [AuthGuard],
  },
  {
    path: 'preweigh-inventory',
    loadChildren: () => import('./preweigh-inventory/preweigh-inventory.module').then( m => m.PreweighInventoryPageModule),
    canActivate: [AuthGuard],
  },









];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
