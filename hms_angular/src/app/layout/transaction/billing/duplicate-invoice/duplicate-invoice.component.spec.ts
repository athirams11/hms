import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DuplicateInvoiceComponent } from './duplicate-invoice.component';

describe('DuplicateInvoiceComponent', () => {
  let component: DuplicateInvoiceComponent;
  let fixture: ComponentFixture<DuplicateInvoiceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DuplicateInvoiceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DuplicateInvoiceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
