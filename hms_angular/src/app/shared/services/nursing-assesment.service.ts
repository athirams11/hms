import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { AppSettings } from '../../app.settings';
const httpOptions = {
  headers: new HttpHeaders({
    'Content-Type':  'application/json'
  })
};
@Injectable({
  providedIn: 'root'
})
export class NursingAssesmentService {

  constructor(private http: HttpClient) { }
  private extractData(res: Response) {
    let body = res;
    return body || { };
  }
  getOpDropdowns(): Observable<any> {
    return this.http.post(AppSettings.API_ENDPOINT + 'OpRegistration/options',null).pipe(
      map(this.extractData));
  }
  startAssesment (opData): Observable<any> {
   // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/startAssesment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('startAssesment'))
    ));
  }
  downloadgeneralconsent(opData): Observable<any> {
    // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Consent/downloadgeneralconsent', JSON.stringify(opData)).pipe(
    tap((result) => 
    catchError(this.handleError<any>('downloadgeneralconsent'))
    ));
  }
  
  cancelVisit (params): Observable<any> {
    //console.log(params);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/cancelVisit', JSON.stringify(params)).pipe(
      tap((result) =>
      catchError(this.handleError<any>('cancelVisit'))
    ));
  }
  getPatientDetails (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'OpRegistration/getPatientDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getCPT(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/getCurrentProceduralCode',JSON.stringify(postdata)).pipe(
      map(this.extractData));
  }
  getCPTByCode(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/getCPTByCode',JSON.stringify(postdata)).pipe(
      map(this.extractData));
  }
  getCurrentDentalByDentalCode(postdata): Observable<any> {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'CurrentProceduralCode/getCurrentDentalByDentalCode',JSON.stringify(postdata)).pipe(
      map(this.extractData));
  }
  getAssesmentListByDate (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getAssesmentListByDate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getDoctorAssesmentListByDate (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getDoctorAssesmentListByDate', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDoctorAssesmentListByDate'))
    ));
  }
  
  getAssesmentParameters (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getAssesmentParameters', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getBloodSugarReport (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getBloodSugarReport', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('BloodSugarReport'))
    ));
  }
  getAssesmentParameterValues (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getAssesmentParameterValues', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getAssessmentMenus (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getAssessmentMenus', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  get_sub_modules (opData): Observable<any> {
   // console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Settings/get_sub_modules', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  editAssesmentValues (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/editAssesmentValues', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }

  saveAssesmentParameters (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/saveAssesmentParameters', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  saveBloodSugarReport (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/saveBloodSugarReport', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveBloodSugarReport'))
    ));
  }
  saveDocuments (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'FileManage/saveFile', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
    ));
  }
  deleteDocuments (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'FileManage/deleteFile', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
    ));
  }
  getDocuments (opData) : Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'FileManage/listFiles',JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
      }

  getReports (opData) : Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/listReports',JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('Document error'))
      ));
      }
  
  getradiologyReports (opData) : Observable<any> {
        //console.log(opData);
        return this.http.post<any>(AppSettings.API_ENDPOINT + 'Laboratory/getradiologyReports',JSON.stringify(opData)).pipe(
          tap((result) => 
          catchError(this.handleError<any>('Document error'))
          ));
          }

  saveAssesmentNotes (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessmentNotes/saveAssesmentParameters', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientDetails'))
    ));
  }
  getNotesParameters (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessmentNotes/getAssesmentParameters', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getNotesParameters'))
    ));
  }
  
  editNotesAssesmentValues (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessmentNotes/editAssesmentValues', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('editNotesAssesmentValues'))
    ));
  }
  listPatientAllergies (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientAllergies/listPatientAllergies', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listPatientAllergies'))
    ));
  }
  getPatientAllergies (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientAllergies/getPatientAllergies', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPatientAllergies'))
    ));
  }
  savePatientAllergies (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientAllergies/savePatientAllergies', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePatientAllergies'))
    ));
  }
  deletePatientAllergies (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PatientAllergies/deletePatientAllergies', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('deletePatientAllergies'))
    ));
  }
  listAllergiesOther (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'AllergiesOther/listAllergiesOther', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('listAllergiesOther'))
    ));
  }
  
  saveAllergiesOther (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'AllergiesOther/saveAllergiesOther', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('saveAllergiesOther'))
    ));
  }
  deleteAllergiesOther (opData): Observable<any> {
    //console.log(opData);
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'AllergiesOther/deleteAllergiesOther', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('deleteAllergiesOther'))
    ));
  }
  completeAssessment(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/completeAssesment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('completeAssessment'))
    ));
    
  }
  completeDoctorAssesment(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/completeDoctorAssesment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('completeDoctorAssesment'))
    ));
    
  }
  getPreviousAssessmentDetails(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getPreviousAssessmentDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPreviousAssessmentDetails'))
    ));
    
  }
  getNextAssessmentDetails(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getNextAssessmentDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getNextAssessmentDetails'))
    ));
    
  }
  getDiscountTreatmentDetails(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getDiscountTreatmentDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getDiscountTreatmentDetails'))
    ));
  }
  getallVisitDetails(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getallVisitDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getallVisitDetails'))
    ));
  }
  getAssessmentDetails(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'NursingAssessment/getAssessmentDetails', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getAssessmentDetails'))
    ));
  } 
  savePainAssesment(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PainAssessment/savePainAssesment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('savePainAssesment'))
    ));
  } 
  getPainAssesment(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PainAssessment/getPainAssesment', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPainAssesment'))
    ));
  } 
  getPainAssesments(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PainAssessment/getPainAssesments', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('getPainAssesments'))
    ));
  }
  get_master_data(opData)
  {
    return this.http.post<any>(AppSettings.API_ENDPOINT + 'PainAssessment/get_master_data', JSON.stringify(opData)).pipe(
      tap((result) => 
      catchError(this.handleError<any>('get_master_data'))
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
