import { Component, OnInit, Input , OnChanges, SimpleChanges} from '@angular/core';
import { DatePipe } from '@angular/common';
import { Router } from '@angular/router';
import { LoaderService } from '../../../../../shared';
import { NotifierService } from 'angular-notifier';
import { ConsultingPageComponent } from './../../../consulting/consulting-page/consulting-page.component';
import { ConsultingService,NursingAssesmentService} from './../../../../../shared/services'
import { AppSettings } from './../../../../../app.settings';
import { FormGroup, FormControl } from '@angular/forms';
import * as moment from 'moment';
@Component({
  selector: 'app-treatment-summary',
  templateUrl: './treatment-summary.component.html',
  styleUrls: ['./treatment-summary.component.scss']
})
export class TreatmentSummaryComponent implements OnInit, OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() values:ConsultingPageComponent;
  @Input() consultation_id: number = 0;
  public complaint_list : any = [];
  notifier: NotifierService;
  complaint_data: any = {};
  complaint_id: any;
     
  
  patient_diagnosis_id: any;
  public loading = false;
  patient_other_daignosis: any;
  diagnosis_id_array: any = [];
  diagnosis_list: any;
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router, public rest2:ConsultingService,public rest:NursingAssesmentService,notifierService: NotifierService)
  {
    this.notifier = notifierService;
   }
  ngOnInit() {
    this.getComplaints();
    this.getPatientDiagnosis();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.getComplaints();
    this.getPatientDiagnosis();
  }
  public labels: { id: number, label: string, value: string,key_name:string }[] = [
    { "id": 1, "label": " Complaints" , value: "0",key_name:"complaints"},
    { "id": 2, "label": " Summary of Case", value: "0",key_name:"summary_of_case"},
    { "id": 3, "label": "Past Medical History" , value: "0",key_name:"past_medical_history"},
    { "id": 4, "label": "Drug History" , value: "0",key_name:"drug_history"},
    { "id": 5, "label": "Social History" , value: "0",key_name:"social_history"},
    { "id": 6, "label": "Clinical examinations" , value: "0",key_name:"clinical_examination"},
    { "id": 7, "label": "Other Notes" , value: "0",key_name:"notes"},
    
  ];
  public getComplaints()
  {
    var postData = {
      assessment_id :this.assessment_id,
      patient_id :this.patient_id
    }
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.getComplaints(postData).subscribe((result:{}) => {
    if(result['status'] == 'Success')
     {
        // this.loading = false;
        this.loaderService.display(false);
        this.complaint_list = result["data"]; 
        this.complaint_id=this.complaint_list.CHIEF_COMPLAINT_ID;
         for (var val of this.labels) {
           this.complaint_data[val.key_name]  = this.complaint_list[val.key_name.toUpperCase()];
         }     
     }
     else
     {
      this.loaderService.display(false);
      this.complaint_list  = [];
      this.complaint_data = {};
      this.complaint_id = 0;
     }
   });
  }
  public getPatientDiagnosis()
    {
    var postData = {
      patient_id : this.patient_id,
      assessment_id : this.assessment_id,
    }
    // this.loading = true;
    this.loaderService.display(true);
    this.rest2.getPatientDiagnosis(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        // this.loading = false;
        this.loaderService.display(false);
        this.diagnosis_list = result.data.PATIENT_DIAGNOSIS_DETAILS
      }
      else
      {
        this.diagnosis_list = []
        // this.loading = false;
        this.loaderService.display(false);
      }
      }, (err) => {
        console.log(err);
      });
    } 
 
    
}