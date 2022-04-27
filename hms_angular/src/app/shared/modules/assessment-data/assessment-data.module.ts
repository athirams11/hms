import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {FormsModule} from '@angular/forms';
import { AssessmentDataComponent } from './assessment-data.component';;
@NgModule({
  declarations: [AssessmentDataComponent],
  imports: [
    CommonModule,RouterModule,NgbModule,FormsModule
  ],
  exports: [AssessmentDataComponent]
})
export class AssessmentDataModule { }
