import { Component, OnInit } from '@angular/core';
import { routerTransition } from '../../router.animations';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.component.html',
  styleUrls: ['./transaction.component.scss'],
  animations: [routerTransition()]
})
export class TransactionComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

}
