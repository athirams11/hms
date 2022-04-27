import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {TransactionComponent} from './transaction.component';
import { AuthGuard } from '../.././shared';
const routes: Routes = [
    {
      path: '', 
      children: [
        {
          path: '', component: TransactionComponent
        },
        { path: 'op-new-registration', loadChildren: './op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
        { path: 'op-new-registration/:app_id', loadChildren: './op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
        { path: 'op-new-registration/:/:id', loadChildren: './op-new-registration/op-new-registration.module#OpNewRegistrationModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-new-registration"} },
        { path: 'appointment', loadChildren: './appointment/appointment.module#AppointmentModule',canActivate: [AuthGuard],data: {level : 2,path: "/appointment"} },
        { path: 'dr-appointment', loadChildren: './dr-appointment/dr-appointment.module#DrAppointmentModule',canActivate: [AuthGuard],data: {level : 2,path: "/dr-appointment"} },
        { path: 'op-visit-entry', loadChildren: './op-visit-entry/op-visit-entry.module#OpVisitEntryModule',canActivate: [AuthGuard],data: {level : 2,path: "/op-visit-entry"} },
        { path: 'pre-consulting', loadChildren: './pre-consulting/pre-consulting.module#PreConsultingModule',canActivate: [AuthGuard],data: {level : 2,path: "/pre-consulting"} },
        { path: 'consulting', loadChildren: './consulting/consulting.module#ConsultingModule',canActivate: [AuthGuard],data: {level : 2,path: "/consulting"} },
        { path: 'billing', loadChildren: './billing/billing.module#BillingModule',canActivate: [AuthGuard],data: {level : 2,path: "/billing"} },
        { path: 'bill', loadChildren: './bill/bill.module#BillModule',canActivate: [AuthGuard],data: {level : 2,path: "/bill"} },

      ],
      runGuardsAndResolvers: 'always',
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TransactionRoutingModule { }
