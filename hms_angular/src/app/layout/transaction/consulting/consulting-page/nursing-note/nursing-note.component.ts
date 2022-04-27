import { Component, OnInit, Input, SimpleChanges, OnChanges } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { NursingAssesmentService } from './../../../../../shared/services';
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
import { AppSettings } from './../../../../../app.settings';
import { NotifierService } from 'angular-notifier';
import { ConsultingPageComponent } from '../consulting-page.component';
import { LoaderService } from '../../../../../shared';
import { variable } from '@angular/compiler/src/output/output_ast';
@Component({
  selector: 'app-nursing-note',
  templateUrl: './nursing-note.component.html',
  styleUrls: ['./nursing-note.component.scss']
})
export class NursingNoteComponent implements OnInit, OnChanges {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  @Input() vital_values: any = [];
  @Input() blood_sugar: any = [];
  @Input() values: ConsultingPageComponent;
  public loading = false;
  private notifier: NotifierService;
  public user_rights: any = {};
  public user_data: any = {};
  public vital_params: any = [];
  // public vital_values : any = [];
  public param_values: any = [];
  public vital_form_values: any = [];
  public dateVal = new Date();
  public assessment_entry_id = 0;
  constructor(private loaderService: LoaderService, public datepipe: DatePipe, private router: Router, public rest2: NursingAssesmentService, notifierService: NotifierService) {
    this.notifier = notifierService;
   }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    // this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    // this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  ngOnChanges(changes: SimpleChanges) {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAssesmentParameters();
    this.user_data = JSON.parse(localStorage.getItem('user'));
  }
  public formatTime (time) {
    return  formatTime(time);
  }
  public formatDate (date) {
    return  formatDate(date);
  }
  public formatDateTime (data) {
      return formatDateTime(data);
  }
  public saveVitals() {
    const form_data = this.vital_form_values;
    if (this.vital_form_values.length === 0) {
      // this.failed_message = "Please enter atleast one parameter value";
      this.notifier.notify( 'error', 'Please enter atleast one parameter value!' );
      return;
    }
    const postData = {
      client_date : this.dateVal,
      date  : this.dateVal,
      user_id : this.user_data.user_id,
      assessment_id : this.assessment_id,
      assessment_entry_id : this.assessment_entry_id,
      test_methode : AppSettings.NURSING_ASSESMENT,
      vitals_details : this.vital_form_values
    };
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.saveAssesmentParameters(postData).subscribe((result) => {
      if (result.status === 'Success') {
        // this.loading = false;
        this.loaderService.display(false);
        this.dateVal = new Date();
        this.assessment_entry_id = 0;
        this.getAssesmentParameters();
        // this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
        this.values.getAssesmentParameterValues(this.patient_id, this.assessment_id);
        this.vital_form_values = [];
      }
      this.loaderService.display(false);
    }, (err) => {
   //   console.log(err);
    });
  }
  public getAssesmentParameters() {
      const postData = {
        test_methode : AppSettings.NURSING_ASSESMENT
      };
      // this.loading = true;
      this.loaderService.display(true);
      this.rest2.getAssesmentParameters(postData).subscribe((result) => {
        if (result.status === 'Success') {
          // this.loading = false;
          this.loaderService.display(false);
        // console.log(result.data);
        this.vital_params = result.data;
        // this.router.navigate(['transaction/pre-consulting']);
          // window.location.reload();
        } else {
          // this.loading = false;
          this.loaderService.display(false);
        }
      }, (err) => {
      //  console.log(err);
      });
  }

}
