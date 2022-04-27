import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BillPatientComponent } from './bill-patient.component';

describe('BillPatientComponent', () => {
  let component: BillPatientComponent;
  let fixture: ComponentFixture<BillPatientComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BillPatientComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BillPatientComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
