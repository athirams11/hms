import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChiefcomplaintsComponent } from './chiefcomplaints.component';

describe('ChiefcomplaintsComponent', () => {
  let component: ChiefcomplaintsComponent;
  let fixture: ComponentFixture<ChiefcomplaintsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChiefcomplaintsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChiefcomplaintsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
