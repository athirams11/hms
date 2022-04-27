import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { QueryComponent } from './query.component';
import { AppointmentListComponent } from './appointment-list/appointment-list.component';
import { PatientQueryComponent } from './patient-query/patient-query.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
  {
    path: '', 
    children: [
      {
        path: '', component: QueryComponent
      },
      { path: 'patient-query', component: PatientQueryComponent,canActivate: [AuthGuard],data: {level : 2,path: "/patient-query"} },
      { path: 'appointment-list', component: AppointmentListComponent,canActivate: [AuthGuard],data: {level : 2,path: "/appointment-list"} },
    ],
    runGuardsAndResolvers: 'always',
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class QueryRoutingModule { }
