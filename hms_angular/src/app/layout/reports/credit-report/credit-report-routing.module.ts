import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreditReportComponent } from './credit-report.component';

const routes: Routes = [
  {
    path: '', component: CreditReportComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CreditReportRoutingModule { }
