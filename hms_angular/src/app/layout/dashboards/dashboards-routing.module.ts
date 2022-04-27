import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {DashboardsComponent} from './dashboards.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '', component : DashboardsComponent
      },     
       { path: 'dr-consultation', loadChildren: '../master/dr-consultation/dr-consultation.module#DrConsultationModule',canActivate: [AuthGuard],data: {level : 2,path: "/dr-consultation"} },
       { path: 'dr-consultation/:type/:id', loadChildren: '../master/dr-consultation/dr-consultation.module#DrConsultationModule',canActivate: [AuthGuard],data: {level : 2,path: "/dr-consultation"} },
       { path: 'dr-schedule-list', loadChildren: '../master/dr-schedule-list/dr-schedule-list.module#DrScheduleListModule',canActivate: [AuthGuard],data: {level : 2,path: "/dr-schedule-list"} },
       { path: 'doctors', loadChildren: '../master/doctors/doctors.module#DoctorsModule',canActivate: [AuthGuard],data: {level : 2,path: "/doctors"} },
       { path: 'diagnosis', loadChildren: '../master/diagnosis/diagnosis.module#DiagnosisModule',canActivate: [AuthGuard],data: {level : 2,path: "/diagnosis"} },
       { path: 'medicine', loadChildren: '../master/medicine/medicine.module#MedicineModule',canActivate: [AuthGuard],data: {level : 2,path: "/medicine"} },
       { path: 'vaccine', loadChildren: '../master/vaccine/vaccine.module#VaccineModule',canActivate: [AuthGuard],data: {level : 2,path: "/vaccine"} },
       { path: 'current-procedural-terminology', loadChildren: '../master/current-procedural-terminology/current-procedural-terminology.module#CurrentProceduralTerminologyModule',canActivate: [AuthGuard],data: {level : 2,path: "/current-procedural-terminology"} },
       { path: 'op-new-registration', loadChildren: '../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
       { path: 'op-new-registration/:app_id', loadChildren: '../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
       { path: 'op-new-registration/:/:id', loadChildren: '../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
       { path: 'appointment', loadChildren: '../transaction/appointment/appointment.module#AppointmentModule',canActivate: [AuthGuard],data: {level : 2,path: "/appointment"} },
       { path: 'op-visit-entry', loadChildren: '../transaction/op-visit-entry/op-visit-entry.module#OpVisitEntryModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-visit-entry"} },
       { path: 'pre-consulting', loadChildren: '../transaction/pre-consulting/pre-consulting.module#PreConsultingModule',canActivate: [AuthGuard],data: {level : 2,path: "/pre-consulting"} },
       { path: 'consulting', loadChildren: '../transaction/consulting/consulting.module#ConsultingModule',canActivate: [AuthGuard],data: {level : 2,path: "/consulting"} },
       { path: 'billing', loadChildren: '../transaction/billing/billing.module#BillingModule',canActivate: [AuthGuard],data: {level : 2,path: "/billing"} },
       { path: 'insurance', loadChildren: '../claim-process/insurance/insurance.module#InsuranceModule', canActivate: [AuthGuard], data: {level : 2, path: '/insurance'} },
       { path: 'patient-query',canActivate: [AuthGuard],data: {level : 2,path: "/patient-query"} },
       { path: 'appointment-list',canActivate: [AuthGuard],data: {level : 2,path: "/appointment-list"} },
       { path: 'user-access', loadChildren: '../settings/user-access/user-access.module#UserAccessModule',canActivate: [AuthGuard],data: {level : 2,path: "/user-access"} },
       { path: 'user-management', loadChildren: '../settings/user-management/user-management.module#UserManagementModule',canActivate: [AuthGuard],data: {level : 2,path: "/user-management"} },

    ],
    runGuardsAndResolvers: 'always',
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DashboardsRoutingModule { }
