import { Component, OnInit, Input } from '@angular/core';
import {DatePipe} from '@angular/common';
@Component({
  selector: 'app-list-table',
  templateUrl: './list-table.component.html',
  styleUrls: ['./list-table.component.scss']
})
export class ListTableComponent implements OnInit {
  @Input() patient_list: any = [];
  @Input() start: number;
  @Input() start_appo: number;
  @Input() appointment_list: any = [];
  @Input() data: any ;
  public user_rights : any = {};
  p = 50;
  public collection: any = '';
  page = 1;
  searching = false;
  searchFailed = false;
  public search_doctor:'';
  public limit: any;
  public search: any;
  public status: any;
  constructor(public datepipe: DatePipe) { }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
  }
  public formatGender (gender)
  {
    var retVal = "";
    if(gender != "" && gender != null )
    {
     if(gender == 1)
     {
      retVal =  "Male" //.toDateString();
     }
     else if(gender == 0){
      retVal =  "Female" //.toDateString();
     }
      
    }
    return  retVal;
  }
  public formatDate (date)
  {
    var retVal = "";
    if(date != "" && date != null && date != '0000-00-00')
    {
      var d  =new Date(date)
      retVal =  this.datepipe.transform(d, 'dd-MM-yyyy') //.toDateString();
    }
    return  retVal;
  }
}
