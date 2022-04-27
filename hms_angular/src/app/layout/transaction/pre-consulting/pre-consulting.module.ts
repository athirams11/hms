import { NgModule } from '@angular/core';
import { CommonModule,DatePipe } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';
import { PageHeaderModule } from './../../../shared';
import { DataListingModule } from './../../../shared/modules/data-listing/data-listing.module';
import { PreConsultingRoutingModule } from './pre-consulting-routing.module';
import { PreConsultingComponent } from './pre-consulting.component';
import { NursingAssesmentListComponent } from './nursing-assesment-list/nursing-assesment-list.component';
import { AssessmentPageComponent } from './assessment-page/assessment-page.component';
import { VitalsComponent } from './assessment-page/vitals/vitals.component';
import { AssessmentComponent } from './assessment-page/assessment/assessment.component';
import { SummaryComponent } from './assessment-page/summary/summary.component';
import { TreatmentSummaryComponent } from './assessment-page/treatment-summary/treatment-summary.component';
import { DocumentComponent } from './assessment-page/document/document.component';
import { VitalDataModule } from './../../../shared/modules/vital-data/vital-data.module';
import { PainAssessmentComponent } from './assessment-page/pain-assessment/pain-assessment.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import {Nl2BrPipeModule} from 'nl2br-pipe';
import { NursePageComponent } from './nurse-page/nurse-page.component';
import { AssessmentValuesComponent } from './assessment-page/assessment-values/assessment-values.component';
import { DocumentAssessmentComponent } from './assessment-page/document-assessment/document-assessment.component';
//import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';

@NgModule({
  declarations: [PreConsultingComponent,NursingAssesmentListComponent, AssessmentPageComponent,VitalsComponent, AssessmentComponent, SummaryComponent, TreatmentSummaryComponent, DocumentComponent, PainAssessmentComponent, NursePageComponent, AssessmentValuesComponent, DocumentAssessmentComponent],

  imports: [
    CommonModule,
    PreConsultingRoutingModule,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    PageHeaderModule,
    DataListingModule,
    VitalDataModule,
    Ng2SearchPipeModule,
    Nl2BrPipeModule,
    ConfirmationPopoverModule.forRoot({
      confirmButtonType: 'success',
      confirmText : 'Yes',
      cancelText : 'No',
    
    })
   // NgxLoadingModule
  ],
  providers:[DatePipe]
})
export class PreConsultingModule { }
