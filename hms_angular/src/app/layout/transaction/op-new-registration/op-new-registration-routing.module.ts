import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {OpNewRegistrationComponent} from './op-new-registration.component';
const routes: Routes = [
  {
    path: '', component: OpNewRegistrationComponent
  }
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OpNewRegistrationRoutingModule { }
