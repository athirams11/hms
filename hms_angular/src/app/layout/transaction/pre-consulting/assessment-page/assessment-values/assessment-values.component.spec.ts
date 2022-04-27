import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AssessmentValuesComponent } from './assessment-values.component';

describe('AssessmentValuesComponent', () => {
  let component: AssessmentValuesComponent;
  let fixture: ComponentFixture<AssessmentValuesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AssessmentValuesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AssessmentValuesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
