import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {DatePipe} from '@angular/common';
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';
import { routerTransition } from '../../../router.animations';
import { UserAccessService } from './../../../shared'
@Component({
  selector: 'app-user-access',
  templateUrl: './user-access.component.html',
  styleUrls: ['./user-access.component.scss'],
  animations: [routerTransition()]
})
export class UserAccessComponent implements OnInit {

  public user_groups : any = [];
  public user_rights : any = {};
  public user_access_list : any = [];
  public user_access_types : any = [];
  sel_user_group = "";
  constructor(public rest: UserAccessService) { }

  ngOnInit() {
    this.getOptions();
    //console.log(JSON.parse(localStorage.getItem('modules')));
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    //console.log(this.user_rights);
  }
  changeAccessRights(module_id,new_value,index,userrights)
  {
    var replace = 0;
    if(new_value == true)
    {
      replace = 1;
    }
    var value = userrights.substring(0, index) + replace + userrights.substring(index + 1);
   // console.log(value);
    var sendJson = {
      user_group : this.sel_user_group,
      module_id : module_id,
      value : value
    }
    
    this.rest.changeAccessRights(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
        this.getGroupAccess();
      }
      else
      {
        this.getGroupAccess();
      }
    }, (err) => {
      console.log(err);
    });
    
  }
  changeGroup(group_id,value)
  {
    var sendJson = {
      user_group : this.sel_user_group,
      group_id : group_id,
      value : value
    }
    this.rest.changeAccessGroup(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
        this.getGroupAccess();
      }
      else
      {
        this.getGroupAccess();
      }
    }, (err) => {
      console.log(err);
    });
  }
  createArray(str){
    
    var items: any = [];
    items = str.split('');
    
    return items;
  }
  getGroupAccess()
  {
    var sendJson = {
      user_group : this.sel_user_group
    }
    this.rest.getAccessrights(sendJson).subscribe((result) => {
      if(result.status == "Success")
      {
  
        //console.log(result.data);
        this.user_access_list = result.data;
       // this.dr_schedule_list_date = [];
      }
      else
      {
        this.user_access_list = [];
        //this.dr_schedule_list_date = [];
      }
    }, (err) => {
      console.log(err);
    });
  }
  getOptions()
  {
    this.rest.getOptions().subscribe((data: {}) => {
      if(data['status'] == 'Success')
      {
        //console.log(data);
        if(data['user_groups']["status"] == 'Success')
        {
          this.user_groups = data['user_groups']['data'];
        }
        if(data['user_access_types']["status"] == 'Success')
        {
          this.user_access_types = data['user_access_types']['data'];
        }
      }
      
    });
  }
}
