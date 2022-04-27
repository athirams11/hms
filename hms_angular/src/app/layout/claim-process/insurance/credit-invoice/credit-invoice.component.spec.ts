import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CreditInvoiceComponent } from './credit-invoice.component';

describe('CreditInvoiceComponent', () => {
  let component: CreditInvoiceComponent;
  let fixture: ComponentFixture<CreditInvoiceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CreditInvoiceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CreditInvoiceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
