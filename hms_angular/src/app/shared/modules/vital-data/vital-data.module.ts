import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VitalDataComponent } from './vital-data.component';

@NgModule({
  declarations: [VitalDataComponent],
  imports: [
    CommonModule
  ],
  exports: [VitalDataComponent]
})
export class VitalDataModule { }
