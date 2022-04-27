import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
@Injectable({
  providedIn: 'root'
})
export class UserAccessService {
  constructor(private http: HttpClient) {
    
    //console.log(this.user_rights);
   }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getOptions(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'UserAccess/options',null).pipe(
      map(this.extractData));
  }
  getAccessrights(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserAccess/getAccessData', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getAccessrights'))
     );
  }
  changeAccessGroup(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserAccess/changeAccessGroup', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getAccessrights'))
     );
  }
  changeAccessRights(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserAccess/changeAccessRights', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getAccessrights'))
     );
  }
  private handleError<T> (operation = 'operation', result?: T) {
    return (error: any): Observable<T> => {
  
      // TODO: send the error to remote logging infrastructure
      console.error(error); // log to console instead
  
      // TODO: better job of transforming error for user consumption
      console.log(`${operation} failed: ${error.message}`);
  
      // Let the app keep running by returning an empty result.
      return of(result as T);
    };
  }
}
