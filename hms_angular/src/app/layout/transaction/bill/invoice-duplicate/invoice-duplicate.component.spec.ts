import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { InvoiceDuplicateComponent } from './invoice-duplicate.component';

describe('InvoiceDuplicateComponent', () => {
  let component: InvoiceDuplicateComponent;
  let fixture: ComponentFixture<InvoiceDuplicateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InvoiceDuplicateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(InvoiceDuplicateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
