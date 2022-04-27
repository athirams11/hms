import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VisitReportComponent } from './visit-report.component';

describe('VisitReportComponent', () => {
  let component: VisitReportComponent;
  let fixture: ComponentFixture<VisitReportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VisitReportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VisitReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
