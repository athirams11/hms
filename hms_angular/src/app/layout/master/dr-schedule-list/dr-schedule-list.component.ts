import { Component, OnInit } from '@angular/core';
import {DatePipe} from '@angular/common';
import * as moment from 'moment';
import { routerTransition } from '../../../router.animations';
import { DrConsultationService } from '../../../shared';
import { LoaderService } from '../../../shared';
@Component({
  selector: 'app-dr-schedule-list',
  templateUrl: './dr-schedule-list.component.html',
  styleUrls: ['./dr-schedule-list.component.scss','../master.component.scss'],
  animations: [routerTransition()],
  providers: [DrConsultationService]
})
export class DrScheduleListComponent implements OnInit {
  public schedule_list : any = [];
  constructor(private loaderService:LoaderService ,public rest:DrConsultationService, public datepipe: DatePipe) { }
  public user_rights : any ={};
  ngOnInit() {
    this.getScheduleList();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
  }
  public getScheduleList()
  {
    this.loaderService.display(true);
    this.rest.getScheduleList().subscribe((result) => {
      if(result.status == "Success")
      {
        this.loaderService.display(false);
        this.schedule_list = result.data;
      }
      else
      {
        this.loaderService.display(false);
        this.schedule_list = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
}
