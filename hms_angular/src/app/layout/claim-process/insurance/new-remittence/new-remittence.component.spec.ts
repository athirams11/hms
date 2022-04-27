import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NewRemittenceComponent } from './new-remittence.component';

describe('NewRemittenceComponent', () => {
  let component: NewRemittenceComponent;
  let fixture: ComponentFixture<NewRemittenceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NewRemittenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NewRemittenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
