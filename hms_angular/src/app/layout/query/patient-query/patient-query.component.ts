import { Component, OnInit } from '@angular/core';
import {DatePipe} from '@angular/common';
import { routerTransition } from '../../../router.animations';
import { PatientQueryService,LoaderService } from '../../../shared'
@Component({
  selector: 'app-patient-query',
  templateUrl: './patient-query.component.html',
  styleUrls: ['./patient-query.component.scss'],
  animations: [routerTransition()],
  providers: [PatientQueryService]
})
export class PatientQueryComponent implements OnInit {
  public patient_list : any = [0];
  public user_rights : any ={};
  p= 50;
  k=50;
  public collection: any = '';
  page = 1;
  searching = false;
  searchFailed = false;
  public search_patient:any='';
  public start: any;
  public limit: any;
  public search: any;
  public status: any;
  constructor(public rest:PatientQueryService, public datepipe: DatePipe,private loaderService:LoaderService) { }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getPatientList();
  }
  public getPatient()
  {
    if(this.search_patient.length > 2)
    {
      this.getPatientList();
    }
  }
  public getPatientList(page=0)
  {
    const limit = 50;
    this.status = '';
    const starting = page * limit;
    this.start = starting;
    this.limit = limit;
    // console.log("starting "+starting);
    const postData = {
      start : this.start,
      limit : this.limit,
      search_text:this.search_patient
    };
    this.loaderService.display(true);
    this.rest.getPatientList(postData).subscribe((result) => {
      if(result.status == "Success")
      {
        this.status = result['status'];
        this.loaderService.display(false);
        this.patient_list = result.data;
        this.collection=result.total_count
      }
      else
      {
         this.status = result['status'];
        this.patient_list = [];
        this.collection = 0;
        this.start = 0;
        this.loaderService.display(false);
      }
    }, (err) => {
      console.log(err);
    });
  }
  clear_search(){
    this.search_patient='';
    this.getPatientList(0);
  }
}
