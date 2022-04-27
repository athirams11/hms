import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrScheduleListComponent } from './dr-schedule-list.component';

describe('DrScheduleListComponent', () => {
  let component: DrScheduleListComponent;
  let fixture: ComponentFixture<DrScheduleListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrScheduleListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrScheduleListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
