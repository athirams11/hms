import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { DrConsultationService } from './dr-consultation.service';

describe('DrConsultationService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [DrConsultationService]}));

  it('should be created', () => {
    const service: DrConsultationService = TestBed.get(DrConsultationService);
    expect(service).toBeTruthy();
  });
});
