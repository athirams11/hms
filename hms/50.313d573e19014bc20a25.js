(window.webpackJsonp=window.webpackJsonp||[]).push([[50],{"+Yb1":function(e,t,n){"use strict";n.r(t);var o=n("CcnG"),r=function(){return function(){}}(),i=n("pMnS"),a=n("ZYCi"),l=n("Ip0R"),s=n("Zseb"),u=(n("M0ag"),n("/RaO")),d=(n("MO+k"),n("wd/R")),c=n("xXU7"),p=function(){function e(e){this.rest=e,this.user_rights={},this.setting=u.a,this.userCredentials=JSON.parse(localStorage.getItem("user")),this.now=new Date,this.user_group=this.userCredentials.user_group,this.user_id=this.userCredentials.user_id,this.per=[],this.pieChartOptions={responsive:!0,legend:{position:"right"},plugins:{datasets:{formatter:function(e,t){return t.chart.data.datasets[t.dataIndex]}}}},this.pieChartLabels=[],this.chartColors=[{backgroundColor:["#52D726","#FFEC00","#FF7300","#FF0000","#007ED6","#7CDDDD"]}],this.chartColor=[{backgroundColor:[]}],this.pieChartData=[],this.pieChartType="pie",this.count=[],this.source=Object(c.a)(5e3)}return e.prototype.ngOnInit=function(){var e=this;this.loadModules(),this.getModuleSummary(),this.formatDateTime(this.now),this.subscription=this.source.subscribe((function(t){return e.getModuleSummary()})),this.user_rights=JSON.parse(localStorage.getItem("user_rights"))},e.prototype.ngOnDestroy=function(){this.subscription&&this.subscription.unsubscribe()},e.prototype.formatDateTime=function(e){if(this.now&&(this.date=d(this.now,"yyyy-MM-D HH:mm.ss a").format("D-MM-Y HH:MM:ss")),e)return d(e,"yyyy-MM-D HH:mm.ss a").format("D-MM-Y HH:MM:ss")},e.prototype.loadModules=function(){var e=this;this.rest.getModules({user_group:this.userCredentials.user_login}).subscribe((function(t){window.scrollTo(0,0),"Success"==t.status&&(e.menulist=t.data,localStorage.setItem("modules",JSON.stringify(t.data)))}),(function(e){console.log(e)}))},e.prototype.getModuleSummary=function(){var e=this,t={user_group:0,user_id:this.user_id,date:this.formatDateTime(this.now)};this.rest.getModuleSummary(t).subscribe((function(t){"Success"==t.status&&(e.pieChartData=t.data.COUNT,e.pieChartLabels=t.data.NAME,1==t.data.COUNT&&(e.pieChartData=[1],e.chartColors=e.chartColor))}),(function(e){console.log(e)}))},e}(),h=n("ZXMZ"),m=o["\u0275crt"]({encapsulation:0,styles:[[".main[_ngcontent-%COMP%]{padding:30px;line-height:1.5;border-radius:0;height:40%}span[_ngcontent-%COMP%]{cursor:pointer;text-align:center;display:block;padding:20px;line-height:1.5;border-radius:0;width:180px;height:150px;margin:17px 20px}a[_ngcontent-%COMP%]{color:#1d578e}label[_ngcontent-%COMP%]{font-size:14px;text-align:center;font-weight:700}i[_ngcontent-%COMP%]{padding-top:20px}.fa[_ngcontent-%COMP%]{font-size:50px;font-weight:lighter}i[_ngcontent-%COMP%]:hover{padding-top:30px;transform:scale(1.5)}"]],data:{}});function f(e){return o["\u0275vid"](0,[(e()(),o["\u0275eld"](0,0,null,null,8,"div",[["class","col-2"]],null,null,null,null,null)),(e()(),o["\u0275eld"](1,0,null,null,7,"a",[["class","router-link-active ex1"],["href","javascript:void(0)"]],[[1,"target",0],[8,"href",4]],[[null,"click"]],(function(e,t,n){var r=!0;return"click"===t&&(r=!1!==o["\u0275nov"](e,2).onClick(n.button,n.ctrlKey,n.metaKey,n.shiftKey)&&r),r}),null,null)),o["\u0275did"](2,671744,null,0,a.n,[a.l,a.a,l.k],{routerLink:[0,"routerLink"]},null),o["\u0275pad"](3,1),(e()(),o["\u0275eld"](4,0,null,null,4,"span",[],null,null,null,null,null)),(e()(),o["\u0275eld"](5,0,null,null,1,"label",[["style","color: #1d578e"]],null,null,null,null,null)),(e()(),o["\u0275ted"](6,null,["",""])),(e()(),o["\u0275eld"](7,0,null,null,1,"div",[["style","text-align: center"]],null,null,null,null,null)),(e()(),o["\u0275eld"](8,0,null,null,0,"i",[["style","font-size: 55px;padding-top:10px"]],[[8,"className",0]],null,null,null,null))],(function(e,t){var n=e(t,3,0,t.parent.parent.context.$implicit.MODULE_GROUP_PATH+t.context.$implicit.MODULE_PATH);e(t,2,0,n)}),(function(e,t){e(t,1,0,o["\u0275nov"](t,2).target,o["\u0275nov"](t,2).href),e(t,6,0,t.context.$implicit.MODULE_NAME),e(t,8,0,o["\u0275inlineInterpolate"](1,"",t.context.$implicit.MODULE_ICON,""))}))}function v(e){return o["\u0275vid"](0,[(e()(),o["\u0275eld"](0,0,null,null,2,null,null,null,null,null,null,null)),(e()(),o["\u0275and"](16777216,null,null,1,null,f)),o["\u0275did"](2,278528,null,0,l.m,[o.ViewContainerRef,o.TemplateRef,o.IterableDiffers],{ngForOf:[0,"ngForOf"]},null),(e()(),o["\u0275and"](0,null,null,0))],(function(e,t){e(t,2,0,t.parent.context.$implicit.sub_menu)}),null)}function g(e){return o["\u0275vid"](0,[(e()(),o["\u0275eld"](0,0,null,null,2,null,null,null,null,null,null,null)),(e()(),o["\u0275and"](16777216,null,null,1,null,v)),o["\u0275did"](2,16384,null,0,l.n,[o.ViewContainerRef,o.TemplateRef],{ngIf:[0,"ngIf"]},null),(e()(),o["\u0275and"](0,null,null,0))],(function(e,t){e(t,2,0,t.context.$implicit.MODULE_GROUP_ID!=t.component.setting.DASHBOARD_MODULE_GROUP_ID)}),null)}function C(e){return o["\u0275vid"](0,[(e()(),o["\u0275eld"](0,0,null,null,6,"div",[["class","row container"]],null,null,null,null,null)),(e()(),o["\u0275eld"](1,0,null,null,3,"div",[["class","col-6"]],null,null,null,null,null)),(e()(),o["\u0275eld"](2,0,null,null,2,"div",[["style","width:500px"]],null,null,null,null,null)),(e()(),o["\u0275eld"](3,0,null,null,1,"canvas",[["baseChart",""]],null,null,null,null,null)),o["\u0275did"](4,999424,null,0,s.a,[o.ElementRef,s.c],{data:[0,"data"],labels:[1,"labels"],options:[2,"options"],chartType:[3,"chartType"],colors:[4,"colors"]},null),(e()(),o["\u0275and"](16777216,null,null,1,null,g)),o["\u0275did"](6,278528,null,0,l.m,[o.ViewContainerRef,o.TemplateRef,o.IterableDiffers],{ngForOf:[0,"ngForOf"]},null)],(function(e,t){var n=t.component;e(t,4,0,n.pieChartData,n.pieChartLabels,n.pieChartOptions,n.pieChartType,n.chartColors),e(t,6,0,n.menulist)}),null)}function y(e){return o["\u0275vid"](0,[(e()(),o["\u0275eld"](0,0,null,null,1,"app-dashboards",[],null,null,null,C,m)),o["\u0275did"](1,245760,null,0,p,[h.a],null,null)],(function(e,t){e(t,1,0)}),null)}var M=o["\u0275ccf"]("app-dashboards",p,y,{},{},[]),b=n("9AJC"),_=n("xkgV"),O=n("gIcY"),k=n("4GxJ"),x=n("t/Na"),w={level:2,path:"/dr-consultation"},T={level:2,path:"/dr-consultation"},A={level:2,path:"/dr-schedule-list"},P={level:2,path:"/doctors"},E={level:2,path:"/diagnosis"},S={level:2,path:"/medicine"},D={level:2,path:"/vaccine"},R={level:2,path:"/current-procedural-terminology"},I={level:2,path:"/op-new-registration"},N={level:2,path:"/op-new-registration"},F={level:2,path:"/op-new-registration"},j={level:2,path:"/appointment"},V={level:2,path:"/op-visit-entry"},U={level:2,path:"/pre-consulting"},L={level:2,path:"/consulting"},H={level:2,path:"/billing"},J={level:2,path:"/insurance"},G={level:2,path:"/patient-query"},K={level:2,path:"/appointment-list"},$={level:2,path:"/user-access"},q={level:2,path:"/user-management"},B=function(){return function(){}}(),X=n("A7o+"),Y=n("+Sv0"),Z=n("UVXo"),z=n("5NQ/"),W=n("RygT"),Q=n("eRTK");n.d(t,"DashboardsModuleNgFactory",(function(){return ee}));var ee=o["\u0275cmf"](r,[],(function(e){return o["\u0275mod"]([o["\u0275mpd"](512,o.ComponentFactoryResolver,o["\u0275CodegenComponentFactoryResolver"],[[8,[i.a,M,b.a,b.b,b.y,b.u,b.v,b.w,b.x]],[3,o.ComponentFactoryResolver],o.NgModuleRef]),o["\u0275mpd"](4608,l.p,l.o,[o.LOCALE_ID,[2,l.H]]),o["\u0275mpd"](4608,_.b,_.b,[]),o["\u0275mpd"](4608,O["\u0275angular_packages_forms_forms_j"],O["\u0275angular_packages_forms_forms_j"],[]),o["\u0275mpd"](4608,k.z,k.z,[o.ComponentFactoryResolver,o.Injector,k.nb,k.A]),o["\u0275mpd"](4608,O.FormBuilder,O.FormBuilder,[]),o["\u0275mpd"](4608,x.j,x.p,[l.c,o.PLATFORM_ID,x.n]),o["\u0275mpd"](4608,x.q,x.q,[x.j,x.o]),o["\u0275mpd"](5120,x.a,(function(e){return[e]}),[x.q]),o["\u0275mpd"](4608,x.m,x.m,[]),o["\u0275mpd"](6144,x.k,null,[x.m]),o["\u0275mpd"](4608,x.i,x.i,[x.k]),o["\u0275mpd"](6144,x.b,null,[x.i]),o["\u0275mpd"](4608,x.f,x.l,[x.b,o.Injector]),o["\u0275mpd"](4608,x.c,x.c,[x.f]),o["\u0275mpd"](1073742336,l.b,l.b,[]),o["\u0275mpd"](1073742336,a.o,a.o,[[2,a.u],[2,a.l]]),o["\u0275mpd"](1073742336,B,B,[]),o["\u0275mpd"](1073742336,X.g,X.g,[]),o["\u0275mpd"](1073742336,k.w,k.w,[]),o["\u0275mpd"](1073742336,_.a,_.a,[]),o["\u0275mpd"](1073742336,k.d,k.d,[]),o["\u0275mpd"](1073742336,k.g,k.g,[]),o["\u0275mpd"](1073742336,k.h,k.h,[]),o["\u0275mpd"](1073742336,k.l,k.l,[]),o["\u0275mpd"](1073742336,k.n,k.n,[]),o["\u0275mpd"](1073742336,O["\u0275angular_packages_forms_forms_bc"],O["\u0275angular_packages_forms_forms_bc"],[]),o["\u0275mpd"](1073742336,O.FormsModule,O.FormsModule,[]),o["\u0275mpd"](1073742336,k.t,k.t,[]),o["\u0275mpd"](1073742336,k.B,k.B,[]),o["\u0275mpd"](1073742336,k.F,k.F,[]),o["\u0275mpd"](1073742336,k.L,k.L,[]),o["\u0275mpd"](1073742336,k.O,k.O,[]),o["\u0275mpd"](1073742336,k.S,k.S,[]),o["\u0275mpd"](1073742336,k.Z,k.Z,[]),o["\u0275mpd"](1073742336,k.db,k.db,[]),o["\u0275mpd"](1073742336,k.gb,k.gb,[]),o["\u0275mpd"](1073742336,k.jb,k.jb,[]),o["\u0275mpd"](1073742336,k.C,k.C,[]),o["\u0275mpd"](1073742336,Y.a,Y.a,[]),o["\u0275mpd"](1073742336,Z.TextMaskModule,Z.TextMaskModule,[]),o["\u0275mpd"](1073742336,O.ReactiveFormsModule,O.ReactiveFormsModule,[]),o["\u0275mpd"](1073742336,z.b,z.b,[]),o["\u0275mpd"](1073742336,W.b,W.b,[]),o["\u0275mpd"](1073742336,x.e,x.e,[]),o["\u0275mpd"](1073742336,x.d,x.d,[]),o["\u0275mpd"](1073742336,s.b,s.b,[]),o["\u0275mpd"](1073742336,r,r,[]),o["\u0275mpd"](1024,a.j,(function(){return[[{path:"",children:[{path:"",component:p},{path:"dr-consultation",loadChildren:"../master/dr-consultation/dr-consultation.module#DrConsultationModule",canActivate:[Q.a],data:w},{path:"dr-consultation/:type/:id",loadChildren:"../master/dr-consultation/dr-consultation.module#DrConsultationModule",canActivate:[Q.a],data:T},{path:"dr-schedule-list",loadChildren:"../master/dr-schedule-list/dr-schedule-list.module#DrScheduleListModule",canActivate:[Q.a],data:A},{path:"doctors",loadChildren:"../master/doctors/doctors.module#DoctorsModule",canActivate:[Q.a],data:P},{path:"diagnosis",loadChildren:"../master/diagnosis/diagnosis.module#DiagnosisModule",canActivate:[Q.a],data:E},{path:"medicine",loadChildren:"../master/medicine/medicine.module#MedicineModule",canActivate:[Q.a],data:S},{path:"vaccine",loadChildren:"../master/vaccine/vaccine.module#VaccineModule",canActivate:[Q.a],data:D},{path:"current-procedural-terminology",loadChildren:"../master/current-procedural-terminology/current-procedural-terminology.module#CurrentProceduralTerminologyModule",canActivate:[Q.a],data:R},{path:"op-new-registration",loadChildren:"../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule",canActivate:[Q.a],data:I},{path:"op-new-registration/:app_id",loadChildren:"../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule",canActivate:[Q.a],data:N},{path:"op-new-registration/:/:id",loadChildren:"../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule",canActivate:[Q.a],data:F},{path:"appointment",loadChildren:"../transaction/appointment/appointment.module#AppointmentModule",canActivate:[Q.a],data:j},{path:"op-visit-entry",loadChildren:"../transaction/op-visit-entry/op-visit-entry.module#OpVisitEntryModule",canActivate:[Q.a],data:V},{path:"pre-consulting",loadChildren:"../transaction/pre-consulting/pre-consulting.module#PreConsultingModule",canActivate:[Q.a],data:U},{path:"consulting",loadChildren:"../transaction/consulting/consulting.module#ConsultingModule",canActivate:[Q.a],data:L},{path:"billing",loadChildren:"../transaction/billing/billing.module#BillingModule",canActivate:[Q.a],data:H},{path:"insurance",loadChildren:"../claim-process/insurance/insurance.module#InsuranceModule",canActivate:[Q.a],data:J},{path:"patient-query",canActivate:[Q.a],data:G},{path:"appointment-list",canActivate:[Q.a],data:K},{path:"user-access",loadChildren:"../settings/user-access/user-access.module#UserAccessModule",canActivate:[Q.a],data:$},{path:"user-management",loadChildren:"../settings/user-management/user-management.module#UserManagementModule",canActivate:[Q.a],data:q}],runGuardsAndResolvers:"always"}]]}),[]),o["\u0275mpd"](256,x.n,"XSRF-TOKEN",[]),o["\u0275mpd"](256,x.o,"X-XSRF-TOKEN",[])])}))},FpO7:function(e,t,n){e.exports=function(e){function t(o){if(n[o])return n[o].exports;var r=n[o]={exports:{},id:o,loaded:!1};return e[o].call(r.exports,r,r.exports,t),r.loaded=!0,r.exports}var n={};return t.m=e,t.c=n,t.p="",t(0)}([function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var r=n(3);Object.defineProperty(t,"conformToMask",{enumerable:!0,get:function(){return o(r).default}});var i=n(2);Object.defineProperty(t,"adjustCaretPosition",{enumerable:!0,get:function(){return o(i).default}});var a=n(5);Object.defineProperty(t,"createTextMaskInputElement",{enumerable:!0,get:function(){return o(a).default}})},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.placeholderChar="_",t.strFunction="function"},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=function(e){var t=e.previousConformedValue,r=void 0===t?o:t,i=e.previousPlaceholder,a=void 0===i?o:i,l=e.currentCaretPosition,s=void 0===l?0:l,u=e.conformedValue,d=e.rawValue,c=e.placeholderChar,p=e.placeholder,h=e.indexesOfPipedChars,m=void 0===h?n:h,f=e.caretTrapIndexes,v=void 0===f?n:f;if(0===s||!d.length)return 0;var g=r.length,C=p.length,y=u.length,M=d.length-g,b=M>0;if(M>1&&!b&&0!==g)return s;var _=0,O=void 0,k=void 0;if(!b||r!==u&&u!==p){var x=u.toLowerCase(),w=d.toLowerCase().substr(0,s).split(o).filter((function(e){return-1!==x.indexOf(e)}));k=w[w.length-1];var T=a.substr(0,w.length).split(o).filter((function(e){return e!==c})).length,A=p.substr(0,w.length).split(o).filter((function(e){return e!==c})).length;!b&&(A!==T||void 0!==a[w.length-1]&&void 0!==p[w.length-2]&&a[w.length-1]!==c&&a[w.length-1]!==p[w.length-1]&&a[w.length-1]===p[w.length-2])&&T>0&&p.indexOf(k)>-1&&void 0!==d[s]&&(O=!0,k=d[s]);for(var P=m.map((function(e){return x[e]})).filter((function(e){return e===k})).length,E=w.filter((function(e){return e===k})).length,S=p.substr(0,p.indexOf(c)).split(o).filter((function(e,t){return e===k&&d[t]!==e})).length+E+P+(O?1:0),D=0,R=0;R<y&&(_=R+1,x[R]===k&&D++,!(D>=S));R++);}else _=s-M;if(b){for(var I=_,N=_;N<=C;N++)if(p[N]===c&&(I=N),p[N]===c||-1!==v.indexOf(N)||N===C)return I}else if(O){for(var F=_-1;F>=0;F--)if(u[F]===k||-1!==v.indexOf(F)||0===F)return F}else for(var j=_;j>=0;j--)if(p[j-1]===c||-1!==v.indexOf(j)||0===j)return j};var n=[],o=""},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};t.default=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:l,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:a,n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};if(!(0,r.isArray)(t)){if((void 0===t?"undefined":o(t))!==i.strFunction)throw new Error("Text-mask:conformToMask; The mask property must be an array.");t=t(e,n),t=(0,r.processCaretTraps)(t).maskWithoutCaretTraps}var s=n.guide,u=void 0===s||s,d=n.previousConformedValue,c=void 0===d?l:d,p=n.placeholderChar,h=void 0===p?i.placeholderChar:p,m=n.placeholder,f=void 0===m?(0,r.convertMaskToPlaceholder)(t,h):m,v=n.currentCaretPosition,g=n.keepCharPositions,C=!1===u&&void 0!==c,y=e.length,M=c.length,b=f.length,_=t.length,O=y-M,k=O>0,x=v+(k?-O:0),w=x+Math.abs(O);if(!0===g&&!k){for(var T=l,A=x;A<w;A++)f[A]===h&&(T+=h);e=e.slice(0,x)+T+e.slice(x,y)}for(var P=e.split(l).map((function(e,t){return{char:e,isNew:t>=x&&t<w}})),E=y-1;E>=0;E--){var S=P[E].char;if(S!==h){var D=E>=x&&M===_;S===f[D?E-O:E]&&P.splice(E,1)}}var R=l,I=!1;e:for(var N=0;N<b;N++){var F=f[N];if(F===h){if(P.length>0)for(;P.length>0;){var j=P.shift(),V=j.char,U=j.isNew;if(V===h&&!0!==C){R+=h;continue e}if(t[N].test(V)){if(!0===g&&!1!==U&&c!==l&&!1!==u&&k){for(var L=P.length,H=null,J=0;J<L;J++){var G=P[J];if(G.char!==h&&!1===G.isNew)break;if(G.char===h){H=J;break}}null!==H?(R+=V,P.splice(H,1)):N--}else R+=V;continue e}I=!0}!1===C&&(R+=f.substr(N,b));break}R+=F}if(C&&!1===k){for(var K=null,$=0;$<R.length;$++)f[$]===h&&(K=$);R=null!==K?R.substr(0,K+1):l}return{conformedValue:R,meta:{someCharsRejected:I}}};var r=n(4),i=n(1),a=[],l=""},function(e,t,n){"use strict";function o(e){return Array.isArray&&Array.isArray(e)||e instanceof Array}Object.defineProperty(t,"__esModule",{value:!0}),t.convertMaskToPlaceholder=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:i,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:r.placeholderChar;if(!o(e))throw new Error("Text-mask:convertMaskToPlaceholder; The mask property must be an array.");if(-1!==e.indexOf(t))throw new Error("Placeholder character must not be used as part of the mask. Please specify a character that is not present in your mask as your placeholder character.\n\nThe placeholder character that was received is: "+JSON.stringify(t)+"\n\nThe mask that was received is: "+JSON.stringify(e));return e.map((function(e){return e instanceof RegExp?t:e})).join("")},t.isArray=o,t.isString=function(e){return"string"==typeof e||e instanceof String},t.isNumber=function(e){return"number"==typeof e&&void 0===e.length&&!isNaN(e)},t.isNil=function(e){return null==e},t.processCaretTraps=function(e){for(var t=[],n=void 0;-1!==(n=e.indexOf(a));)t.push(n),e.splice(n,1);return{maskWithoutCaretTraps:e,indexes:t}};var r=n(1),i=[],a="[]"},function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}function r(e,t){document.activeElement===e&&(f?v((function(){return e.setSelectionRange(t,t,h)}),0):e.setSelectionRange(t,t,h))}function i(e){if((0,d.isString)(e))return e;if((0,d.isNumber)(e))return String(e);if(null==e)return p;throw new Error("The 'value' provided to Text Mask needs to be a string or a number. The value received was:\n\n "+JSON.stringify(e))}Object.defineProperty(t,"__esModule",{value:!0});var a=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(e[o]=n[o])}return e},l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};t.default=function(e){var t={previousConformedValue:void 0,previousPlaceholder:void 0};return{state:t,update:function(n){var o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:e,h=o.inputElement,f=o.mask,v=o.guide,g=o.pipe,C=o.placeholderChar,y=void 0===C?c.placeholderChar:C,M=o.keepCharPositions,b=void 0!==M&&M,_=o.showMask,O=void 0!==_&&_;if(void 0===n&&(n=h.value),n!==t.previousConformedValue){(void 0===f?"undefined":l(f))===m&&void 0!==f.pipe&&void 0!==f.mask&&(g=f.pipe,f=f.mask);var k=void 0,x=void 0;if(f instanceof Array&&(k=(0,d.convertMaskToPlaceholder)(f,y)),!1!==f){var w=i(n),T=h.selectionEnd,A=t.previousConformedValue,P=t.previousPlaceholder,E=void 0;if((void 0===f?"undefined":l(f))===c.strFunction){if(!1===(x=f(w,{currentCaretPosition:T,previousConformedValue:A,placeholderChar:y})))return;var S=(0,d.processCaretTraps)(x),D=S.maskWithoutCaretTraps,R=S.indexes;E=R,k=(0,d.convertMaskToPlaceholder)(x=D,y)}else x=f;var I={previousConformedValue:A,guide:v,placeholderChar:y,pipe:g,placeholder:k,currentCaretPosition:T,keepCharPositions:b},N=(0,u.default)(w,x,I),F=N.conformedValue,j=(void 0===g?"undefined":l(g))===c.strFunction,V={};j&&(!1===(V=g(F,a({rawValue:w},I)))?V={value:A,rejected:!0}:(0,d.isString)(V)&&(V={value:V}));var U=j?V.value:F,L=(0,s.default)({previousConformedValue:A,previousPlaceholder:P,conformedValue:U,placeholder:k,rawValue:w,currentCaretPosition:T,placeholderChar:y,indexesOfPipedChars:V.indexesOfPipedChars,caretTrapIndexes:E}),H=U===k&&0===L,J=O?k:p,G=H?J:U;t.previousConformedValue=G,t.previousPlaceholder=k,h.value!==G&&(h.value=G,r(h,L))}}}}};var s=o(n(2)),u=o(n(3)),d=n(4),c=n(1),p="",h="none",m="object",f="undefined"!=typeof navigator&&/Android/i.test(navigator.userAgent),v="undefined"!=typeof requestAnimationFrame?requestAnimationFrame:setTimeout}])},UVXo:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n("CcnG"),r=n("gIcY"),i=n("ZYjt"),a=n("FpO7");t.TextMaskConfig=function(){return function(){}}(),t.MASKEDINPUT_VALUE_ACCESSOR={provide:r.NG_VALUE_ACCESSOR,useExisting:o.forwardRef((function(){return l})),multi:!0};var l=function(){function e(e,t,n){var o;this._renderer=e,this._elementRef=t,this._compositionMode=n,this.textMaskConfig={mask:[],guide:!0,placeholderChar:"_",pipe:void 0,keepCharPositions:!1},this.onChange=function(e){},this.onTouched=function(){},this._composing=!1,null==this._compositionMode&&(this._compositionMode=(o=i.\u0275getDOM()?i.\u0275getDOM().getUserAgent():"",!/android (\d+)/.test(o.toLowerCase())))}return e.prototype.ngOnChanges=function(e){this._setupMask(!0),void 0!==this.textMaskInputElement&&this.textMaskInputElement.update(this.inputElement.value)},e.prototype.writeValue=function(e){this._setupMask(),this._renderer.setProperty(this.inputElement,"value",null==e?"":e),void 0!==this.textMaskInputElement&&this.textMaskInputElement.update(e)},e.prototype.registerOnChange=function(e){this.onChange=e},e.prototype.registerOnTouched=function(e){this.onTouched=e},e.prototype.setDisabledState=function(e){this._renderer.setProperty(this._elementRef.nativeElement,"disabled",e)},e.prototype._handleInput=function(e){(!this._compositionMode||this._compositionMode&&!this._composing)&&(this._setupMask(),void 0!==this.textMaskInputElement&&(this.textMaskInputElement.update(e),this.onChange(e=this.inputElement.value)))},e.prototype._setupMask=function(e){void 0===e&&(e=!1),this.inputElement||(this.inputElement="INPUT"===this._elementRef.nativeElement.tagName.toUpperCase()?this._elementRef.nativeElement:this._elementRef.nativeElement.getElementsByTagName("INPUT")[0]),this.inputElement&&e&&(this.textMaskInputElement=a.createTextMaskInputElement(Object.assign({inputElement:this.inputElement},this.textMaskConfig)))},e.prototype._compositionStart=function(){this._composing=!0},e.prototype._compositionEnd=function(e){this._composing=!1,this._compositionMode&&this._handleInput(e)},e.decorators=[{type:o.Directive,args:[{host:{"(input)":"_handleInput($event.target.value)","(blur)":"onTouched()","(compositionstart)":"_compositionStart()","(compositionend)":"_compositionEnd($event.target.value)"},selector:"[textMask]",exportAs:"textMask",providers:[t.MASKEDINPUT_VALUE_ACCESSOR]}]}],e.propDecorators={textMaskConfig:[{type:o.Input,args:["textMask"]}]},e}();t.MaskedInputDirective=l,t.TextMaskModule=function(){function e(){}return e.decorators=[{type:o.NgModule,args:[{declarations:[l],exports:[l]}]}],e}();var s=n("FpO7");t.conformToMask=s.conformToMask}}]);