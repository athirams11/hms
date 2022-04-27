import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { MedicalReportComponent } from './medical-report.component';

const routes: Routes = [
  {
    path: '', component: MedicalReportComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MedicalReportRoutingModule { }
