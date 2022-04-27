import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { OpRegistrationService } from './op-registration.service';

describe('OpRegistrationService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [OpRegistrationService]}));

  it('should be created', () => {
    const service: OpRegistrationService = TestBed.get(OpRegistrationService);
    expect(service).toBeTruthy();
  });
});
