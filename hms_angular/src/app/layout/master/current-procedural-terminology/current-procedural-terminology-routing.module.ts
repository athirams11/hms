import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CurrentProceduralTerminologyComponent } from './current-procedural-terminology.component'

const routes: Routes = [{
  path: '', component: CurrentProceduralTerminologyComponent
}];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CurrentProceduralTerminologyRoutingModule { }
