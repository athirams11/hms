
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {RadiologyComponent} from './radiology.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
    {
      path: '', 
      children: [
        {
          path: '', component: RadiologyComponent
        },
        { path: 'radiology-report', loadChildren: './radiology-report/radiology-report.module#RadiologyReportModule',canActivate: [AuthGuard],data: {level : 2,path: "/radiology-report"} },
      ],
      runGuardsAndResolvers: 'always',
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RadiologyRoutingModule { }