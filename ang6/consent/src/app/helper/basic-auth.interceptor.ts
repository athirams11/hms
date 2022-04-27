import { Injectable } from '@angular/core';
import { HttpRequest, HttpHandler, HttpEvent, HttpInterceptor } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable()
export class BasicAuthInterceptor implements HttpInterceptor {
    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        // add authorization header with basic auth credentials if available
        let currentUser = JSON.parse(localStorage.getItem('user'));
       // console.log("Intercepted : "+request.url);
        if (currentUser && currentUser.api_key) {
            var authdata = window.btoa(currentUser.user_id+":"+currentUser.api_key);
            request = request.clone({
                setHeaders: { 
                    "HMS-KEY": `5dd26ca2624be`,
                    Authorization: `Basic ${authdata}`
                }
            });
        }
        else
        {
            request = request.clone({
                setHeaders: { 
                    "HMS-KEY": `5dd26ca2624be`
                }
            });
        }

        return next.handle(request);
    }
}