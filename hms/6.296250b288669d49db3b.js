(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"GgH/":function(t,n,e){"use strict";e.d(n,"a",(function(){return o}));var o=function(){return function(){}}()},MOZF:function(t,n,e){"use strict";var o=e("CcnG"),i=e("Ip0R");e("lTVM"),e("ZYCi"),e("rsm9"),e("CGbT"),e.d(n,"a",(function(){return l})),e.d(n,"b",(function(){return a}));var l=o["\u0275crt"]({encapsulation:0,styles:[["td[_ngcontent-%COMP%]{font-size:13px;text-align:left;width:1%}.main[_ngcontent-%COMP%]{border:2px solid #ccc;padding:10px;line-height:1.5;border-radius:3px;box-shadow:2px 4px 20px 2px rgba(202,199,199,.2),0 6px 10px 0 rgba(0,0,0,.19)}legend[_ngcontent-%COMP%]{display:block;width:auto;max-width:100%;padding:10px;margin-bottom:.5rem;font-size:19px;line-height:inherit;color:inherit;white-space:normal}.high-light[_ngcontent-%COMP%]{font-weight:700;font-size:19px;color:#1d578e}fieldset[_ngcontent-%COMP%]{min-width:0;padding:10px;margin:5px;border:1px solid grey}table.center[_ngcontent-%COMP%]{margin-left:5%;margin-right:5%;margin-bottom:5%}label[_ngcontent-%COMP%]{font-size:15px;text-align:center}"]],data:{}});function s(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,1,"td",[["style","background-color: #ccc; "]],null,null,null,null,null)),(t()(),o["\u0275ted"](1,null,["",""]))],null,(function(t,n){t(n,1,0,n.parent.context.$implicit.SHORT_FORM)}))}function r(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,1,"td",[],null,null,null,null,null)),(t()(),o["\u0275ted"](1,null,[" ","",""]))],null,(function(t,n){t(n,1,0,n.parent.context.$implicit.PARAMETER_VALUE,n.parent.context.$implicit.SYMBOL)}))}function u(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,4,"tr",[],null,null,null,null,null)),(t()(),o["\u0275and"](16777216,null,null,1,null,s)),o["\u0275did"](2,16384,null,0,i.n,[o.ViewContainerRef,o.TemplateRef],{ngIf:[0,"ngIf"]},null),(t()(),o["\u0275and"](16777216,null,null,1,null,r)),o["\u0275did"](4,16384,null,0,i.n,[o.ViewContainerRef,o.TemplateRef],{ngIf:[0,"ngIf"]},null)],(function(t,n){t(n,2,0,null!=n.context.$implicit.PARAMETER_VALUE),t(n,4,0,null!=n.context.$implicit.PARAMETER_VALUE)}),null)}function c(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,9,"div",[["class","col-3"]],null,null,null,null,null)),(t()(),o["\u0275eld"](1,0,null,null,8,"div",[["class","main"],["style","text-align:center;"]],null,null,null,null,null)),(t()(),o["\u0275eld"](2,0,null,null,3,"label",[["style","text-align: center"]],null,null,null,null,null)),(t()(),o["\u0275eld"](3,0,null,null,2,"b",[],null,null,null,null,null)),(t()(),o["\u0275eld"](4,0,null,null,1,"span",[["style","color: #1d578e;"]],null,null,null,null,null)),(t()(),o["\u0275ted"](5,null,[""," "])),(t()(),o["\u0275eld"](6,0,null,null,3,"table",[["class","table-bordered center"]],null,null,null,null,null)),(t()(),o["\u0275eld"](7,0,null,null,2,"tbody",[],null,null,null,null,null)),(t()(),o["\u0275and"](16777216,null,null,1,null,u)),o["\u0275did"](9,278528,null,0,i.m,[o.ViewContainerRef,o.TemplateRef,o.IterableDiffers],{ngForOf:[0,"ngForOf"]},null)],(function(t,n){t(n,9,0,n.context.$implicit.param_values)}),(function(t,n){t(n,5,0,n.component.formatDateTime(n.context.$implicit.DATE_TIME))}))}function a(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,3,"div",[["class","container"]],null,null,null,null,null)),(t()(),o["\u0275eld"](1,0,null,null,2,"div",[["class","row"]],null,null,null,null,null)),(t()(),o["\u0275and"](16777216,null,null,1,null,c)),o["\u0275did"](3,278528,null,0,i.m,[o.ViewContainerRef,o.TemplateRef,o.IterableDiffers],{ngForOf:[0,"ngForOf"]},null)],(function(t,n){t(n,3,0,n.component.vital_values)}),null)}},Wd0O:function(t,n,e){"use strict";e.d(n,"b",(function(){return l})),e.d(n,"a",(function(){return i}));var o=e("CcnG"),i=function(){function t(t){this.sanitizer=t}return t.prototype.transform=function(t,n){if("string"!=typeof t)return t;var e=t.replace(/(?:\r\n|\r|\n)/g,"<br />");return o.VERSION&&"2"!==o.VERSION.major?n?this.sanitizer.sanitize(o.SecurityContext.HTML,e):e:this.sanitizer.bypassSecurityTrustHtml(e)},t}(),l=function(){return function(){}}()},Xe8C:function(t,n,e){"use strict";e.d(n,"a",(function(){return o}));var o=function(){function t(){}return t.prototype.getAllStyles=function(t){return window.getComputedStyle(t)},t.prototype.getStyle=function(t,n){return this.getAllStyles(t)[n]},t.prototype.isStaticPositioned=function(t){return"static"===(this.getStyle(t,"position")||"static")},t.prototype.offsetParent=function(t){for(var n=t.offsetParent||document.documentElement;n&&n!==document.documentElement&&this.isStaticPositioned(n);)n=n.offsetParent;return n||document.documentElement},t.prototype.position=function(t,n){var e;void 0===n&&(n=!0);var o={width:0,height:0,top:0,bottom:0,left:0,right:0};if("fixed"===this.getStyle(t,"position"))e=t.getBoundingClientRect();else{var i=this.offsetParent(t);e=this.offset(t,!1),i!==document.documentElement&&(o=this.offset(i,!1)),o.top+=i.clientTop,o.left+=i.clientLeft}return e.top-=o.top,e.bottom-=o.top,e.left-=o.left,e.right-=o.left,n&&(e.top=Math.round(e.top),e.bottom=Math.round(e.bottom),e.left=Math.round(e.left),e.right=Math.round(e.right)),e},t.prototype.offset=function(t,n){void 0===n&&(n=!0);var e=t.getBoundingClientRect(),o=window.pageYOffset-document.documentElement.clientTop,i=window.pageXOffset-document.documentElement.clientLeft,l={height:e.height||t.offsetHeight,width:e.width||t.offsetWidth,top:e.top+o,bottom:e.bottom+o,left:e.left+i,right:e.right+i};return n&&(l.height=Math.round(l.height),l.width=Math.round(l.width),l.top=Math.round(l.top),l.bottom=Math.round(l.bottom),l.left=Math.round(l.left),l.right=Math.round(l.right)),l},t.prototype.positionElements=function(t,n,e,o){var i=o?this.offset(t,!1):this.position(t,!1),l=this.getAllStyles(n),s=n.getBoundingClientRect(),r=e.split("-")[0]||"top",u=e.split("-")[1]||"center",c={height:s.height||n.offsetHeight,width:s.width||n.offsetWidth,top:0,bottom:s.height||n.offsetHeight,left:0,right:s.width||n.offsetWidth};switch(r){case"top":c.top=i.top-(n.offsetHeight+parseFloat(l.marginBottom));break;case"bottom":c.top=i.top+i.height;break;case"left":c.left=i.left-(n.offsetWidth+parseFloat(l.marginRight));break;case"right":c.left=i.left+i.width}switch(u){case"top":c.top=i.top;break;case"bottom":c.top=i.top+i.height-n.offsetHeight;break;case"left":c.left=i.left;break;case"right":c.left=i.left+i.width-n.offsetWidth;break;case"center":"top"===r||"bottom"===r?c.left=i.left+i.width/2-n.offsetWidth/2:c.top=i.top+i.height/2-n.offsetHeight/2}return c.top=Math.round(c.top),c.bottom=Math.round(c.bottom),c.left=Math.round(c.left),c.right=Math.round(c.right),c},t.prototype.getAvailablePlacements=function(t,n){var e=[],o=t.getBoundingClientRect(),i=n.getBoundingClientRect(),l=document.documentElement,s=window.innerHeight||l.clientHeight,r=window.innerWidth||l.clientWidth,u=o.left+o.width/2,c=o.top+o.height/2;return i.width<o.left&&(c>i.height/2&&s-c>i.height/2&&e.splice(e.length,1,"left"),this.setSecondaryPlacementForLeftRight(o,i,"left",e)),i.height<o.top&&(u>i.width/2&&r-u>i.width/2&&e.splice(e.length,1,"top"),this.setSecondaryPlacementForTopBottom(o,i,"top",e)),r-o.right>i.width&&(c>i.height/2&&s-c>i.height/2&&e.splice(e.length,1,"right"),this.setSecondaryPlacementForLeftRight(o,i,"right",e)),s-o.bottom>i.height&&(u>i.width/2&&r-u>i.width/2&&e.splice(e.length,1,"bottom"),this.setSecondaryPlacementForTopBottom(o,i,"bottom",e)),e},t.prototype.setSecondaryPlacementForLeftRight=function(t,n,e,o){var i=document.documentElement;n.height<=t.bottom&&o.splice(o.length,1,e+"-bottom"),(window.innerHeight||i.clientHeight)-t.top>=n.height&&o.splice(o.length,1,e+"-top")},t.prototype.setSecondaryPlacementForTopBottom=function(t,n,e,o){var i=document.documentElement;(window.innerWidth||i.clientWidth)-t.left>=n.width&&o.splice(o.length,1,e+"-left"),n.width<=t.right&&o.splice(o.length,1,e+"-right")},t}();new o},fCu2:function(t,n,e){"use strict";e.d(n,"a",(function(){return d})),e.d(n,"e",(function(){return s})),e.d(n,"g",(function(){return r})),e.d(n,"f",(function(){return u})),e.d(n,"d",(function(){return c})),e.d(n,"b",(function(){return p})),e.d(n,"c",(function(){return h})),e.d(n,"h",(function(){return a}));var o=e("mrSG"),i=e("CcnG"),l=e("Xe8C"),s=function(){return function(){this.confirmText="Confirm",this.cancelText="Cancel",this.confirmButtonType="success",this.cancelButtonType="default",this.placement="top",this.hideConfirmButton=!1,this.hideCancelButton=!1,this.popoverClass="",this.appendToBody=!1,this.reverseButtonOrder=!1,this.closeOnOutsideClick=!0}}(),r=function(t){function n(){return null!==t&&t.apply(this,arguments)||this}return Object(o.c)(n,t),n}(s),u=function(){function t(t){this.options=t}return t.prototype.ngAfterViewInit=function(){this.options.onAfterViewInit()},t}(),c=function(){function t(t,n,e,o,l,s){this.viewContainerRef=t,this.elm=n,this.defaultOptions=e,this.cfr=o,this.position=l,this.renderer=s,this.isDisabled=!1,this.isOpen=!1,this.isOpenChange=new i.EventEmitter(!0),this.confirm=new i.EventEmitter,this.cancel=new i.EventEmitter,this.eventListeners=[]}return t.prototype.ngOnInit=function(){this.isOpenChange.emit(!1)},t.prototype.ngOnChanges=function(t){t.isOpen&&(!0===t.isOpen.currentValue?this.showPopover():this.hidePopover())},t.prototype.ngOnDestroy=function(){this.hidePopover()},t.prototype.onConfirm=function(t){this.confirm.emit(t),this.hidePopover()},t.prototype.onCancel=function(t){this.cancel.emit(t),this.hidePopover()},t.prototype.togglePopover=function(){this.popover?this.hidePopover():this.showPopover()},t.prototype.onDocumentClick=function(t){var n=void 0!==this.closeOnOutsideClick?this.closeOnOutsideClick:this.defaultOptions.closeOnOutsideClick;this.popover&&!this.elm.nativeElement.contains(t.target)&&!this.popover.location.nativeElement.contains(t.target)&&n&&this.hidePopover()},t.prototype.showPopover=function(){var t=this;if(!this.popover&&!this.isDisabled){setTimeout((function(){t.eventListeners=[t.renderer.listen("document","click",(function(n){return t.onDocumentClick(n)})),t.renderer.listen("document","touchend",(function(n){return t.onDocumentClick(n)})),t.renderer.listen("window","resize",(function(){return t.positionPopover()}))]}));var n=new r;Object.assign(n,this.defaultOptions,{onConfirm:function(n){t.onConfirm(n)},onCancel:function(n){t.onCancel(n)},onAfterViewInit:function(){t.positionPopover()}}),["confirmText","cancelText","placement","confirmButtonType","cancelButtonType","focusButton","hideConfirmButton","hideCancelButton","popoverClass","appendToBody","customTemplate","reverseButtonOrder","popoverTitle","popoverMessage"].forEach((function(e){void 0!==t[e]&&(n[e]=t[e])}));var e=this.cfr.resolveComponentFactory(u),o=i.Injector.create([{provide:r,useValue:n}],this.viewContainerRef.parentInjector);this.popover=this.viewContainerRef.createComponent(e,this.viewContainerRef.length,o),n.appendToBody&&document.body.appendChild(this.popover.location.nativeElement),this.isOpenChange.emit(!0)}},t.prototype.positionPopover=function(){if(this.popover){var t=this.popover.location.nativeElement.children[0],n=this.position.positionElements(this.elm.nativeElement,t,this.placement||this.defaultOptions.placement,this.appendToBody||this.defaultOptions.appendToBody);this.renderer.setStyle(t,"top",n.top+"px"),this.renderer.setStyle(t,"left",n.left+"px")}},t.prototype.hidePopover=function(){this.popover&&(this.popover.destroy(),delete this.popover,this.isOpenChange.emit(!1),this.eventListeners.forEach((function(t){return t()})),this.eventListeners=[])},t}(),a=function(){function t(t){this.elm=t}return t.prototype.ngOnChanges=function(t){t.mwlFocus&&!0===this.mwlFocus&&this.elm.nativeElement.focus()},t}(),p=new i.InjectionToken("confirmation popover user options");function h(t){var n=new s;return Object.assign(n,t),n}var d=function(){function t(){}return t.forRoot=function(n){return void 0===n&&(n={}),{ngModule:t,providers:[{provide:p,useValue:n},{provide:s,useFactory:h,deps:[p]},l.a]}},t}()},lTVM:function(t,n,e){"use strict";e.d(n,"a",(function(){return l})),e("ZF+8");var o=e("T9/9"),i=e("/RaO"),l=(e("CGbT"),function(){function t(t,n,e,o){this.datepipe=t,this.router=n,this.rest2=e,this.assessment_id=0,this.patient_id=0,this.vital_values=[],this.blood_sugar=[],this.user_rights={},this.user_data={},this.vital_params=[],this.param_values=[],this.vital_form_values=[],this.dateVal=new Date,this.assessment_entry_id=0,this.notifier=o}return t.prototype.ngOnInit=function(){this.user_rights=JSON.parse(localStorage.getItem("user_rights")),this.user_data=JSON.parse(localStorage.getItem("user"))},t.prototype.ngOnChanges=function(t){this.user_rights=JSON.parse(localStorage.getItem("user_rights")),this.user_data=JSON.parse(localStorage.getItem("user"))},t.prototype.formatTime=function(t){return Object(o.e)(t)},t.prototype.formatDate=function(t){return Object(o.c)(t)},t.prototype.formatDateTime=function(t){return Object(o.d)(t)},t.prototype.getAssesmentParameters=function(){var t=this;this.rest2.getAssesmentParameters({test_methode:i.a.NURSING_ASSESMENT}).subscribe((function(n){"Success"==n.status&&(t.vital_params=n.data)}),(function(t){console.log(t)}))},t}())},pymk:function(t,n,e){"use strict";e.d(n,"a",(function(){return c}));var o=e("CcnG"),i=(e("ZF+8"),e("T9/9")),l=(e("CGbT"),e("/RaO")),s=e("f0Wu"),r=e.n(s),u=e("4GxJ"),c=function(){function t(t,n,e,i,s,r){this.modalService=t,this.datepipe=n,this.router=e,this.opv=i,this.rest2=s,this.assessment_id=0,this.patient_id=0,this.selected_visit=[],this.user_id=0,this.onEvent=new o.EventEmitter,this.save_notify=0,this.finishAssessment=new o.EventEmitter,this.showDiscount=new o.EventEmitter,this.blood_sugar=[],this.settings=l.a,this.assessment_menu_list=[],this.loading=!1,this.menu_list=[],this.visit_list=[],this.user_rights={},this.vital_params=[],this.vital_values=[],this.vital_form_values=[],this.dateVal=new Date,this.assessment_entry_id=0,this.notifier=r}return t.prototype.ngOnInit=function(){if(this.user_rights=JSON.parse(localStorage.getItem("user_rights")),this.doctor_department=JSON.parse(localStorage.getItem("doctor_department")),this.doctor_department.length>0)for(var t=0,n=this.doctor_department;t<n.length;t++){var e=n[t];8==e.OPTIONS_TYPE&&43==e.DEPARTMENT_ID&&(this.department=1)}this.getAssesmentParameterValues(this.patient_id,this.assessment_id),this.get_sub_modules(),this.getBloodSugarReport(),this.time=r.a.tz.guess()},t.prototype.ngOnChanges=function(t){this.user_rights=JSON.parse(localStorage.getItem("user_rights")),this.getAssesmentParameterValues(this.patient_id,this.assessment_id),this.getBloodSugarReport()},t.prototype.formatTime=function(t){return Object(i.e)(t)},t.prototype.formatDate=function(t){return Object(i.c)(t)},t.prototype.formatDateTime=function(t){return Object(i.d)(t)},t.prototype.setAssessment=function(t){this.assessment_id=t},t.prototype.setPatient=function(t){this.patient_id=t},t.prototype.get_sub_modules=function(){var t=this,n={module_id:l.a.DOCTOR_ASSESMENT_MENU};this.loading=!0,this.rest2.get_sub_modules(n).subscribe((function(n){"Success"==n.status?(t.loading=!1,t.menu_list=n.menu_list,t.UserTab=t.menu_list[1],t.UserTab.MENUS[0]&&(t.selectedUserTab=t.UserTab.MENUS[0])):t.menu_list=[]}),(function(t){}))},t.prototype.getEvent=function(t){console.log(t),this.onEvent.emit()},t.prototype.getAssesmentParameterValues=function(t,n){var e=this;void 0===t&&(t=0),void 0===n&&(n=0);var o={patient_id:this.patient_id,assessment_id:this.assessment_id};this.loading=!0,this.rest2.getAssesmentParameterValues(o).subscribe((function(t){"Success"==t.status?(e.loading=!1,e.vital_values=t.data):e.vital_values=t.data}),(function(t){}))},t.prototype.getVisitListByDate=function(){var t=this,n={dateVal:this.dateVal,timeZone:this.time};this.loading=!0,this.opv.getVisitListByDate(n).subscribe((function(n){"Success"==n.status?(t.loading=!1,t.visit_list=n.data):(t.loading=!1,t.visit_list=[])}),(function(t){}))},t.prototype.getBloodSugarReport=function(){var t=this;this.rest2.getBloodSugarReport({patient_id:this.patient_id,assessment_id:this.assessment_id}).subscribe((function(n){t.blood_sugar=n.data}),(function(t){console.log(t)}))},t.prototype.change=function(t){this.UserTab=t==this.UserTab?0:t},t.prototype.tabchange=function(t,n){var e=this;this.selectedTab=t,t==this.UserTab?this.selectedUserTab=0:1==this.save_notify?this.modalService.open(n,{ariaLabelledBy:"modal-basic-title",size:"sm",centered:!0}).result.then((function(t){e.closeResult="Closed with: "+t}),(function(t){e.closeResult="Dismissed "+e.getDismissReason(t)})):this.selectedUserTab=t},t.prototype.getDismissReason=function(t){return t===u.a.ESC?"by pressing ESC":t===u.a.BACKDROP_CLICK?"by clicking on a backdrop":"with: "+t},t.prototype.changetab=function(){this.selectedUserTab=this.selectedTab,this.save_notify=0},t.prototype.OnApplyNotify=function(t){this.save_notify=t},t.prototype.OnFinish=function(){this.finishAssessment.emit(1)},t.prototype.ShowDiscount=function(){this.showDiscount.emit(1)},t}()},uUQW:function(t,n,e){"use strict";e.d(n,"a",(function(){return d}));var o=e("CcnG"),i=e("fCu2"),l=e("Ip0R"),s=o["\u0275crt"]({encapsulation:0,styles:[".popover[_ngcontent-%COMP%] {\n      display: block;\n    }\n    .bs-popover-top[_ngcontent-%COMP%]   .arrow[_ngcontent-%COMP%], .bs-popover-bottom[_ngcontent-%COMP%]   .arrow[_ngcontent-%COMP%] {\n      left: 50%;\n    }\n    .bs-popover-left[_ngcontent-%COMP%]   .arrow[_ngcontent-%COMP%], .bs-popover-right[_ngcontent-%COMP%]   .arrow[_ngcontent-%COMP%] {\n      top: 50%;\n    }\n    .btn[_ngcontent-%COMP%] {\n      transition: none;\n    }\n    .confirm-btns[_ngcontent-%COMP%] {\n      display: flex;\n      justify-content: space-around;\n    }\n    .confirm-btn-container[_ngcontent-%COMP%] {\n      flex-basis: 50%;\n    }\n    .confirm-btn-container[_ngcontent-%COMP%]:not(:first-child) {\n      margin-left: 4px;\n    }\n    .confirm-btn-container[_ngcontent-%COMP%]:not(:last-child) {\n      margin-right: 4px;\n    }\n    .confirm-btns-reversed[_ngcontent-%COMP%] {\n      flex-direction: row-reverse;\n    }\n    .confirm-btns-reversed[_ngcontent-%COMP%]   .confirm-btn-container[_ngcontent-%COMP%]:not(:first-child) {\n      margin-right: 4px;\n      margin-left: 0;\n    }\n    .confirm-btns-reversed[_ngcontent-%COMP%]   .confirm-btn-container[_ngcontent-%COMP%]:not(:last-child) {\n      margin-right: 0;\n      margin-left: 4px;\n    }"],data:{}});function r(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,2,"div",[["class","confirm-btn-container"]],null,null,null,null,null)),(t()(),o["\u0275eld"](1,0,null,null,1,"button",[["type","button"]],[[8,"className",0],[8,"innerHTML",1]],[[null,"click"]],(function(t,n,e){var o=!0;return"click"===n&&(o=!1!==t.parent.context.options.onCancel({clickEvent:e})&&o),o}),null,null)),o["\u0275did"](2,540672,null,0,i.h,[o.ElementRef],{mwlFocus:[0,"mwlFocus"]},null)],(function(t,n){t(n,2,0,"cancel"===n.parent.context.options.focusButton)}),(function(t,n){t(n,1,0,"btn btn-block btn-"+n.parent.context.options.cancelButtonType,n.parent.context.options.cancelText)}))}function u(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,2,"div",[["class","confirm-btn-container"]],null,null,null,null,null)),(t()(),o["\u0275eld"](1,0,null,null,1,"button",[["type","button"]],[[8,"className",0],[8,"innerHTML",1]],[[null,"click"]],(function(t,n,e){var o=!0;return"click"===n&&(o=!1!==t.parent.context.options.onConfirm({clickEvent:e})&&o),o}),null,null)),o["\u0275did"](2,540672,null,0,i.h,[o.ElementRef],{mwlFocus:[0,"mwlFocus"]},null)],(function(t,n){t(n,2,0,"confirm"===n.parent.context.options.focusButton)}),(function(t,n){t(n,1,0,"btn btn-block btn-"+n.parent.context.options.confirmButtonType,n.parent.context.options.confirmText)}))}function c(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,11,"div",[],null,null,null,null,null)),o["\u0275did"](1,278528,null,0,l.l,[o.IterableDiffers,o.KeyValueDiffers,o.ElementRef,o.Renderer2],{ngClass:[0,"ngClass"]},null),o["\u0275pad"](2,5),(t()(),o["\u0275eld"](3,0,null,null,0,"div",[["class","popover-arrow arrow"]],null,null,null,null,null)),(t()(),o["\u0275eld"](4,0,null,null,0,"h3",[["class","popover-title popover-header"]],[[8,"innerHTML",1]],null,null,null,null)),(t()(),o["\u0275eld"](5,0,null,null,6,"div",[["class","popover-content popover-body"]],null,null,null,null,null)),(t()(),o["\u0275eld"](6,0,null,null,0,"p",[],[[8,"innerHTML",1]],null,null,null,null)),(t()(),o["\u0275eld"](7,0,null,null,4,"div",[["class","confirm-btns"]],[[2,"confirm-btns-reversed",null]],null,null,null,null)),(t()(),o["\u0275and"](16777216,null,null,1,null,r)),o["\u0275did"](9,16384,null,0,l.n,[o.ViewContainerRef,o.TemplateRef],{ngIf:[0,"ngIf"]},null),(t()(),o["\u0275and"](16777216,null,null,1,null,u)),o["\u0275did"](11,16384,null,0,l.n,[o.ViewContainerRef,o.TemplateRef],{ngIf:[0,"ngIf"]},null)],(function(t,n){var e=t(n,2,0,"popover",n.context.options.placement,"popover-"+n.context.options.placement,"bs-popover-"+n.context.options.placement,n.context.options.popoverClass);t(n,1,0,e),t(n,9,0,!n.context.options.hideCancelButton),t(n,11,0,!n.context.options.hideConfirmButton)}),(function(t,n){t(n,4,0,n.context.options.popoverTitle),t(n,6,0,n.context.options.popoverMessage),t(n,7,0,n.context.options.reverseButtonOrder)}))}function a(t){return o["\u0275vid"](0,[(t()(),o["\u0275and"](0,null,null,0))],null,null)}function p(t){return o["\u0275vid"](0,[(t()(),o["\u0275and"](0,[["defaultTemplate",2]],null,0,null,c)),(t()(),o["\u0275and"](16777216,null,null,2,null,a)),o["\u0275did"](2,540672,null,0,l.u,[o.ViewContainerRef],{ngTemplateOutletContext:[0,"ngTemplateOutletContext"],ngTemplateOutlet:[1,"ngTemplateOutlet"]},null),o["\u0275pod"](3,{options:0})],(function(t,n){var e=n.component,i=t(n,3,0,e.options);t(n,2,0,i,e.options.customTemplate||o["\u0275nov"](n,0))}),null)}function h(t){return o["\u0275vid"](0,[(t()(),o["\u0275eld"](0,0,null,null,1,"mwl-confirmation-popover-window",[],null,null,null,p,s)),o["\u0275did"](1,4243456,null,0,i.f,[i.g],null,null)],null,null)}var d=o["\u0275ccf"]("mwl-confirmation-popover-window",i.f,h,{},{},[])}}]);