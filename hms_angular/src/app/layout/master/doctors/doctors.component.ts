import { Component, OnInit,ViewChild, TemplateRef } from '@angular/core';
import {DatePipe} from '@angular/common';
import { Router, ActivatedRoute } from '@angular/router';
import { routerTransition } from '../../../router.animations';
import { DoctorsService } from '../../../shared/services'
import { CompileShallowModuleMetadata } from '@angular/compiler';
import { NotifierService } from 'angular-notifier';
import { NgxLoadingComponent }  from 'ngx-loading';
import { LoaderService } from '../../../shared';
import * as moment from 'moment';
import * as EmailValidator from 'email-validator';
@Component({
  selector: 'app-doctors',
  templateUrl: './doctors.component.html',
  styleUrls: ['./doctors.component.scss','../master.component.scss'],
  animations: [routerTransition()]
})
export class DoctorsComponent implements OnInit {

  @ViewChild('ngxLoading') ngxLoadingComponent: NgxLoadingComponent;
  private notifier: NotifierService;
  public  user_rights : any = {};
  public  user : any = {};
  public now = new Date();
  public date:any;
  public success_message  ='';
  public failed_message ='';
  public loading = false;
  public department_list : any =[];
  public type_list : any =[];
  public doctorData = {
    doc_id : 0,
    doc_name : "",
    doc_lisc_no: '',
    doc_cat:'',
    doc_dept : {},
    doc_phone : "",
    doc_email : "",
    doc_gender : "1",
    doc_degree : "",
    clinician_user:"",
    clinician_pass:"",
    user_id : '',
    date:''
  };
  dropdownSettings = {};
  dropdown_type_Settings = {};
  doc_list : any =[];
  p = 50;
  public collection: any = '';
  page = 1;
  searching = false;
  searchFailed = false;
  public search_doctor:any='';
  public start: any;
  public limit: any;
  public search: any;
  public status: any;
  index: any;
  constructor(private loaderService:LoaderService ,public rest:DoctorsService,notifierService: NotifierService) { 
    this.notifier = notifierService;
  }

  ngOnInit() {
    this.formatDateTime ();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.doctorData.user_id = this.user.user_id;
    this.getDropdowns();
    this.getDoctorList();
    this.dropdownSettings = {
      singleSelection: false,
      idField: 'OPTIONS_ID',
      textField: 'OPTIONS_NAME',
      selectAllText: 'Select All',
      unSelectAllText: 'UnSelect All',
      itemsShowLimit: 5,
      allowSearchFilter: true
    };
    this.dropdown_type_Settings = {
      singleSelection: true,
      idField: 'SERVICES_ID',
      textField: 'SERVICE_CODE',
      itemsShowLimit: 5,
      allowSearchFilter: true
    };
  }
  onItemSelect(item: any) {
    // console.log(item);
  }
  onSelectAll(items: any) {
    // console.log(items);
  }
  public getDropdowns()
  {
    this.loaderService.display(true);
   this.rest.getDropdowns().subscribe((data: {}) => {
     if(data['status'] == 'Success')
     {
       // console.log(data);
       if(data['department']["status"] == 'Success')
       {
         this.department_list = data['department']['data'];
       }

       if(data['doc_type']["status"] == 'Success')
       {
         this.type_list = data['doc_type']['data'];
       }

       this.loaderService.display(false);
     }
     this.loaderService.display(false);
   });
  }
  public getDoctorList(page=0)
  {
    const limit = 100;
    this.status = '';
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    const postData = {
      start : this.start,
      limit : this.limit,
      search_text:this.search_doctor
    };
    this.loaderService.display(true);
   this.rest.getDoctorList(postData).subscribe((response) => {
     if(response['status'] === 'Success') {
        // console.log(response);
        this.doc_list = response['data'];
        this.collection=response.total_count;
        this.status=response.status
        const i = this.doc_list.length;
        this.index = i + 5;
        this.loaderService.display(false);
     }
     this.loaderService.display(false);
   });
  }
  public editDoctor(doctor_data) {
   // console.log(doctor_data);
    this.doctorData.doc_id = doctor_data.DOCTORS_ID;
    this.doctorData.doc_name = doctor_data.DOCTORS_NAME;
    this.doctorData.doc_lisc_no = doctor_data.DOCTORS_LISCENCE_NO;
    this.doctorData.doc_cat = doctor_data.DOCTOR_CATEGORY;
    this.doctorData.doc_dept = doctor_data.DOCTOR_DEPARTMENTS;
    this.doctorData.doc_email = doctor_data.DOCTORS_EMAIL;
    this.doctorData.doc_gender = doctor_data.DOCTORS_GENDER;
    this.doctorData.doc_phone = doctor_data.DOCTORS_PHONE_NO;
    this.doctorData.doc_degree = doctor_data.DOCTORS_DEGREE;
    this.doctorData.clinician_pass = doctor_data.CLINICIAN_PASS;
    this.doctorData.clinician_user = doctor_data.CLINICIAN_USER;
    window.scrollTo(0, 0);
  }
  public addNewDoctor() {

    if(this.doctorData.doc_name == "")
    {
      // this.failed_message = "Please Enter Doctor Name";
      this.notifier.notify( 'error', 'Please Enter Doctor Name!' );
      return;
    } else if(this.doctorData.doc_lisc_no == "")
    {
      // this.failed_message = "Please Enter Doctor Name";
      this.notifier.notify( 'error', 'Please Enter Loscence No.!' );
      return;
    } else if(!(this.doctorData.doc_cat))
    {
      // this.failed_message = "Please Select A Category";
      this.notifier.notify( 'error', 'Please Select A Category!' );
      return;
    } else if(!(this.doctorData.doc_dept[0]))
    {
      // this.failed_message = "Please Select A Department";
      this.notifier.notify( 'error', 'Please Select A Department!' );
      return;
    } 
      // else if(this.doctorData.doc_phone == "")
      // {
      //   // this.failed_message = "Please Enter Phone No.";
      //   this.notifier.notify( 'error', 'Please Enter Phone No.!' );
      //   return;
      // } else if(this.doctorData.doc_email === "") {

      //     this.notifier.notify( 'error', 'Please Enter the Email !' );
      //     return;
      // } else if(this.doctorData.doc_email !=="") {
      //     const f = 0;
      //    if (EmailValidator.validate(this.doctorData.doc_email)) {
      //   } else {
      //     this.notifier.notify( 'error', 'Please Enter Proper Email !' );
      //     return;
      //   }
      // } 
    else if (this.doctorData.doc_gender === '') {
      // this.failed_message = "Please Select Gender";
      this.notifier.notify( 'error', 'Please Select Gender!' );
      return;
    }
   if (this.doctorData.doc_degree === '') {

      // this.failed_message = "Please Enter Doctor Degree";
      this.notifier.notify( 'error', 'Please Enter Doctor Degree!' );
      return;

    }

    // else if(this.doctorData.doc_degree != ""){
      // console.log(this.doctorData);
      this.loaderService.display(true);
      this.rest.addNewDoctor(this.doctorData).subscribe((result) => {
        // this.router.navigate(['/product-details/'+result._id]);
        // console.log(result);

        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          if (result.response) {
            // this.success_message = result.response;
            this.notifier.notify( 'success', result.response );
          } else {
              // this.success_message = "<strong>Data </strong> saved successfully...";
              this.notifier.notify( 'success', 'Doctor details saved successfully...' );
          }
          setTimeout(() => {
              // this.router.navigate(['master/dr-schedule-list']);
              // window.location.reload();
              this.success_message = '';
            }, 2000);
            this.getDropdowns();
            this.getDoctorList();
            this.doctorData = {
              doc_id : 0,
              doc_name : '',
              doc_lisc_no: '',
              doc_cat: '',
              doc_dept : {},
              doc_phone : '',
              doc_email : '',
              clinician_user:"",
              clinician_pass:"",
              doc_gender : '1',
              doc_degree : '',
              user_id : '',
              date: ''
            };

        } else {
          if (result.response) {

            // this.failed_message = result.response;
            this.notifier.notify( 'error', result.response );
          } else {
            // this.failed_message = "<strong>Failed </strong> to saved the details";
            this.notifier.notify( 'error', 'Failed  to saved the details!' );
          }
          this.loaderService.display(false);
        }

        // MessageBox.show(this.dialog, `Hello, World!`);
      }, (err) => {
        console.log(err);
      });

  // }
}
  public clearForm() {
    this.doctorData = {
      doc_id : 0,
      doc_name : '',
      doc_lisc_no: '',
      doc_cat: '',
      doc_dept : {},
      doc_phone : '',
      doc_email : '',
      clinician_pass:'',
      clinician_user:'',
      doc_gender : '1',
      doc_degree : '',
      user_id : '',
      date: ''
    };
  }
  public formatDateTime () {
    if (this.now ) {
      this.doctorData.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
  public clear_search() { if (this.search_doctor !== '') {
    // this.clearCPT();
    this.search_doctor='';
    this.getDoctorList(1);
    this.status = '';
  }
}
}
