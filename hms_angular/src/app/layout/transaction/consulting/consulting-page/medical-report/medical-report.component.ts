import { Component, OnInit, Input, SimpleChanges, OnChanges } from '@angular/core';
import { ConsultingService, NursingAssesmentService } from 'src/app/shared';
import { NotifierService } from 'angular-notifier';
import {DatePipe} from '@angular/common';
import { Router } from '@angular/router';
import * as moment from 'moment';
import { AppSettings } from 'src/app/app.settings';
import { LoaderService } from '../../../../../shared';
import { formatTime, formatDateTime, formatDate } from './../../../../../shared/class/Utils';
@Component({
  selector: 'app-medical-report',
  templateUrl: './medical-report.component.html',
  styleUrls: ['./medical-report.component.scss']
})
export class MedicalReportComponent implements OnInit,OnChanges {
  @Input() assessment_id: number = 0;
  @Input() patient_id: number = 0;
  @Input() consultation_id: number = 0;
  @Input() selected_visit: any = [];
  @Input() vital_values: any = [];
  public complaint_list : any = [];
  notifier: NotifierService;
  complaint_data: any = {};
  complaint_id: any;
  public vital_params : any = [];
  public param_values : any = [];
  public vital_form_values : any = [];   
  public diagnosis_data = {
    name :"",
    id : "",
    code : [],
    user_id :0,
    patient_diagnosis_id: 0,
    diagnosis_id :0 ,
    patient_id: 0,  
    assessment_id : 0,
    diagnosis_code :0,
    consultation_id:0,
    chief_complaint_id : 0,
    patient_other_daignosis : '',
    diagnosis_id_arr: [""],
    diagnosis_level_arr: [""],
    diagnosis_type_arr: [""],
    }
  patient_diagnosis_id: any;
  patient_other_daignosis: any;
  diagnosis_id_array: any = [];
  diagnosis_list: any;
  other_diagnosis = '';
  allergy:any = {};
  drug_allergy :any = {};
  other_allergy :any = {};
  loading: false;
  public institution = JSON.parse(localStorage.getItem('institution'));
  public logo_path = JSON.parse(localStorage.getItem('logo_path'));
  constructor(private loaderService : LoaderService,public datepipe: DatePipe,private router: Router,public rest2:ConsultingService,public rest:NursingAssesmentService,notifierService: NotifierService)
  {
    this.notifier = notifierService;
   }
   
  ngOnInit() {
    this.getComplaints();
    this.getPatientDiagnosis();
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.getPatientAllergies();
  }
  ngOnChanges(changes: SimpleChanges) {
    this.getComplaints();
    this.getPatientDiagnosis();
    this.getAssesmentParameters();
    this.getAssesmentParameterValues(this.patient_id,this.assessment_id);
    this.getPatientAllergies();
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
    this.loaderService.display(true);
    this.rest2.getComplaints(postData).subscribe((result:{}) => {
    if(result['status'] == 'Success')
     {
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
    
    this.rest2.getPatientDiagnosis(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        this.diagnosis_list = result.data.PATIENT_DIAGNOSIS_DETAILS
        this.other_diagnosis = result.data.PATIENT_OTHER_DIAGNOSIS
      }
      else
      {
        this.diagnosis_list = [];
        this.other_diagnosis = ''
      }
      }, (err) => {
      //  console.log(err);
      });
    }             
    public getAssesmentParameters()
    {
      var postData = {
      test_methode : AppSettings.NURSING_ASSESMENT
      }
      this.loaderService.display(true);
      this.rest.getAssesmentParameters(postData).subscribe((result) => {
        if(result.status == "Success")
        {
          this.loaderService.display(false);
          this.vital_params = result.data;
        }
        else
        {
          this.vital_params = result.data;
          this.loaderService.display(false);
        }
      }, (err) => {
       // console.log(err);
      });
    } 
    public getAssesmentParameterValues(patient_id=0,assessment_id=0)
    {
        var postData = {
          patient_id : this.patient_id,
          assessment_id : this.assessment_id
        }
        this.loaderService.display(true);
        this.rest.getAssesmentParameterValues(postData).subscribe((result) => {
          if(result.status == "Success")
          {
            this.loaderService.display(false);
            this.vital_values = result.data;
          }
          else
          {
            this.vital_values = result.data;
            this.loaderService.display(false);
          }
        }, (err) => {
        //  console.log(err);
        });
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
    public getPatientAllergies()
    {
    var postData = {
      patient_id : this.patient_id  
    }
    this.loaderService.display(true);
    this.rest.getPatientAllergies(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        this.loaderService.display(false);
        this.allergy = result.data.NO_KNOWN_ALLERGIES;
        //console.log("allergy" +JSON.stringify(this.allergy));
        this.drug_allergy =  result.data.DRUG_ALLERGIES;
        this.other_allergy = result.data.OTHER_ALLERGIES;
      }
      else
      {
        this.loaderService.display(false);
        this.allergy = {};
        this.drug_allergy = {};
        this.other_allergy = {};
      }
      }, (err) => {
      //  console.log(err);
      });
    }             
  print(): void {
    let printContents, popupWin;
    printContents = document.getElementById('print-section').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
          <title>Medical Report</title>
          <style>
          table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
          }
          th, td {
            padding: 5px;
            text-align: left;    
          }
          </style>
        </head>
    <body onload="window.print();window.close()">${printContents}</body>
      </html>`
    );
    popupWin.document.close();
  }
}

