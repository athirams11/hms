import { Component, OnInit, Input } from '@angular/core';
import { NotifierService } from 'angular-notifier';
import { LoaderService, OpRegistrationService } from '../../../shared';
import { ConsultingService } from 'src/app/shared/services/consulting.service';
import {DatePipe, CommonModule } from '@angular/common';
import * as moment from 'moment';
@Component({
  selector: 'app-ins-network',
  templateUrl: './ins-network.component.html',
  styleUrls: ['./ins-network.component.scss','../master.component.scss']
})
export class InsNetworkComponent implements OnInit {
  @Input() assessment_id = 0;
  @Input() patient_id = 0;
  public now = new Date(); public date: any;
  public appdata = {
   tpa_id: 0,
   network_code: '',
   network_id: '',
   network_name: '',
   network_status: 1,
   network_classification: '',
   search:'',
   copy_from_network : ''
  };
  public search:any='';
  user_rights: any;
  user: any;
  notifier: NotifierService;
  public p = 50;
  public collection: any = '';
  public network_list: any = [];
  public get_network: any = [];
  public status: string;
  page = 1;
  start: number;
  showselect : false;
  limit: number;
  public tpa_receiver: any = [];
  public tpa_data:any=[];
  public tpa_options:any=[''];
  networks: any = [];
  net: any;
  constructor(private loaderService: LoaderService , public notifierService: NotifierService, public rest: ConsultingService, public rest1:OpRegistrationService) {
    this.notifier = notifierService;
   }


  ngOnInit() {
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
    this.user = JSON.parse(localStorage.getItem('user'));
    this.getNetwork();
    this. getDropdowns();
    this.formatDateTime ();
  }
    public selectStatus(val) {
    this.appdata.network_status = val;
  }
  public save_network() {
    if (this. appdata.tpa_id === 0) {
      this.notifier.notify( 'error', 'Please Enter TPA!' );
    } else if (this. appdata.network_code === '') {
      this.notifier.notify( 'error', 'Please Enter network code!' );
    } else if (this. appdata.network_name === '') {
      this.notifier.notify( 'error', 'Please Enter network name' );
    } else if (this. appdata.network_classification === '') {
      this.notifier.notify( 'error', 'Please Enter classification!' );
    } else {
    const postData = {
      ins_network_id :this.appdata.network_id,
      user_id : this.user.user_id,
      tpa_id : this.appdata.tpa_id,
      ins_network_code : this.appdata.network_code,
      ins_network_name : this.appdata.network_name,
      ins_network_classification : this.appdata.network_classification,
      ins_network_status: this.appdata.network_status,
      client_date: this.date,
      copy_from_network : this.appdata.copy_from_network

      };
     // console.log("ewf"+JSON.stringify(postData));
      this.loaderService.display(true);
      this.rest.saveNetwork(postData).subscribe((result) => {
        if (result['status'] === 'Success') {
          this.loaderService.display(false);
          this.appdata.network_id = result.data_id;
          this.notifier.notify( 'success' ,'Insurance network details saved successfully...!' );
          this.getNetwork();
          this.clearForm();
        } else {
          this.loaderService.display(false);
          this.notifier.notify( 'error', result['msg']);
        }
      });
    }
  }
  public getNetworks(data) {
    
    this.networks= []
    const opdata = {
      tpa_id : this.tpa_data.TPA_ID
    };
    this.loaderService.display(true);
    this.rest1.getOpNetworks(opdata).subscribe((result) => {
      this.loaderService.display(false);
      if (result['status'] === 'Success') {
      this.networks = result['data'];
      }
      else
      {
        this.networks = []
      }

    });
  }
    public getNetwork(page= 0) {

      const limit = 50;
      const starting = page * limit;
      this.start = starting;
      this.limit = limit;
      const postData = {

        start: this.start,
        limit : this.limit,

      };
      this.loaderService.display(true);
     this.rest.getNetworklist(postData).subscribe((result: {}) => {

       if (result['status'] === 'Success') {
        this.loaderService.display(false);
          this.network_list = result['data'];
          this.collection = result['total_count'];
       }
       this.loaderService.display(false);
     });
    }

    public getSearchlist(page= 0) {
      //if(this.search.length > 2){
        const limit = 100;
        this.start = 0;
        this.limit = limit;
        const postData = {
          start: this.start,
          limit : this.limit,
          search_text: this.search
        };
        this.loaderService.display(true);
        this.rest.getNetworklist(postData).subscribe((result: {}) => {
          this.status = result['status'];
          if (result['status'] === 'Success') {
            this.loaderService.display(false);
            this.network_list = result['data'];
              // console.log('this.Inspayer_list  ' + this.Inspayer_list);
            /////  this.setDropdown();
              // this.collection = result['total_count'];
          }
          this.loaderService.display(false);
        });
     // }
    }

    public editNetwork(data) {
      const post2Data = {
        ins_network_id: data.INS_NETWORK_ID
      };

      this.loaderService.display(true);
      this.rest.getNetwork(post2Data).subscribe((result: {}) => {
        if (result['status'] === 'Success') {
          this.net = result['status']
         this.loaderService.display(false);
         this.get_network = result['data'];
        
        // this.appdata.tpa_id = this.get_network.TPA_ID;
          this.tpa_data = this.get_network.TPA_NAME;
         
         this.appdata.network_id = this.get_network.INS_NETWORK_ID;
         this.appdata.network_code = this.get_network.INS_NETWORK_CODE;
         this.appdata.network_name = this.get_network.INS_NETWORK_NAME;
         this.appdata.network_classification = this.get_network.INS_NETWORK_CLASSIFICATION;
         this.appdata.network_status = this.get_network.INS_NETWORK_STATUS;
         this.setDropdown();
         this.appdata.tpa_id =  this.get_network.TPA_ID;
        }
        
        this.loaderService.display(false);
      });

      window.scrollTo(0, 0);
    }


  public clearForm() {
    this.appdata = {
      tpa_id: 0,
      network_code: '',
      network_id: '',
      network_name: '',
      network_status: 1,
      search: '',
      network_classification: '',
      copy_from_network : ''
     };
     this.tpa_data = [''];
  }
  public clear_search() {
    if( this.search != '')
    {
      this.search = '' ;
      this.getNetwork();
      this.editNetwork(0);
     }
}
public formatDateTime () {
    if (this.now ) {
      this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y h:mm a');
    }
  }
  public setDropdown(){
    if ( this.appdata.tpa_id) {
    for (let index = 0; index < this.tpa_options.length; index++) {
      if (this.tpa_options[index].OP_INS_TPA == this.appdata.tpa_id) {
        this.tpa_data = this.tpa_options[index];
        break;
      }
    }
  }
}
  public getDropdowns() {
    this.loaderService.display(true);
    this.rest1.getOpDropdowns().subscribe((data: {}) => {
      this.loaderService.display(false);
        if (data['tpa_receiver']['status'] === 'Success') {
          this.tpa_receiver = data['tpa_receiver']['data'];
          this.tpa_options=this.tpa_receiver;
          for (let index = 0; index < this.tpa_options.length; index++) {
            if (this.tpa_options[index].TPA_ID == this.appdata.tpa_id) {
              this.tpa_data = this.tpa_options[index];
            }
          }
        }

    });
  }
  getTpa($event){
    // console.log("this.country_data  "+JSON.stringify(this.country_data));
    this.appdata.tpa_id = this.tpa_data.TPA_ID;
   }

   config = {
    displayKey:"TPA", //if objects array passed which key to be displayed defaults to description
    search:true, //true/false for the search functionlity defaults to false,
    height: '350px', //height of the list so that if there are more no of items it can show a scroll defaults to auto. With auto height scroll will never appear
    placeholder:'Select TPA / Receiver', // text to be displayed when no item is selected defaults to Select,
   // customComparator: ()=>{}, // a custom function using which user wants to sort the items. default is undefined and Array.sort() will be used in that case,
    limitTo: 100, // a number thats limits the no of options displayed in the UI similar to angular's limitTo pipe
    moreText: '.........', // text to be displayed whenmore than one items are selected like Option 1 + 5 more
    noResultsFound: 'No results found!', // text to be displayed when no items are found while searching
    searchPlaceholder:'Search' ,// label thats displayed in search input,
    searchOnKey: 'TPA' // key on which search should be performed this will be selective search. if undefined this will be extensive search on all keys
    }
    getlength($event){
      if (this.search.length > 2) {
        this.getSearchlist($event);
      }
    }
}
