import { Component, Output, EventEmitter, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { ModuleService } from '../../../shared';
import { AppSettings } from 'src/app/app.settings';
import { interval, observable, Subscription } from 'rxjs';
import * as moment from 'moment';
@Component({
    selector: 'app-sidebar',
    templateUrl: './sidebar.component.html',
    styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

    constructor(private translate: TranslateService, public router: Router, public rest: ModuleService) {
        this.translate.addLangs(['en', 'fr', 'ur', 'es', 'it', 'fa', 'de']);
        this.translate.setDefaultLang('en');
        const browserLang = this.translate.getBrowserLang();
        this.translate.use(browserLang.match(/en|fr|ur|es|it|fa|de/) ? browserLang : 'en');

        this.router.events.subscribe(val => {
            if (
                val instanceof NavigationEnd &&
                window.innerWidth <= 992 &&
                this.isToggled()
            ) {
                this.toggleSidebar();
            }
        });
        // this.subscription = this.source.subscribe(x => this.getModulesNotification());

    }
    div1Counter = 0;
    isActive: boolean;
    collapsed: boolean;
    showMenu:  string;
    public count = {};
    public subscription: Subscription;
    public setting = AppSettings;
    pushRightClass: string;
    public userCredentials = JSON.parse(localStorage.getItem('user'));
    public menulist: any = [];
    public sub;
    public menu: any = {};
    public sub_menu: any = {};
    public module_ids: any = [];
    public now = new Date() ;
    public date: any;
    public user_group =this.userCredentials.user_group;
    public user_id =this.userCredentials.user_id;
    @Output() collapsedEvent = new EventEmitter<boolean>();
    source = interval(3000);

    ngOnInit() {
        this.isActive = false;
        this.collapsed = false;
        this.showMenu = '';
        this.loadModules();
        this.pushRightClass = 'push-right';
        // this.getModulesNotification();
        this.formatDateTime(this.now);
        // this.subscription = this.source.subscribe(x => this.getModulesNotification());
    }
    ngOnDestroy() {
        // avoid memory leaks here by cleaning up after ourselves. If we  
        // don't then we will continue to run our initialiseInvites()   
        // method on every navigationEnd event.
        if (this.subscription) {  
           this.subscription.unsubscribe();
        }
      }


    loadModules() {
        const sentData = {
            user_group : this.userCredentials.user_login
        };
        this.rest.getModules(sentData).subscribe((result) => {
            window.scrollTo(0, 0);
            if (result['status'] === 'Success') {
               this.menulist = result['data'];
               this.menu = result['data'];
               localStorage.setItem('modules', JSON.stringify(result['data']));
            } else {

            }
            // MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }

    public formatDateTime (data) {
        if (this.now) {
          this.date =  moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
        }
        if (data) {
          data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
        //   this.date = data;
          return data;
        }
      }

    getModulesNotification() {
        const sentData = {
            user_group: this.user_group,
            user_id:this.user_id,
            date: this.date
        };
        this.rest.getModulesNotification(sentData).subscribe((result) => {
            if (result['status'] == 'Success') {
               this.count = result['count'];
            } else {

            }
            // MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }
    eventCalled() {
        this.isActive = !this.isActive;
    }

    addExpandClass(element: any) {
        if (element == this.showMenu) {
            this.showMenu = '0';
        } else {
            this.showMenu = element;
        }
    }

    toggleCollapsed() {
        this.collapsed = !this.collapsed;
        this.collapsedEvent.emit(this.collapsed);
    }

    isToggled(): boolean {
        const dom: Element = document.querySelector('body');
        return dom.classList.contains(this.pushRightClass);
    }

    toggleSidebar() {
        const dom: any = document.querySelector('body');
        dom.classList.toggle(this.pushRightClass);
    }

    rltAndLtr() {
        const dom: any = document.querySelector('body');
        dom.classList.toggle('rtl');
    }

    changeLang(language: string) {
        this.translate.use(language);
    }

    onLoggedout() {
        // localStorage.removeItem('isLoggedin');
        localStorage.removeItem('isLoggedin');
        localStorage.removeItem('user');
        localStorage.removeItem('modules');
        localStorage.removeItem('sub_modules');
        localStorage.removeItem('access_types');
        localStorage.removeItem('user_rights');
    }
}
