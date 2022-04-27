import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SickLeaveCertificateComponent } from './sick-leave-certificate.component';

describe('SickLeaveCertificateComponent', () => {
  let component: SickLeaveCertificateComponent;
  let fixture: ComponentFixture<SickLeaveCertificateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SickLeaveCertificateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SickLeaveCertificateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
