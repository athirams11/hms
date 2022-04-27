import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SampleTypeComponent } from './sample-type.component';
const routes: Routes = [
  {
    path: '', component: SampleTypeComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SampleTypeRoutingModule { }