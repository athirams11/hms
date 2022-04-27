import { Component, OnInit } from '@angular/core';
import { AppointmentService } from '../../../shared'
import { routerTransition } from '../../../router.animations';
import { LoaderService } from '../../../shared'
@Component({
  selector: 'app-appointment-list',
  templateUrl: './appointment-list.component.html',
  styleUrls: ['./appointment-list.component.scss'],
  animations: [routerTransition()],
  providers:[AppointmentService]
})
export class AppointmentListComponent implements OnInit {

  appointment_list : any = [0];
  public user_rights : any ={};
  p= 50;
  k=50;
  public collection: any = '';
  page = 1;
  searching = false;
  searchFailed = false;
  public search_appoinyment:any='';
  public start: any;
  public limit: any;
  public search: any;
  public status: any;
  constructor(public rest:AppointmentService,private loaderService:LoaderService) { }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getAllAppointments();
  }
  public getAppointments()
  {
    if(this.search_appoinyment.length > 2)
    {
      this.getAllAppointments();
    }
  }
  public getAllAppointments(page=0)
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
      search_text : this.search_appoinyment,

    };
    this.loaderService.display(true);
    this.rest.getAllAppointments(postData).subscribe((result) => {
      this.loaderService.display(false);
      if(result.status == "Success")
      {
        
        this.status=result['status'];
        this.appointment_list = result.data;
        this.collection=result.total_count;
       // this.status=result.status
            }
      else
      {
        this.appointment_list = []
        this.status=result['status'];
        this.collection = 0;
        this.start = 0;
        
      }
     // console.log( "vhdhigdf"+this.appointment_list );
    }, (err) => {
      console.log(err);
    });
  }
  clear_search(){
    this.search_appoinyment='';
    this.getAllAppointments(0);
  }
}
