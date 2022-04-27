import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DrConsultationComponent } from './dr-consultation.component';

const routes: Routes = [
  {
    path: '', component: DrConsultationComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DrConsultationRoutingModule { }
