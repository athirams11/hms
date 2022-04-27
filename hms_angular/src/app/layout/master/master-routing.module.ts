import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuard } from '../.././shared';
import {MasterComponent} from './master.component';
const routes: Routes = [
  {
    path: '',
    children: [
      {
        path: '', component: MasterComponent
      },
      { path: 'dr-consultation', loadChildren: './dr-consultation/dr-consultation.module#DrConsultationModule', canActivate: [AuthGuard], data: {level : 2, path: '/dr-consultation'} },
      { path: 'dr-consultation/:type/:id', loadChildren: './dr-consultation/dr-consultation.module#DrConsultationModule', canActivate: [AuthGuard], data: {level : 2, path: '/dr-consultation'} },
      { path: 'dr-schedule-list', loadChildren: './dr-schedule-list/dr-schedule-list.module#DrScheduleListModule', canActivate: [AuthGuard], data: {level : 2, path: '/dr-schedule-list'} },
      { path: 'doctors', loadChildren: './doctors/doctors.module#DoctorsModule', canActivate: [AuthGuard], data: {level : 2, path: '/doctors'} },
      { path: 'diagnosis', loadChildren: './diagnosis/diagnosis.module#DiagnosisModule', canActivate: [AuthGuard], data: {level : 2, path: '/diagnosis'} },
      { path: 'medicine', loadChildren: './medicine/medicine.module#MedicineModule', canActivate: [AuthGuard], data: {level : 2, path: '/medicine'} },
      { path: 'vaccine', loadChildren: './vaccine/vaccine.module#VaccineModule', canActivate: [AuthGuard], data: {level : 2, path: '/vaccine'} },
      { path: 'current-procedural-terminology', loadChildren: './current-procedural-terminology/current-procedural-terminology.module#CurrentProceduralTerminologyModule', canActivate: [AuthGuard], data: {level : 2, path: '/current-procedural-terminology'} },
      { path: 'tpa-receiver', loadChildren: './tpa-receiver/tpa-receiver.module#TpaReceiverModule', canActivate: [AuthGuard], data: {level : 2, path: '/tpa-receiver'} },
      { path: 'ins-payer', loadChildren: './ins-payer/ins-payer.module#InsPayerModule', canActivate: [AuthGuard], data: {level : 2, path: '/ins-payer'} },
      { path: 'ins-network', loadChildren: './ins-network/ins-network.module#InsNetworkModule', canActivate: [AuthGuard], data: {level : 2, path: '/ins-network'} },
      { path: 'insurance-price-list', loadChildren: './insurance-price-list/insurance-price-list.module#InsurancePriceListModule', canActivate: [AuthGuard], data: {level : 2, path: '/insurance-price-list'} },
      { path: 'corporate-company', loadChildren: './corporate-company/corporate-company.module#CorporateCompanyModule', canActivate: [AuthGuard], data: {level : 2, path: '/corporate-company'} },
      { path: 'sample-type', loadChildren: './sample-type/sample-type.module#SampleTypeModule', canActivate: [AuthGuard], data: {level : 2, path: '/sample-type'} },
      { path: 'lab-test', loadChildren: './lab-test/lab-test.module#LabTestModule', canActivate: [AuthGuard], data: {level : 2, path: '/lab-test'} },
    ]

  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MasterRoutingModule { }
