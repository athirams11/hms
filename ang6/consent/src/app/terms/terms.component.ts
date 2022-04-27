import { Component, OnInit } from '@angular/core';
import {MatBottomSheet, MatBottomSheetRef} from '@angular/material';
@Component({
  selector: 'app-terms',
  templateUrl: './terms.component.html',
  styleUrls: ['./terms.component.css']
})
export class TermsComponent implements OnInit {

  constructor(private bottomSheetRef: MatBottomSheetRef<TermsComponent>) {}

  

  ngOnInit() {
  }
  closePop(){
    this.bottomSheetRef.dismiss();
    
  }

}
