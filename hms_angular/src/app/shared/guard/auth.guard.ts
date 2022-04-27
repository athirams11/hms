import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Router } from '@angular/router';

@Injectable()
export class AuthGuard implements CanActivate {
    constructor(private router: Router) {}

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (localStorage.getItem('isLoggedin')) {
            // return true;
            const lastLog = localStorage.getItem('activeTime');
            if (lastLog !== null && lastLog !== '') {
                //console.log(lastLog);
                const last = new Date(lastLog);
                const date =  new Date();
                const diffInMs: number = Date.parse(date.toString()) - Date.parse(lastLog);
                //console.log(diffInMs / 1000 / 60 / 60);
                if (diffInMs / 1000 / 60 / 60 > 0.1) {                    
                    //window.location.reload();
                    this.router.navigate(['/login']);
                    return false;
                }
            } else {
                window.location.reload();
                this.router.navigate(['/login']);
                return false;
            }
            const path = route.data['path'] as string;
            const level = route.data['level'] as number;
            if (level > 0) {
                if (level === 1) {
                    //console.log(localStorage.getItem('modules'));

                    const modules = JSON.parse(localStorage.getItem('modules'));
                    const module_group =  modules.find(x => x.MODULE_GROUP_PATH === path);

                    if (module_group !== undefined) {

                        if (module_group.MODULE_GROUP_ACCESS === '1') {
                            // console.log(module_group);
                            localStorage.setItem('sub_modules', JSON.stringify(module_group.sub_menu));
                            return true;
                        } else {
                            this.router.navigate(['/access-denied']);
                            return false;
                        }
                    } else {
                        this.router.navigate(['/access-denied']);
                        return false;
                    }
                }
                if (level === 2) {
                    const sub_modules = JSON.parse(localStorage.getItem('sub_modules'));
                    const access_types = JSON.parse(localStorage.getItem('access_types'));
                    const module =  sub_modules.find(x => x.MODULE_PATH === path);
                    // console.log(module);
                    // console.log(access_types);
                    if (module !== undefined) {
                        let rights_array: any = [];
                        const user_rights_array: any = {};
                        rights_array = module.MODULE_ACCESS_RIGHTS.split('');
                        for (let i = 0; i < access_types.length; i++) {
                           // if (rights_array.indexOf(i) > -1) {
                                // do stuff with array
                                user_rights_array[access_types[i].USER_ACCESS_TYPE_NAME] = rights_array[i];
                           // }
                        }
                        // console.log(user_rights_array);
                        if (user_rights_array.VIEW === '1') {
                            localStorage.setItem('user_rights', JSON.stringify(user_rights_array));
                            return true;
                        } else {
                            this.router.navigate(['/access-denied']);
                            return false;
                        }
                    } else {
                        this.router.navigate(['/access-denied']);
                        return false;
                    }

                }
            } else {
                return true;
            }


        }

        this.router.navigate(['/login']);
        return false;
    }
}
