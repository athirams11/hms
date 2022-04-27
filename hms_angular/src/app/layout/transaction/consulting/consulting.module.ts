import { NO_ERRORS_SCHEMA,NgModule } from '@angular/core';
import { FormsModule , ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { TextMaskModule } from 'angular2-text-mask';
import { PageHeaderModule } from '../../../shared';
import { DataListingModule } from '../../../shared/modules/data-listing/data-listing.module';
import { NgMultiSelectDropDownModule } from 'ng-multiselect-dropdown';
import { CommonModule } from '@angular/common';
import { ConsultingRoutingModule } from './consulting-routing.module';
import { ConsultingComponent } from './consulting.component';
import { ConsultingPageComponent } from './consulting-page/consulting-page.component';
import { DrTreatmentSummmaryComponent } from './consulting-page/dr-treatment-summmary/dr-treatment-summmary.component';
import { NursingNoteComponent } from './consulting-page/nursing-note/nursing-note.component';
import { ChiefComplaintsComponent } from './consulting-page/chief-complaints/chief-complaints.component';
import { AllergiesComponent } from './consulting-page/allergies/allergies.component';
import { DiagnosisComponent } from './consulting-page/diagnosis/diagnosis.component';
import { VitalDataModule } from '../../../shared/modules/vital-data/vital-data.module';
import { AssessmentDataModule } from '../../../shared/modules/assessment-data/assessment-data.module';
import { PrescriptionComponent } from './consulting-page/prescription/prescription.component';
import { ReportsComponent } from './consulting-page/reports/reports.component';
import { ImmunizationComponent } from './consulting-page/immunization/immunization.component';
import { MedicalReportComponent } from './consulting-page/medical-report/medical-report.component';
import { VitalSignalsComponent } from './consulting-page/vital-signals/vital-signals.component';
import { LaboratoryComponent } from './consulting-page/laboratory/laboratory.component';
import { pipe } from '@angular/core/src/render3';
import { InvestigativeProcedureComponent } from './consulting-page/investigative-procedure/investigative-procedure.component';
import { SpecialCommentsComponent } from './consulting-page/special-comments/special-comments.component';
import { FileUploadComponent } from './consulting-page/file-upload/file-upload.component';
import { SelectDropDownModule } from 'ngx-select-dropdown'
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import { NgxPrintModule } from 'ngx-print';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';

import { NgxLoadingComponent, NgxLoadingModule }  from 'ngx-loading';
import { LaboratoryProcedureComponent } from './consulting-page/laboratory-procedure/laboratory-procedure.component';

import { ResponsiveModule } from 'ngx-responsive';
import { ExportAsModule } from 'ngx-export-as';
import { DentalComplaintComponent } from './consulting-page/dental-complaint/dental-complaint.component';
import { DentalInvestigationComponent } from './consulting-page/dental-investigation/dental-investigation.component'

import { PainAssessmentComponent } from './consulting-page/pain-assessment/pain-assessment.component'
import { SickLeaveCertificateComponent } from './consulting-page/sick-leave-certificate/sick-leave-certificate.component'
import {Nl2BrPipeModule} from 'nl2br-pipe';
import { DoctorPageComponent } from './doctor-page/doctor-page.component';
import { ChiefcomplaintsComponent } from './consulting-page/chiefcomplaints/chiefcomplaints.component';
import { UploadFileComponent } from './consulting-page/upload-file/upload-file.component';
import { SickLeaveComponent } from './consulting-page/sick-leave/sick-leave.component';
import { LabReportComponent } from './consulting-page/lab-report/lab-report.component';
import { RadiologyReportComponent } from './consulting-page/radiology-report/radiology-report.component';
const config = {
  breakPoints: {
      xs: {max: 600},
      sm: {min: 601, max: 959},
      md: {min: 960, max: 1279},
      lg: {min: 1280, max: 1919},
      xl: {min: 1920}
  },
  debounceTime: 100
};
@NgModule({

  declarations: [
    ConsultingComponent, 
    ConsultingPageComponent, 
    DrTreatmentSummmaryComponent, 
    NursingNoteComponent, 
    ChiefComplaintsComponent, 
    AllergiesComponent, 
    DiagnosisComponent, 
    PrescriptionComponent, 
    ReportsComponent, 
    ImmunizationComponent,
    MedicalReportComponent,
    VitalSignalsComponent, 
    LaboratoryComponent, 
    InvestigativeProcedureComponent, 
    SpecialCommentsComponent, 
    FileUploadComponent, 
    LaboratoryProcedureComponent, 
    PainAssessmentComponent, 
    DentalComplaintComponent, 
    DentalInvestigationComponent,
    SickLeaveCertificateComponent,
    DoctorPageComponent,
    ChiefcomplaintsComponent,
    UploadFileComponent,
    SickLeaveComponent,
    LabReportComponent,
    RadiologyReportComponent
  ],

  imports: [
    NgbModule,
    FormsModule,
    TextMaskModule,
    PageHeaderModule,
    DataListingModule,
    CommonModule,
    ReactiveFormsModule,
    ConsultingRoutingModule,
    VitalDataModule,
    AssessmentDataModule,
    NgMultiSelectDropDownModule,
    NgxLoadingModule,
    SelectDropDownModule,
    Ng2SearchPipeModule,
    NgxPrintModule,
    ExportAsModule,
    Nl2BrPipeModule,
    ResponsiveModule.forRoot(config),
    ConfirmationPopoverModule.forRoot({
      confirmButtonType: 'success',
      confirmText : 'Yes',
      cancelText : 'No',
    
    })
    //readXlsxFile
    //Ng2SearchPipeModule 
    // DocumentComponent
  ],
  schemas: [
    NO_ERRORS_SCHEMA
  ]
})
export class ConsultingModule { }
