import { Component, OnInit } from '@angular/core';
import { LoaderService } from './shared';
import { startWith, tap, delay } from 'rxjs/operators';
@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
    public loading : boolean;
    constructor(private loaderService : LoaderService) {
    }

    ngOnInit() {
        this.loaderService.status.pipe(
            startWith(null),
            delay(0),
        ).subscribe((val: boolean) => {
            this.loading = val;
        });
    }
}