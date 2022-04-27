import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { GeneralComponent } from './general/general.component';
import { Covid19Component } from './covid19/covid19.component';
import { QuestionsComponent } from './questions/questions.component';
import { PatientscreeningComponent } from './patientscreening/patientscreening.component';
import { DashboardComponent } from './dashboard/dashboard.component';

const routes: Routes = [
  { path: '', component: DashboardComponent },
  { path: 'form1', component: GeneralComponent },
  { path: 'form2', component: Covid19Component },
  { path: 'form3', component: QuestionsComponent },
  { path: 'form4', component: PatientscreeningComponent },
];

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forRoot(routes)
  ],
  declarations: [],
  exports: [RouterModule]
})
export class AppRoutingModule { }
