import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { ModuleService } from './module.service';

describe('ModuleService', () => {
  beforeEach(() => TestBed.configureTestingModule({
    imports: [HttpClientTestingModule], 
    providers: [ModuleService]}));

  it('should be created', () => {
    const service: ModuleService = TestBed.get(ModuleService);
    expect(service).toBeTruthy();
  });
});
