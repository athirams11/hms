import { Component, OnInit } from '@angular/core';
import { routerTransition } from '../../../router.animations';
import { Router } from '@angular/router';
import {DatePipe} from '@angular/common';
import {NgbModal, ModalDismissReasons} from '@ng-bootstrap/ng-bootstrap';
import {DoctorsService,UserManagementService} from './../../../shared';
import {formatDate} from './../../../shared/class/Utils';
import { NotifierService } from 'angular-notifier';
import { LoaderService } from '../../../shared';
import * as EmailValidator from 'email-validator';

@Component({
  selector: 'app-institution',
  templateUrl: './institution.component.html',
  styleUrls: ['./institution.component.scss'],
  animations: [routerTransition()]

})

export class InstitutionComponent implements OnInit {
  private notifier: NotifierService;
  public user_groups: any = [];
  public userlist: any = [];
  public doc_list: any = [];
  public user_rights: any = {};
  public logo : any;
  public country_options:any=[''];
  public countries: any = [];
  public user_data: any;
  public hospital_list:any=[];
  public country_data:any=[];
  public index;
  public image:any = '';
  public hospital_data={
    hospital_name:'',
    hospital_address:'',
    hospital_phone:'',
    hospital_city:'',
    hospital_country:'',
    hospital_email:'',
    hospital_logo:'',
    dhpo_name:'',
    dhpo_id:'',
    dhpo_password:'',
    hospital_id:'',
    user_id:0,
    custom_date_status:false,
    custom_date:''

  }
  status: any;
  splitted: any;
  fileName: any;
  constructor(private loaderService: LoaderService,public rest: UserManagementService, notifierService: NotifierService, public rest2: DoctorsService) {
    this.notifier = notifierService;

  }
  ngOnInit() {
    this.getHospitalOptions();
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user_data = JSON.parse(localStorage.getItem('user'));
    this.hospital_data.user_id=this.user_data.user_id;
  }
  getFileDetails($event) {
    let file = $event.target.files[0];
    this.readThis($event.target);
      
  }
  readThis(inputValue: any): void {
    var file:File = inputValue.files[0];
    var myReader:FileReader = new FileReader();
  
    myReader.onloadend = (e) => {
      this.logo = myReader.result;
      this.splitted = this.logo.split(",");
      this.hospital_data.hospital_logo =this.splitted[1]
    }
    myReader.readAsDataURL(file);
  }

  public getHospitalOptions() {
      this.countries = [];
      window.scrollTo(0, 0);
      this.loaderService.display(true);
      this.rest.getHospitalOptions().subscribe((result: {}) => {
        this.status = result['status'];
      if (result['status'] === 'Success') {
        this.countries = result['country']['data'];
        this.country_options = this.countries;
        this.listInstitution();
          this.loaderService.display(false);
      } else {
          this.loaderService.display(false);
        
      }
    });
  }
  public listInstitution() {
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest.listInstitution().subscribe((result: {}) => {
      this.status = result['status'];
    if (result['status'] === 'Success') {
      this.loaderService.display(false);
      this.hospital_list=result['data'];
      this.hospital_data.hospital_id=this.hospital_list.INSTITUTION_SETTINGS_ID;
      this.hospital_data.dhpo_name = this.hospital_list.INSTITUTION_DHPO_LOGIN;
      this.hospital_data.hospital_phone = this.hospital_list.INSTITUTION_PHONE_NO;
      this.hospital_data.hospital_name = this.hospital_list.INSTITUTION_NAME;
      this.hospital_data.dhpo_password =this.hospital_list.INSTITUTION_DHPO_PASS;
      this.hospital_data.dhpo_id = this.hospital_list.INSTITUTION_DHPO_ID;
      this.hospital_data.hospital_address= this.hospital_list.INSTITUTION_ADDRESS;
      this.hospital_data.hospital_city= this.hospital_list.INSTITUTION_CITY;
      this.hospital_data.hospital_email= this.hospital_list.INSTITUTION_EMAIL;
      this.hospital_data.custom_date_status = this.hospital_list.CUSTOM_DATE_STATUS==1 ? true : false;
      if(this.hospital_list.CUSTOM_DATE != '' && this.hospital_list.CUSTOM_DATE != null && this.hospital_list.CUSTOM_DATE != '0000-00-00')
      this.hospital_data.custom_date= this.hospital_list.CUSTOM_DATE;
      if(this.hospital_list.INSTITUTION_LOGO)
      {
        this.image = result['logo_path']+ this.hospital_list.INSTITUTION_LOGO;
      }
      else
      {
        this.image = ''
      }
      this.fileName = this.hospital_list.INSTITUTION_LOGO;
      this.hospital_data.hospital_country = this.hospital_list.INSTITUTION_COUNTRY;
      this.setCountry();
      localStorage.setItem('institution', JSON.stringify(this.hospital_list));
      localStorage.setItem('logo_path', JSON.stringify(result['logo_path']));
    } 
    
  });
}
public removeLogo()
{
  this.hospital_data.hospital_logo = '1'
  this.fileName = ''
  this.image = ''
  // this.notifier.notify("error","Company logo removed")
}
  public setCountry(){
    if ( this.hospital_data.hospital_country) {
      for (let index = 0; index < this.countries.length; index++) {
        if (this.countries[index].COUNTRY_ID == this.hospital_data.hospital_country ) {
          this.country_data = this.countries[index];
          break;
        }
      }
    }
  }
  public saveDate()
  {
    if (this.hospital_data.custom_date_status == true) {
      if (this.hospital_data.custom_date == '' || this.hospital_data.custom_date == null) {
        this.notifier.notify( 'error', 'Please custom date!' );
        return false;
      }
    }
    var postdata={
			hospital_id:this.hospital_data.hospital_id,
      user_id: this.user_data.user_id,
      custom_date: this.hospital_data.custom_date,
      custom_date_status: this.hospital_data.custom_date_status
      
    }
    
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest.saveCustomDate(postdata).subscribe((result: {}) => {
      this.status = result['status'];
      if (result['status'] === 'Success') {
        if(this.hospital_data.custom_date_status == true)
        {
          localStorage.setItem('dafault_date', formatDate(this.hospital_data.custom_date));
        }
        this.notifier.notify( 'success', "Custom date saved successfully..!")
          this.loaderService.display(false);
          this.listInstitution();
      } else {
          this.loaderService.display(false);
      }
    }); 
  }
  public saveInstitution() {
    if(this.hospital_data.hospital_name == '')
    {
      this.notifier.notify("error","Please enter Institution name")
      return false;
    }
    else if(this.hospital_data.hospital_address == '')
    {
      this.notifier.notify("error","Please enter Institution address")
      return false;
    }
    else if(this.hospital_data.hospital_country == '')
    {
      this.notifier.notify("error","Please enter Institution country")
      return false;
    }
    else if(this.hospital_data.hospital_phone == '')
    {
      this.notifier.notify("error","Please enter Institution phone number")
      return false;
    }
    else if(this.hospital_data.hospital_email == '')
    {
      this.notifier.notify("error","Please enter Institution email address")
      return false;
    }
    var postdata={
      hospital_name:this.hospital_data.hospital_name,
      hospital_address:this.hospital_data.hospital_address,
      hospital_phone:this.hospital_data.hospital_phone,
      hospital_city:this.hospital_data.hospital_city,
      hospital_country:this.hospital_data.hospital_country,
      hospital_logo:this.hospital_data.hospital_logo,
      dhpo_name:this.hospital_data.dhpo_name,
      dhpo_id:this.hospital_data.dhpo_id,
      dhpo_password:this.hospital_data.dhpo_password,
			hospital_id:this.hospital_data.hospital_id,
      hospital_email:this.hospital_data.hospital_email,
      user_id: this.user_data.user_id,
      
      
    }
    
    window.scrollTo(0, 0);
    this.loaderService.display(true);
    this.rest.saveInstitution(postdata).subscribe((result: {}) => {
      this.status = result['status'];
    if (result['status'] === 'Success') {
      this.notifier.notify( 'success',"Institution details saved successfully..!")
        this.loaderService.display(false);
        this.listInstitution();
    } else {
        this.loaderService.display(false);
    }
  });

}
  configer = {
    displayKey:"COUNTRY_NAME", //if objects array passed which key to be displayed defaults to description
    search:true, //true/false for the search functionlity defaults to false,
    height: 'auto', //height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
    placeholder:'Select Country', // text to be displayed when no item is selected defaults to Select,
   // customComparator: ()=>{}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
    limitTo:239 , // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
    moreText: '.........', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
    noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
    searchPlaceholder:'Search' ,// label thats displayed in search input,
    searchOnKey: 'COUNTRY_NAME' // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
    }
    getcountry($event){
      if(this.country_data)
        this. hospital_data.hospital_country= this.country_data.COUNTRY_ID;
      else
      this.country_data = []

    }
}
