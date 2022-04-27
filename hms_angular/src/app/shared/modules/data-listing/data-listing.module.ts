import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import {FormsModule} from '@angular/forms';
import { AppointmentListComponent } from './appointment-list/appointment-list.component';
import { OpVisitListComponent } from './op-visit-list/op-visit-list.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
@NgModule({
  declarations: [AppointmentListComponent,OpVisitListComponent],
  imports: [
    CommonModule,RouterModule,NgbModule,FormsModule,Ng2SearchPipeModule
  ],
  exports: [AppointmentListComponent,OpVisitListComponent]
})
export class DataListingModule {}
