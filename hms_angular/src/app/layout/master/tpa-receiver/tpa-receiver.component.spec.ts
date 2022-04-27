import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TpaReceiverComponent } from './tpa-receiver.component';

describe('TpaReceiverComponent', () => {
  let component: TpaReceiverComponent;
  let fixture: ComponentFixture<TpaReceiverComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TpaReceiverComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TpaReceiverComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
