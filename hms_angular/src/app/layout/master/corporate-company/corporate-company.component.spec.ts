import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CorporateCompanyComponent } from './corporate-company.component';

describe('CorporateCompanyComponent', () => {
  let component: CorporateCompanyComponent;
  let fixture: ComponentFixture<CorporateCompanyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CorporateCompanyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CorporateCompanyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
