import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
//const endpoint = 'http://wiktait.com/demo/hms/server/';
//const endpoint = 'http://192.168.0.11/hms/server/';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};
@Injectable({
  providedIn: 'root'
})
export class AppointmentService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'Appointment/options',null).pipe(
      map(this.extractData));
  }
  getDrSchduleByDate (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getDrSchduleByDate', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDrSchduleByDate'))
    ));
  }
  getDrSchduleForWeek (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getDrSchduleForWeek', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDrSchduleForWeek'))
    ));
  }
  getAppointmentsByDate (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getAppointmentsByDate', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDrSchduleByDate'))
    ));
  }
  getAppointmentsByfromtoDate (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getAppointmentsByfromtoDate', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDrSchduleByDate'))
    ));
  }
  downloadgeneralconsent(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Consent/downloadgeneralconsent_reg', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloadgeneralconsent_reg'))
    ));
  }

  getAppointmentsByDoctor (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getAppointmentsByDoctor', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getAppointmentsByDoctor'))
    ));
  }
  cancelAppointment (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/cancelAppointment', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('cancelAppointment'))
    ));
  }
  changeAppointmentStatus(params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/changeAppointmentStatus', JSON.stringify(params)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('changeAppointmentStatus'))
    ));
  }
  getAllAppointments (postData): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getAllAppointments', JSON.stringify(postData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getAllAppointments'))
    ));
  }
  addNewAppointMent (appData): Observable<any> {
    //console.log(appData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/addNewAppointment', JSON.stringify(appData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('addNewOpRegistration'))
    ));
  }
  checkAvailableSlots(appData): Observable<any> {
    //console.log(appData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/checkAvailableSlots', JSON.stringify(appData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('checkAvailableSlots'))
    ));
  }
  getAppointMent (appData): Observable<any> {
    //console.log(appData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getAppointment', JSON.stringify(appData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getAppointMent'))
    ));
  }

  getemri (appData): Observable<any> {
    //console.log(appData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getemri', JSON.stringify(appData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getemri'))
    ));
  }

  getPatientDetails (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getPatientDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getPatientByEIDnumber (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getPatientByEIDnumber', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientByEIDnumber'))
    ));
  }
  getPatientsByPhoneNo (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getPatientsByPhoneNo', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientsByPhoneNo'))
    ));
  }
  getDoctersByDate (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Appointment/getDoctersByDate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDoctersByDate'))
    ));
  }
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
