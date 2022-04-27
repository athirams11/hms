import { Component, OnInit, Input } from '@angular/core';
import {DatePipe} from '@angular/common';
@Component({
  selector: 'app-list-table',
  templateUrl: './list-table.component.html',
  styleUrls: ['./list-table.component.scss','../../master.component.scss']
})
export class ListTableComponent implements OnInit {
  @Input() schedule_list: any;
  public user_rights : any ={};
  constructor(public datepipe: DatePipe) { }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
  }

  public formatDate (date)
  {
    var retVal = "";
    if(date != "")
    {
      var d  =new Date(date)
      retVal =  this.datepipe.transform(d, 'dd-MM-yyyy') //.toDateString();
    }
    return  retVal;
  }
}
