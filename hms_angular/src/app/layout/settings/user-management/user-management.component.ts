import { Component, OnInit } from '@angular/core'
import { routerTransition } from '../../../router.animations';
import { UserManagementService, DoctorsService } from './../../../shared';
import { LoaderService } from '../../../shared'
import { NotifierService } from 'angular-notifier';
import * as moment from 'moment';
@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss'],
  animations: [routerTransition()]
})
export class UserManagementComponent implements OnInit {
  private notifier: NotifierService;
  public user_groups: any = [];
  public userlist: any = [];
  public doc_list: any = [];
  public user_rights: any = {};
  public drFlag = 0;
  public now = new Date();
  public date: any;
  drValue: any;
  drvalue = 4;
  sel_user_group = '';
  public formData = {
    firstname: '',
    lastname: '',
    username: '',
    password: '',
    useraccesstype: '',
    user_id: '',
    email: '',
    doctor_id: '',
    date: '',
    status: 1,
    department_id: 0
  };
  success_message = '';
  error_message = '';
  departments: any;
  constructor(public rest: UserManagementService, notifierService: NotifierService, public rest2: DoctorsService, private loaderService: LoaderService) {
    this.notifier = notifierService;

  }

  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.getOptions();
    this.getUserList();
    this.getDoctorList();
    this.formatDateTime();
  }
  editUser(user) {
    window.scrollTo(0, 0);
    console.log(user)
    this.formData.firstname = user.FIRSTNAME;
    this.formData.lastname = user.LASTNAME;
    this.formData.email = user.EMAIL;
    this.formData.username = user.USERNAME;
    this.formData.user_id = user.USER_SPK;
    this.formData.useraccesstype = user.USER_ACCESS_TYPE;
    this.formData.department_id = user.DEPARTMENT_ID;
    this.formData.doctor_id = user.DOCTORS_ID;
    this.formData.status = user.STATUS;
    if (user.USER_ACCESS_TYPE === '4') {
      this.drFlag = 1;
    } else {
      this.drFlag = 0;
    }
  }
  updateUserData() {
    if (this.formData.firstname == '') {
      this.notifier.notify('error', 'Please enter first name');
      return false;
    } else if (this.formData.lastname == '') {
      this.notifier.notify('error', 'Please enter last name');
      return false;
    } else if (this.formData.email == '') {
      this.notifier.notify('error', 'Please enter email address');
      return false;
    } else if (this.formData.useraccesstype == '') {
      this.notifier.notify('error', 'Please enter group');
      return false;
    } else if (this.formData.useraccesstype == '') {
      this.notifier.notify('error', 'Please enter group');
      return false;
    } else if (this.formData.useraccesstype == '8' && (this.formData.department_id == 0 || this.formData.department_id < 0)) {
      this.notifier.notify('error', 'Please select the department of the cashier');
      return false;
    } else if (this.drFlag == 1 && (this.formData.doctor_id == '' || this.formData.doctor_id == '0' || this.formData.doctor_id == null)) {
      this.notifier.notify('error', 'Please select the doctor');
      return false;
    } else if (this.formData.username == '') {
      this.notifier.notify('error', 'Please enter user name');
      return false;
    } else if (this.formData.user_id == '' && this.formData.password == '') {
      this.notifier.notify('error', 'Please enter your password');
      return false;
    } else {
      // this.formData.department_id = this.formData.useraccesstype != '8' ?  0 : this.formData.department_id
      this.loaderService.display(true);
      this.rest.addNewUser(this.formData).subscribe((result) => {
        this.loaderService.display(false);
        window.scrollTo(0, 0);
        if (result['status'] === 'Success') {

          this.getUserList();
          this.notifier.notify('success', result["message"], result.response);
          this.formData = {
            firstname: '',
            lastname: '',
            username: '',
            password: '',
            useraccesstype: '',
            user_id: '',
            email: '',
            doctor_id: '',
            date: '',
            status: 1,
            department_id: 0
          };
          this.drFlag = 0;

        } else {
          this.notifier.notify('error', result["message"]);
        }
      }, (err) => {
        console.log(err);
      });
    }
  }
  getUserList() {
    this.loaderService.display(true);
    this.rest.getUserList().subscribe((data: {}) => {
      this.loaderService.display(false);
      if (data['status'] === 'Success') {
        this.userlist = data['data'];
      }

    });
  }
  getOptions() {
    this.rest.getOptions().subscribe((data: {}) => {
      if (data['status'] === 'Success') {
        // console.log(data);
        if (data['user_groups']['status'] === 'Success') {
          this.user_groups = data['user_groups']['data'];
        }
        if (data['departments']['status'] === 'Success') {
          this.departments = data['departments']['data'];
        }
      }

    });
  }
  public selectDr(data) {
    this.drValue = data;

    if (this.drValue === '4') {
      this.drFlag = 1;
      // console.log(' this.drFlag ' +  new Date());
    } else {
      this.drFlag = 0;
    }

  }
  public getDoctorList() {
    // this.loaderService.display(true);
    var postData = {};
    this.rest2.getDoctorList(postData).subscribe((response: {}) => {
      if (response['status'] === 'Success') {
        this.doc_list = response['data'];
        // this.loaderService.display(false);

      }
      //  this.loaderService.display(false);
    });
  }

  public formatDateTime() {

    const retVal = '';
    if (this.now) {
      this.formData.date = moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
      //console.log('date   ' + this.date);
    }
    return retVal;


  }
}
