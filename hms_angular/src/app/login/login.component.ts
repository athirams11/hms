import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';
import { routerTransition } from '../router.animations';
import { LoginService, AuthGuard } from '../shared';
import { formatTime, formatDateTime, formatDate } from '../shared/class/Utils';
import { ModuleService } from '../shared'
import { UserIdleService } from 'angular-user-idle';
@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss'],
    animations: [routerTransition()]
})
export class LoginComponent implements OnInit {
    public error_message = "";
    public user = "";
    public pass = "";
    constructor(
        private translate: TranslateService,
        public router: Router,
        public rest: LoginService,
        public modules: ModuleService,
        private userIdle: UserIdleService
        ) {
            this.translate.addLangs(['en', 'fr', 'ur', 'es', 'it', 'fa', 'de', 'zh-CHS']);
            this.translate.setDefaultLang('en');
            const browserLang = this.translate.getBrowserLang();
            this.translate.use(browserLang.match(/en|fr|ur|es|it|fa|de|zh-CHS/) ? browserLang : 'en');
    }

    ngOnInit() {}
    getAccessTypes()
    {
        this.rest.getAccessTypes().subscribe((data: {}) => {
        if(data['status'] == 'Success')
        {
            //console.log(data['user_access_types']['data']);
            
            if(data['user_access_types']["status"] == 'Success')
            {
                localStorage.setItem('access_types', JSON.stringify(data['user_access_types']['data']));
            }
        }
        
        });
    }
    getInstitution()
    {
        this.rest.getInstitution().subscribe((data: {}) => {
            if(data["status"] == 'Success')
            {
               // console.log(data['logo_path']);
               // console.log(data);
                localStorage.setItem('institution', JSON.stringify(data['data']));
                localStorage.setItem('logo_path', JSON.stringify(data['logo_path']));

            }
        });
    }
    onLoggedin() {
        //localStorage.setItem('isLoggedin', 'true');
        if(this.user == "")
        {
            this.error_message = "<strong>Invalid </strong> User";
            setTimeout(() => {
                
                this.error_message = "";
            
            }, 4000);
        }
        else if(this.pass == "")
        {
            this.error_message = "<strong>Invalid </strong> Password";
            setTimeout(() => {
                
                this.error_message = "";
            
            }, 4000);
        }
        else
        {
          
            var login_data = {
                log_user : this.user,
                log_pass : this.pass
            };
            this.rest.checkCredentials(login_data).subscribe((result) => {
                window.scrollTo(0, 0)
                if(result["status"] == "Success")
                {
                    localStorage.setItem('isLoggedin', 'true');
                    var date=  new Date();
                    localStorage.setItem('activeTime', date.toString());
                    var default_date = result["data"].default_date;
                    // if(result["data"].default_date != '' && result["data"].default_date != null && result["data"].default_date != '0000-00-00'){
                    //     result["data"]["default_date"] = result["data"].default_date);
                    //     default_date = result["data"].default_date;
                    //     console.log("default_date");
                    //     console.log(default_date);
                    // }
                    localStorage.setItem('default_date', default_date);
                    localStorage.setItem('user', JSON.stringify(result["data"]));
                    localStorage.setItem('doctor_department', JSON.stringify(result["doctor_department"]));

                    this.getAccessTypes();
                    this.getInstitution();
                    this.userIdle.startWatching();
                    // Start watching when user idle is starting.
                    this.userIdle.onTimerStart().subscribe();
                    // Start watch when time is up.
                    this.userIdle.ping$.subscribe(() => {
                      
                        var date=  new Date();
                       
                        localStorage.setItem('activeTime', date.toString());
                    });
                    this.userIdle.onTimeout().subscribe(() => {
                       
                        localStorage.removeItem('isLoggedin');
                        localStorage.removeItem('user');
                        localStorage.removeItem('modules');
                        localStorage.removeItem('sub_modules');
                        localStorage.removeItem('access_types');
                        localStorage.removeItem('user_rights');
                        localStorage.removeItem('institution');
                        localStorage.removeItem('logo_path');
                        localStorage.removeItem('doctor_department');
                        this.router.navigate(['login']);
                    });
                    
                    var path :String = result["data"]["redirect_page"].toString();
                    //console.log(path);
                    this.loadModules(path);
                    //localStorage.setItem('first_name', JSON.stringify(result["data"]["first_name"]));
                   // localStorage.setItem('last_name', JSON.stringify(result["data"]["last_name"]));
                    //localStorage.setItem('user_id', JSON.stringify(result["data"]["user_id"]));
                    //localStorage.setItem('user_email', JSON.stringify(result["data"]["user_email"]));
                   // localStorage.setItem('user_login', JSON.stringify(result["data"]["user_login"]));
                    
                }
                else
                {
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
        
    }
    public loadModules(path : String)
    {
        var userCredentials=JSON.parse(localStorage.getItem('user'));
        var sentData = {
            user_group : userCredentials.user_login
        }
        this.modules.getModules(sentData).subscribe((result) => {
            window.scrollTo(0, 0)
            if(result["status"] == "Success")
            {
               //this.menulist = result["data"];
               localStorage.setItem('modules', JSON.stringify(result["data"]));
               this.router.navigate([path]);
            }
            else
            {
               
            }
            //MessageBox.show(this.dialog, `Hello, World!`);
        }, (err) => {
            console.log(err);
        });
    }
}
