import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PreConsultingComponent } from './pre-consulting.component';
const routes: Routes = [
  { path : '', component : PreConsultingComponent },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PreConsultingRoutingModule { }
