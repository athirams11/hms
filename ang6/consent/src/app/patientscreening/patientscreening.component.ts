import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import {ThemePalette} from '@angular/material/core';
import { Router, NavigationEnd } from '@angular/router';
import { SignaturePad } from 'angular2-signaturepad';
import { HostListener } from "@angular/core";
import { ActivatedRoute } from '@angular/router';
import { PatientService } from '../services';
import {MatSnackBar} from '@angular/material';
import moment from 'moment-timezone';
import {DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE} from '@angular/material/core';
import { formatTime, formatDateTime, formatDate, defaultDateTime } from '../class/Utils';
@Component({
  selector: 'app-patientscreening',
  templateUrl: './patientscreening.component.html',
  styleUrls: ['./patientscreening.component.css']
})
export class PatientscreeningComponent implements OnInit {

  title = 'Patient Screening Form - Covid 19';
  note = 'Positive responses to any of these would likely indicate a deeper discussion with the Dentist before proceeding emergency or urgent treatment.';
  title_ar = 'استمارة فحص المريض';
  lang = 1;
  @ViewChild('signdiv') signdiv: ElementRef;
    @ViewChild('signardiv') signardiv: ElementRef;
  @ViewChild(SignaturePad) signaturePad: SignaturePad;
  signaturePadOptions = { // passed through to szimek/signature_pad constructor
      'minWidth': 1,
      'canvasWidth': 400,
      'canvasHeight': 200,
      'backgroundColor':'#e9ecef'
  };

  questions :any
          
  @HostListener('window:resize', ['$event'])
  scrHeight:any;
    scrWidth:any;
    patient_data:any = null;
    patient_dob:any;
    patient_name:any;
    patient_no: any;
    patient_address: any;
    patient_nationality: any;
    patient_gender: any;
    patient_nationalid: any;
    p_number:any;
    patient_visit:any;
    visit_id:any;
    institution_name:any;
    dateVal = new Date();
    accept_terms = true;
    opnumber = '';
  getScreenSize(event?) {
      this.scrHeight = window.innerHeight;
      this.scrWidth = window.innerWidth;
      
      //console.log(this.scrHeight, this.scrWidth);
  }
  constructor(private router: Router,public rest: PatientService,public snackBar: MatSnackBar) { 
  this.getScreenSize();
  }
  public patient_options: any = [''];
  public patient_nodata: any = [];
  public patients: any = [];
    ngOnInit() {
      this.getDropdowns();
    var width = this.signdiv.nativeElement.offsetWidth;
    
    if(width < 800){

      this.signaturePadOptions.canvasWidth =  width-30;
    this.signaturePadOptions.canvasHeight = width/2;
    }
    this.init_values();
    
    }

    ngAfterViewInit() {
    this.signaturePad.clear()

  //this.signaturePadOptions.canvasHeight = ((80 / 100) * this.scrWidth)/2;
  }
  drawComplete(signaturePad) {
  // will be notified of szimek/signature_pad's onEnd event
  //console.log(this.toccSymptoms);

  }

  drawClear() {
    this.signaturePad.clear()
  }

  drawStart() {
  // will be notified of szimek/signature_pad's onBegin event
  //console.log('begin drawing');
  }
  changeLang(lang){
    this.lang = lang;
  }


  openSnackBar(message: string, action: string) {
    this.snackBar.open(message, action, {
    duration: 3000,
    });
  }
  openSUccessSnackBar(message: string, action: string) {
    this.snackBar.open(message, action, {
    duration: 3000,
    panelClass:'text-success'
    });
  }
  init_values(){
    this.questions = [
      {remark:'',need_remark:false,is_option:false,ques:'Do you have fever or felt hot or feverish recently (14-21 days)?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Do you have shortness of breath or breathing difficulty?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Do you have a cough?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Do you have any symptoms like gastrointestinal upset, headache or fatigue?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Have you experienced recent loss of taste or smell?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Are you in contact with a COVID-19 Positive patient(s)?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Are you above 60 years of age?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Do you have heart disease, lung disease, kidney disease, diabetes or any auto-immune disorder?',ques_ar:'',ans:'',options:[]},
      {remark:'',need_remark:false,is_option:false,ques:'Have you travelled in the past 14 days to any regions affected by COVID-19',ques_ar:'',ans:'',options:[]},
      
  ]
  }

 

  public getPatientDetails($event) {
    //console.log(this.patient_nodata);
    this.opnumber = this.patient_nodata.OP_REGISTRATION_NUMBER;
    if(this.opnumber != ''){
    var sendJson = {
    op_number: this.opnumber,
    dateVal : formatDateTime (this.dateVal),
    timeZone: moment.tz.guess(),
    };
    //this.loaderService.display(true);
    this.rest.getPatientDetails(sendJson).subscribe((result) => {
    
    if (result.status == 'Success') {
      const patient_details = result.data;
        this.p_number = this.opnumber;
      //console.log(patient_details);
      this.patient_data = patient_details.patient_data
      this.patient_dob = this.patient_data.DOB
      this.patient_no = this.patient_data.MOBILE_NO
      this.patient_address = this.patient_data.ADDRESS
      this.patient_nationality = this.patient_data.NATIONALITY_NAME
      this.patient_gender = (this.patient_data.GENDER ==1)?'Mr':'Ms';
      this.patient_nationalid = this.patient_data.NATIONAL_ID
      this.patient_name = this.patient_data.FIRST_NAME + ' ' + this.patient_data.MIDDLE_NAME + ' ' + this.patient_data.LAST_NAME
      this.patient_visit = this.patient_data.PATIENT_VISIT_LIST_ID 
      //this.loaderService.display(false);
    } else {
      this.openSnackBar('No patient / Visit found', 'Failed')
      this.patient_data = null;
      
    }
    }, (err) => {
      console.log(err);
    });
    }
  }
  configer = {
    displayKey: "OP_REGISTRATION_NUMBER",
    search: true, 
    height: '200px',
    placeholder: 'Select patient number',
    limitTo: 239, 
    moreText: '.........', 
    noResultsFound: 'No results found!',
    searchPlaceholder: 'Search',
    searchOnKey: 'OP_REGISTRATION_NUMBER'
  }
  

  // getpatient($event) {
  //   if(this.patient_nodata){
  //     this.opnumber = this.patient_nodata.PATIENT_ID;
  //     this.getPatientDetails();
  //   }
  //   else
  //     this.patient_nodata.opnumber = "";
  // }

   public getDropdowns() {
    this.patients = [];
    const myDate = new Date();
    var sendJson = {
      dateVal : formatDateTime (this.dateVal),
      timeZone: moment.tz.guess(),
      };
      this.rest.getPatientLists(sendJson).subscribe((data) => {
      if (data['status'] === 'Success') {
          this.patients = data['data'];
          // this.patient_nodata = this.patients;
          for (let index = 0; index < this.patients.length; index++) {
            if (this.patients[index].PATIENT_ID == this.opnumber) {
              this.patient_nodata = this.patients[index];
            } 
          }
          
      }
    });
  }

  public saveConsent(){
    console.log(this.questions);
    var formData = {
      sign : this.signaturePad.toDataURL(),
      patient_visit: this.patient_visit,
      dateVal: this.dateVal,
      opnumber: this.opnumber,
      type:2,
      selected_options:{
      questions: this.questions
      },
      timeZone: moment.tz.guess(),
    }
    if(this.accept_terms == false){
      this.openSnackBar('Please accept terms', 'Error')
      return;
    }
    var questions_checked: boolean = true
    var isolationtype_checked = false
    var toccSymptoms_checked = false
    this.questions.forEach(function (value) {
    
      if(value.is_option==false && value.ans === ''){
      questions_checked = false;
      return
      }
      else if(value.is_option==true){
      value.options.forEach(function (v) {
        if(v.ans === ''){
          questions_checked = false;
          console.log(v);
          return
          }
      });
      }
    }); 
    if(questions_checked !== true){
      this.openSnackBar('Please answer all options' , 'Error')
      return;
    }
    
    
    if(this.signaturePad.isEmpty()){

      this.openSnackBar('Please sign the consent', 'Error')
      return;
    }
    //console.log(this.signaturePad.toDataURL() )
    if(this.opnumber == ''){
      this.openSnackBar('Invalid op number', 'Error')
      return;
    }
    if(this.patient_visit == null){
      this.openSnackBar('Invalid visit for patient', 'Error')
      return;
    }
    this.rest.saveConsentDetails(formData).subscribe((result) => {
      
      if (result.status == 'Success') {
        this.patient_data = null;
        this.signaturePad.clear()
        this.opnumber = ''
        //this.loaderService.display(false);
        this.openSUccessSnackBar(result.msg, '')
        this.accept_terms = false;
        this.init_values()

      } else {
        this.openSnackBar('No patient / Visit found', 'Failed')
        this.patient_data = null;
        
      }
      }, (err) => {
      console.log(err);
      });

  }
}
