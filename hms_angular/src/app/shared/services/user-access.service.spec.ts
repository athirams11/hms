import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { UserAccessService } from './user-access.service';

describe('UserAccessService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [UserAccessService]}));

  it('should be created', () => {
    const service: UserAccessService = TestBed.get(UserAccessService);
    expect(service).toBeTruthy();
  });
});
