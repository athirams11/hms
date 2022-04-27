import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubmittedFilesComponent } from './submitted-files.component';

describe('SubmittedFilesComponent', () => {
  let component: SubmittedFilesComponent;
  let fixture: ComponentFixture<SubmittedFilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubmittedFilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubmittedFilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
