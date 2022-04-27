import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';

@Injectable({
  providedIn: 'root'
})
export class UserManagementService {

  constructor(private http: HttpClient) { }

  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
  getOptions(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'UserManagement/options', null).pipe(
      map(this.extractData));
  }
  getUserList(): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserManagement/getUserList', null).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getUserList'))
     );
  }
  addNewUser(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserManagement/createNewUser', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getAccessrights'))
     );
  }
  getUser(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'UserManagement/getUser', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('getAccessrights'))
     );
  }
  saveInstitution(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InstitutionManagement/saveInstitution', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('saveInstitution'))
     );
  }

  saveCustomDate(sendData): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'InstitutionManagement/saveCustomDate', JSON.stringify(sendData)).pipe(
       tap((result) => console.log(`updated ins data w/ stat=${result.status}`)),
       catchError(this.handleError<any>('saveCustomDate'))
     );
  }
  listInstitution(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'InstitutionManagement/listInstitution', null).pipe(
      map(this.extractData));
  }
  getHospitalOptions(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'InstitutionManagement/Options', null).pipe(
      map(this.extractData));
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
