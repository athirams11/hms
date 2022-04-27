import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import { ModuleService, LoginService } from '../../../shared';
import { NotifierService } from 'angular-notifier';
import { UserIdleService } from 'angular-user-idle';
@Component({
    selector: 'app-header',
    templateUrl: './header.component.html',
    styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {
    public error_message = "";
    public pushRightClass: string;
    public userCredentials = JSON.parse(localStorage.getItem('user'));
    public institution = JSON.parse(localStorage.getItem('institution'));
    public user_id = this.userCredentials.user_id;
    public current_password: string;
    public new_password: string;
    public confirm_password: string;
    // modalService: any;
    closeResult: string;
    private notifier: NotifierService;


    constructor(private modalService: NgbModal, private translate: TranslateService, public router: Router, public rest1: ModuleService, notifierService: NotifierService, public rest: LoginService, private userIdle: UserIdleService) {
        this.notifier = notifierService;
        this.translate.addLangs(['en', 'fr', 'ur', 'es', 'it', 'fa', 'de', 'zh-CHS']);
        this.translate.setDefaultLang('en');
        const browserLang = this.translate.getBrowserLang();
        this.translate.use(browserLang.match(/en|fr|ur|es|it|fa|de|zh-CHS/) ? browserLang : 'en');

        this.router.events.subscribe(val => {
            if (
                val instanceof NavigationEnd &&
                window.innerWidth <= 992 &&
                this.isToggled()
            ) {
                this.toggleSidebar();
            }
        });
    }

    ngOnInit() {
        this.pushRightClass = 'push-right';
        this.institution = JSON.parse(localStorage.getItem('institution'));
        //  console.log("userr"+JSON.stringify(this.userCredentials));
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

    onLoggedout() {
        localStorage.removeItem('isLoggedin');
        localStorage.removeItem('user');
        localStorage.removeItem('modules');
        localStorage.removeItem('sub_modules');
        localStorage.removeItem('access_types');
        localStorage.removeItem('user_rights');
        localStorage.removeItem('institution');
    }

    changeLang(language: string) {
        this.translate.use(language);
    }
    public open(content) {
        this.modalService.open(content, { ariaLabelledBy: 'modal-basic-title', windowClass: "col-md-12", centered: true }).result.then((result) => {
            this.closeResult = `Closed with: ${result}`;
        }, (reason) => {
            this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
        });
    }

    private getDismissReason(reason: any): string {
        if (reason === ModalDismissReasons.ESC) {
            return 'by pressing ESC';
        } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
            return 'by clicking on a backdrop';
        } else {
            return `with: ${reason}`;
        }
    }

    changePassword() {
        const sentData = {
            user_id: this.user_id,
            current_password: this.current_password,
            new_password: this.new_password,
            confirm_password: this.confirm_password
        };
        this.rest1.changePassword(sentData).subscribe((result) => {
            if (result['status'] == 'Success') {
                this.notifier.notify('success', result.message);
                // this.onLoggedout();
                this.onLoggedin();
            }
            else {
                this.notifier.notify('error', result.message);
            }
            // MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }
    getAccessTypes() {
        this.rest.getAccessTypes().subscribe((data: {}) => {
            if (data['status'] == 'Success') {
                //console.log(data['user_access_types']['data']);

                if (data['user_access_types']["status"] == 'Success') {
                    localStorage.setItem('access_types', JSON.stringify(data['user_access_types']['data']));
                }
            }

        });
    }
    getInstitution() {
        this.rest.getInstitution().subscribe((data: {}) => {
            if (data["status"] == 'Success') {
                // console.log(data['logo_path']);
                // console.log(data);
                localStorage.setItem('institution', JSON.stringify(data['data']));
                localStorage.setItem('logo_path', JSON.stringify(data['logo_path']));

            }
        });
    }
    onLoggedin() {
        var login_data = {
            log_user: this.userCredentials.user,
            log_pass: this.new_password
        };
        this.rest.checkCredentials(login_data).subscribe((result) => {
            window.scrollTo(0, 0)
            if (result["status"] == "Success") {
                localStorage.setItem('isLoggedin', 'true');
                var date = new Date();
                localStorage.setItem('activeTime', date.toString());
                localStorage.setItem('user', JSON.stringify(result["data"]));
                this.getAccessTypes();
                this.getInstitution();
                this.userIdle.startWatching();
                // Start watching when user idle is starting.
                this.userIdle.onTimerStart().subscribe(count => console.log(count));
                // Start watch when time is up.
                this.userIdle.ping$.subscribe(() => {

                    var date = new Date();

                    localStorage.setItem('activeTime', date.toString());
                });
                this.userIdle.onTimeout().subscribe(() => {

                    localStorage.removeItem('isLoggedin');
                    localStorage.removeItem('user');
                    localStorage.removeItem('modules');
                    localStorage.removeItem('sub_modules');
                    localStorage.removeItem('access_types');
                    localStorage.removeItem('user_rights');
                    //localStorage.removeItem('institution');
                    localStorage.removeItem('logo_path');
                });

                var path: String = result["data"]["redirect_page"].toString();
                // console.log("path"+path);
                this.loadModules(path);
                //localStorage.setItem('first_name', JSON.stringify(result["data"]["first_name"]));
                // localStorage.setItem('last_name', JSON.stringify(result["data"]["last_name"]));
                //localStorage.setItem('user_id', JSON.stringify(result["data"]["user_id"]));
                //localStorage.setItem('user_email', JSON.stringify(result["data"]["user_email"]));
                // localStorage.setItem('user_login', JSON.stringify(result["data"]["user_login"]));

            }
            else {
                this.error_message = result["message"];
                setTimeout(() => {

                    this.error_message = "";

                }, 4000);
            }
            //MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }

    public loadModules(path: String) {
        var userCredentials = JSON.parse(localStorage.getItem('user'));
        var sentData = {
            user_group: userCredentials.user_login
        }
        this.rest1.getModules(sentData).subscribe((result) => {
            window.scrollTo(0, 0)
            if (result["status"] == "Success") {
                //this.menulist = result["data"];
                localStorage.setItem('modules', JSON.stringify(result["data"]));
                this.router.navigate([path]);
            }
            else {

            }
            //MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }
}
