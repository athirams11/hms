import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
import * as FileSaver from 'file-saver';
import * as XLSX from 'xlsx';
//const endpoint = 'http://192.168.0.11/hms/server/';
//const endpoint = 'http://wiktait.com/demo/hms/server/';

const EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
const EXCEL_EXTENSION = '.xlsx';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};
@Injectable({
  providedIn: 'root'
})
export class DrConsultationService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'DoctorSchedule/options',null).pipe(
      map(this.extractData));
  }
  addNewSchedule (opData): Observable<any> {
   // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'DoctorSchedule/newSchedule', JSON.stringify(opData)).pipe(
      tap((result) => console.log(`added new etry w/ id=${result.status}`)),
      catchError(this.handleError<any>('addNewOpRegistration'))
    );
  }
  getScheduleList(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'DoctorSchedule/getScheduleList',null).pipe(
      map(this.extractData));
  }
  getDoctorsSpecialized(postData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'DoctorSchedule/getDoctorsSpecialized',JSON.stringify(postData)).pipe(
      map(this.extractData));
  }
  getScheduleById(sendData): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'DoctorSchedule/getScheduleById',JSON.stringify(sendData)).pipe(
      map(this.extractData));
  }
  
  public exportAsExcelFile(json: any[], excelFileName: string): void {  
    const worksheet: XLSX.WorkSheet = XLSX.utils.json_to_sheet(json); 
    const workbook: XLSX.WorkBook = { Sheets: { 'data': worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' }); 
    this.saveAsExcelFile(excelBuffer, excelFileName);
  } 
  public saveAsExcelFile(buffer: any, fileName: string): void {
    const data: Blob = new Blob([buffer], {type: EXCEL_TYPE});   FileSaver.saveAs(data, fileName + '_export_' + new  Date().getTime() + EXCEL_EXTENSION);
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
