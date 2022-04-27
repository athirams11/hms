import { NgModule,Input,ViewChild,Output} from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DiagnosisComponent } from './diagnosis.component';
import { NgxLoadingComponent } from 'ngx-loading';
const routes: Routes = [
  {
    path: '', component: DiagnosisComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DiagnosisRoutingModule {

  @ViewChild('ngxLoading') ngxLoadingComponent: NgxLoadingComponent;
 }
