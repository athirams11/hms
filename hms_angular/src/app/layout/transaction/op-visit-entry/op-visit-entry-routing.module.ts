import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { OpVisitEntryComponent } from './op-visit-entry.component'
const routes: Routes = [
 { path : '', component : OpVisitEntryComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
  
})
export class OpVisitEntryRoutingModule { }
