import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
@Injectable({
  providedIn: 'root'
})
export class InstitutionService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    const body = res;
    return body || { };
  }
  getOptions(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'InstitutionManagement/Options', null).pipe(
      map(this.extractData));
  }
  // getDropdowns(): Observable<any> {
  //   return this.http.post(AppSettings.API_ENDPOINT + 'DoctorSchedule/options',null).pipe(
  //     map(this.extractData));
  // }
  // addNewSchedule (opData): Observable<any> {
  //  // console.log(opData);
  //   return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorSchedule/newSchedule', JSON.stringify(opData)).pipe(
  //     tap((result) => console.log(`added new etry w/ id=${result.status}`)),
  //     catchError(this.handleError<any>('addNewOpRegistration'))
  //   );
  // }
   //To handle error
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
