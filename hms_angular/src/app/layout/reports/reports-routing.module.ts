import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from '../.././shared';
import { ReportsComponent } from './reports.component';
const routes: Routes = [
  {
      path: '', 
      children: [
        {
          path: '', component: ReportsComponent
        },
        { path: 'cash-report', loadChildren: './cash-report/cash-report.module#CashReportModule', canActivate: [AuthGuard], data: {level : 2,path: '/cash-report'} },
        { path: 'credit-report', loadChildren: './credit-report/credit-report.module#CreditReportModule', canActivate: [AuthGuard], data: {level : 2,path: '/credit-report'} },
        { path: 'medical-report', loadChildren: './medical-report/medical-report.module#MedicalReportModule', canActivate: [AuthGuard], data: {level : 2,path: '/medical-report'} },
        { path: 'visit-report', loadChildren: './visit-report/visit-report.module#VisitReportModule', canActivate: [AuthGuard], data: {level : 2,path: '/visit-report'} },
       

      ],
      runGuardsAndResolvers: 'always',
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ReportsRoutingModule { }
