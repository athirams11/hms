import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { InstitutionService } from './institution-service.service';

describe('InstitutionService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [InstitutionService]
  }));

  it('should be created', () => {
    const service: InstitutionService = TestBed.get(InstitutionService);
    expect(service).toBeTruthy();
  });
});
