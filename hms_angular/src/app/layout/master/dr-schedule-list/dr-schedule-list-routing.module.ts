import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DrScheduleListComponent } from './dr-schedule-list.component'

const routes: Routes = [
  {
    path: '', component: DrScheduleListComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DrScheduleListRoutingModule { }
