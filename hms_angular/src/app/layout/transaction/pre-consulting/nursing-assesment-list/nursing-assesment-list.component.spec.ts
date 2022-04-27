import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NursingAssesmentListComponent } from './nursing-assesment-list.component';

describe('NursingAssesmentListComponent', () => {
  let component: NursingAssesmentListComponent;
  let fixture: ComponentFixture<NursingAssesmentListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NursingAssesmentListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NursingAssesmentListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
