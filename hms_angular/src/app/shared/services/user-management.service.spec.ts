import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { UserManagementService } from './user-management.service';

describe('UserManagementService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [UserManagementService]}));

  it('should be created', () => {
    const service: UserManagementService = TestBed.get(UserManagementService);
    expect(service).toBeTruthy();
  });
});
