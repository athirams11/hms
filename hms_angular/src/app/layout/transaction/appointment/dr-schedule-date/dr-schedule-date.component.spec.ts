import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DrScheduleDateComponent } from './dr-schedule-date.component';

describe('DrScheduleDateComponent', () => {
  let component: DrScheduleDateComponent;
  let fixture: ComponentFixture<DrScheduleDateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DrScheduleDateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DrScheduleDateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
