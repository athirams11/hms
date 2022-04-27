import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { RadiologyReportComponent } from './radiology-report.component';
const routes: Routes = [
  {
    path: '', component: RadiologyReportComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RadiologyReportRoutingModule { }