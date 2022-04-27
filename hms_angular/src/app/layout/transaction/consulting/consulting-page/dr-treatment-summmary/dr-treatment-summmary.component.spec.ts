import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrTreatmentSummmaryComponent } from './dr-treatment-summmary.component';

describe('DrTreatmentSummmaryComponent', () => {
  let component: DrTreatmentSummmaryComponent;
  let fixture: ComponentFixture<DrTreatmentSummmaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrTreatmentSummmaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrTreatmentSummmaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
