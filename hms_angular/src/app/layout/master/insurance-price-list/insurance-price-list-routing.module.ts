import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { InsurancePriceListComponent } from './insurance-price-list.component';
const routes: Routes = [
  {
    path: '', component: InsurancePriceListComponent
  }
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class InsurancePriceListRoutingModule { }
