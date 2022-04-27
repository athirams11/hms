import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OpNewRegistrationComponent } from './op-new-registration.component';

describe('OpNewRegistrationComponent', () => {
  let component: OpNewRegistrationComponent;
  let fixture: ComponentFixture<OpNewRegistrationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OpNewRegistrationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OpNewRegistrationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
