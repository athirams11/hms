import { Component, OnInit } from '@angular/core';
import { ModuleService } from 'src/app/shared';
import { AppSettings } from 'src/app/app.settings';
import { ChartOptions, ChartType, ChartColor } from 'chart.js';
import { Label, SingleDataSet, Colors, Color } from 'ng2-charts';
import * as moment from 'moment';
import { interval, observable, Subscription } from 'rxjs';
//import * as pluginDataLabels from 'chartjs-plugin-datalabels';
import { ArrayType } from '@angular/compiler';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  public user_rights: any = {};
  public menulist: any[];
  public setting = AppSettings;
  public userCredentials = JSON.parse(localStorage.getItem('user'));
  public now = new Date();
  public date: any;
  public user_group = this.userCredentials.user_group;
  public user_id = this.userCredentials.user_id;
  public per: any = [];
  public pieChartOptions: ChartOptions = {
    responsive: true,
    legend: { position: 'right' },
    plugins: {
      datasets: {
        formatter: (value, ctx) => {
          const label = ctx.chart.data.datasets[ctx.dataIndex];
          return label;
        },
      },
    }
  };
  // public pieChartPlugins = [pluginDataLabels];
  public pieChartLabels: Label[] = [];
  public chartColors: Array<object> = [
    { // all colors in order
      backgroundColor: ["#52D726", "#FFEC00", "#FF7300", "#FF0000", "#007ED6", "#7CDDDD"],
    },
  ]
  public chartColor: Array<object> = [
    { // all colors in order
      backgroundColor: [],
    },
  ]
  public pieChartData: SingleDataSet = [];
  public pieChartType: ChartType = 'pie';
  // public pieChartColors : Color[] = 'backgroundColor'
  // public pieChartLegend = false;
  //public pieChartPlugins = [];
  public count: any = [];
  public subscription: Subscription;
  source = interval(5000);
  name: any;
  allZero: boolean = false;

  constructor(public rest: ModuleService) {
    // monkeyPatchChartJsTooltip();
    // monkeyPatchChartJsLegend();
  }

  ngOnInit() {
    this.loadModules();
    this.getModuleSummary();
    this.formatDateTime(this.now);
    this.subscription = this.source.subscribe(x => this.getModuleSummary());
    this.user_rights = JSON.parse(localStorage.getItem('user_rights'));
  }
  ngOnDestroy() {
    // avoid memory leaks here by cleaning up after ourselves. If we  
    // don't then we will continue to run our initialiseInvites()   
    // method on every navigationEnd event.
    if (this.subscription) {
      this.subscription.unsubscribe();
    }
  }
  public formatDateTime(data) {
    if (this.now) {
      this.date = moment(this.now, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
    }
    if (data) {
      data = moment(data, 'yyyy-MM-D HH:mm.ss a').format('D-MM-Y HH:MM:ss');
      //   this.date = data;
      return data;
    }
  }
  loadModules() {
    var sentData = {
      user_group: this.userCredentials.user_login
    }
    this.rest.getModules(sentData).subscribe((result) => {
      window.scrollTo(0, 0)
      if (result["status"] == "Success") {
        this.menulist = result["data"];
        localStorage.setItem('modules', JSON.stringify(result["data"]));
      }
      else {

      }
      //MessageBox.show(this.dialog, `Hello, World!`);
    }, (err) => {
      console.log(err);
    });
  }
  getModuleSummary() {
    const sentData = {
      user_group: 0,
      user_id: this.user_id,
      date: this.formatDateTime(this.now)
      //date: "24-12-2019 14:12:32"

    };
    this.rest.getModuleSummary(sentData).subscribe((result) => {
      if (result['status'] == 'Success') {
        this.pieChartData = result['data']['COUNT'];
        this.allZero = result['data']['COUNT'].every(item => item === 0);
        this.pieChartLabels = result['data']['NAME'];
        if (result['data']['COUNT'] == 1) {
          this.pieChartData = [1];
          this.chartColors = this.chartColor;
          //this.pieChartLegend = false;
        }

        // this.count = result['data']['COUNT'];
        //     var sum = this.count.reduce((acc, cur) => acc + cur, 0);
        // console.log(sum);
        // var a =  this.count.map(function(x) { return x / sum; });
        // console.log("sum"+a);
        // this.per.percentage = a.map(function(x) { return x * 100; });
        // console.log("per"+this.per);

        // this.per.name  = result['data']['NAME'];
        // console.log("per"+this.per.percentage);
      }
      else {

      }
    }, (err) => {
      console.log(err);
    });
  }
}