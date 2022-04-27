import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LabTestComponent } from './lab-test.component';
const routes: Routes = [
  {
    path: '', component: LabTestComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class LabTestRoutingModule { }