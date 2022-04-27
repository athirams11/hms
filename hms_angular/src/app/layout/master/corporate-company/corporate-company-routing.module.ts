import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CorporateCompanyComponent } from './corporate-company.component'

const routes: Routes = [{
  path: '', component: CorporateCompanyComponent
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CorporateCompanyRoutingModule { }
