import { Component, OnInit,NgModule} from '@angular/core';
import { Injectable } from "@angular/core";



@Component({
    selector: 'app-layout',
    templateUrl: './layout.component.html',
    styleUrls: ['./layout.component.scss']
})

@Injectable()
export class LayoutComponent implements OnInit {
    collapedSideBar: boolean;

    constructor() {}

    ngOnInit() {}

    receiveCollapsed($event) {
        this.collapedSideBar = $event;
    }
    public handleClick($event)
    {
        //localStorage.getItem('activeTime');
        var date=  new Date();
        localStorage.setItem('activeTime',date.toString());
       // console.log(date.toString());
    }
}
