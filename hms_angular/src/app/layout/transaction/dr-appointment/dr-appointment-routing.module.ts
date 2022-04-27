import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DrAppointmentComponent } from '../dr-appointment/dr-appointment.component'

const routes: Routes = [
  {
    path: '', component: DrAppointmentComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DrAppointmentRoutingModule { }
