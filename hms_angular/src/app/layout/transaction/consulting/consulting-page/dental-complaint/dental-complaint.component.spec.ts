import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DentalComplaintComponent } from './dental-complaint.component';

describe('DentalComplaintComponent', () => {
  let component: DentalComplaintComponent;
  let fixture: ComponentFixture<DentalComplaintComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DentalComplaintComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DentalComplaintComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
