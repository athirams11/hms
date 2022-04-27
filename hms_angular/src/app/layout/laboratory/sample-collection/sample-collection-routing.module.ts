import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SampleCollectionComponent } from './sample-collection.component';
const routes: Routes = [
  {
    path: '', component: SampleCollectionComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SampleCollectionRoutingModule { }