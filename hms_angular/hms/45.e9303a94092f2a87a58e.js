(window.webpackJsonp = window.webpackJsonp || []).push([[45], { "+Yb1": function (e, t, n) { "use strict"; n.r(t); var o = n("CcnG"), r = function () { return function () { } }(), a = n("pMnS"), i = n("ZYCi"), l = n("Ip0R"), s = n("Zseb"), u = (n("M0ag"), n("/RaO")), d = (n("MO+k"), n("wd/R")), c = n("xXU7"), p = function () { function e(e) { this.rest = e, this.user_rights = {}, this.setting = u.a, this.userCredentials = JSON.parse(localStorage.getItem("user")), this.now = new Date, this.user_group = this.userCredentials.user_group, this.user_id = this.userCredentials.user_id, this.per = [], this.pieChartOptions = { responsive: !0, legend: { position: "right" }, plugins: { datasets: { formatter: function (e, t) { return t.chart.data.datasets[t.dataIndex] } } } }, this.pieChartLabels = [], this.chartColors = [{ backgroundColor: ["#52D726", "#FFEC00", "#FF7300", "#FF0000", "#007ED6", "#7CDDDD"] }], this.chartColor = [{ backgroundColor: [] }], this.pieChartData = [], this.pieChartType = "pie", this.count = [], this.source = Object(c.a)(5e3) } return e.prototype.ngOnInit = function () { var e = this; this.loadModules(), this.getModuleSummary(), this.formatDateTime(this.now), this.subscription = this.source.subscribe((function (t) { return e.getModuleSummary() })), this.user_rights = JSON.parse(localStorage.getItem("user_rights")) }, e.prototype.ngOnDestroy = function () { this.subscription && this.subscription.unsubscribe() }, e.prototype.formatDateTime = function (e) { if (this.now && (this.date = d(this.now, "yyyy-MM-D HH:mm.ss a").format("D-MM-Y HH:MM:ss")), e) return d(e, "yyyy-MM-D HH:mm.ss a").format("D-MM-Y HH:MM:ss") }, e.prototype.loadModules = function () { var e = this; this.rest.getModules({ user_group: this.userCredentials.user_login }).subscribe((function (t) { window.scrollTo(0, 0), "Success" == t.status && (e.menulist = t.data, localStorage.setItem("modules", JSON.stringify(t.data))) }), (function (e) { console.log(e) })) }, e.prototype.getModuleSummary = function () { var e = this, t = { user_group: 0, user_id: this.user_id, date: this.formatDateTime(this.now) }; this.rest.getModuleSummary(t).subscribe((function (t) { "Success" == t.status && (e.pieChartData = t.data.COUNT, e.pieChartLabels = t.data.NAME, 1 == t.data.COUNT && (e.pieChartData = [1], e.chartColors = e.chartColor)) }), (function (e) { console.log(e) })) }, e }(), h = n("ZXMZ"), f = o["\u0275crt"]({ encapsulation: 0, styles: [[".main[_ngcontent-%COMP%]{border:2px solid #ccc;padding:30px;line-height:1.5;border-radius:0;height:40%;box-shadow:2px 4px 20px 2px rgba(202,199,199,.2),0 6px 10px 0 rgba(0,0,0,.19)}span[_ngcontent-%COMP%]{cursor:pointer;text-align:center;display:block;outline:#ccc solid 1px;padding:20px;line-height:1.5;border-radius:0;width:180px;height:150px;box-shadow:2px 4px 20px 2px rgba(202,199,199,.2),0 6px 10px 0 rgba(0,0,0,.19);margin:17px 20px}a[_ngcontent-%COMP%]{color:#1d578e}label[_ngcontent-%COMP%]{font-size:14px;text-align:center;font-weight:700}i[_ngcontent-%COMP%]{padding-top:20px}.fa[_ngcontent-%COMP%]{font-size:50px;font-weight:lighter}span[_ngcontent-%COMP%]:hover{transform:scale(1.1)}"]], data: {} }); function m(e) { return o["\u0275vid"](0, [(e()(), o["\u0275eld"](0, 0, null, null, 8, "div", [["class", "col-3"]], null, null, null, null, null)), (e()(), o["\u0275eld"](1, 0, null, null, 7, "a", [["class", "router-link-active ex1"], ["href", "javascript:void(0)"]], [[1, "target", 0], [8, "href", 4]], [[null, "click"]], (function (e, t, n) { var r = !0; return "click" === t && (r = !1 !== o["\u0275nov"](e, 2).onClick(n.button, n.ctrlKey, n.metaKey, n.shiftKey) && r), r }), null, null)), o["\u0275did"](2, 671744, null, 0, i.n, [i.l, i.a, l.k], { routerLink: [0, "routerLink"] }, null), o["\u0275pad"](3, 1), (e()(), o["\u0275eld"](4, 0, null, null, 4, "span", [["style", "background:#ffffff;"]], null, null, null, null, null)), (e()(), o["\u0275eld"](5, 0, null, null, 1, "label", [["style", "color: #000000"]], null, null, null, null, null)), (e()(), o["\u0275ted"](6, null, ["", ""])), (e()(), o["\u0275eld"](7, 0, null, null, 1, "div", [["style", "text-align: center"]], null, null, null, null, null)), (e()(), o["\u0275eld"](8, 0, null, null, 0, "i", [["style", "font-size: 55px;padding-top:10px;background:#ffffff"]], [[8, "className", 0]], null, null, null, null))], (function (e, t) { var n = e(t, 3, 0, t.parent.parent.context.$implicit.MODULE_GROUP_PATH + t.context.$implicit.MODULE_PATH); e(t, 2, 0, n) }), (function (e, t) { e(t, 1, 0, o["\u0275nov"](t, 2).target, o["\u0275nov"](t, 2).href), e(t, 6, 0, t.context.$implicit.MODULE_NAME), e(t, 8, 0, o["\u0275inlineInterpolate"](1, "", t.context.$implicit.MODULE_ICON, "")) })) } function v(e) { return o["\u0275vid"](0, [(e()(), o["\u0275eld"](0, 0, null, null, 2, null, null, null, null, null, null, null)), (e()(), o["\u0275and"](16777216, null, null, 1, null, m)), o["\u0275did"](2, 278528, null, 0, l.m, [o.ViewContainerRef, o.TemplateRef, o.IterableDiffers], { ngForOf: [0, "ngForOf"] }, null), (e()(), o["\u0275and"](0, null, null, 0))], (function (e, t) { e(t, 2, 0, t.parent.context.$implicit.sub_menu) }), null) } function g(e) { return o["\u0275vid"](0, [(e()(), o["\u0275eld"](0, 0, null, null, 2, null, null, null, null, null, null, null)), (e()(), o["\u0275and"](16777216, null, null, 1, null, v)), o["\u0275did"](2, 16384, null, 0, l.n, [o.ViewContainerRef, o.TemplateRef], { ngIf: [0, "ngIf"] }, null), (e()(), o["\u0275and"](0, null, null, 0))], (function (e, t) { e(t, 2, 0, t.context.$implicit.MODULE_GROUP_ID != t.component.setting.DASHBOARD_MODULE_GROUP_ID) }), null) } function C(e) { return o["\u0275vid"](0, [(e()(), o["\u0275eld"](0, 0, null, null, 6, "div", [["class", "row container"]], null, null, null, null, null)), (e()(), o["\u0275eld"](1, 0, null, null, 3, "div", [["class", "col-6"]], null, null, null, null, null)), (e()(), o["\u0275eld"](2, 0, null, null, 2, "div", [["style", "width:500px"]], null, null, null, null, null)), (e()(), o["\u0275eld"](3, 0, null, null, 1, "canvas", [["baseChart", ""]], null, null, null, null, null)), o["\u0275did"](4, 999424, null, 0, s.a, [o.ElementRef, s.c], { data: [0, "data"], labels: [1, "labels"], options: [2, "options"], chartType: [3, "chartType"], colors: [4, "colors"] }, null), (e()(), o["\u0275and"](16777216, null, null, 1, null, g)), o["\u0275did"](6, 278528, null, 0, l.m, [o.ViewContainerRef, o.TemplateRef, o.IterableDiffers], { ngForOf: [0, "ngForOf"] }, null)], (function (e, t) { var n = t.component; e(t, 4, 0, n.pieChartData, n.pieChartLabels, n.pieChartOptions, n.pieChartType, n.chartColors), e(t, 6, 0, n.menulist) }), null) } function y(e) { return o["\u0275vid"](0, [(e()(), o["\u0275eld"](0, 0, null, null, 1, "app-dashboards", [], null, null, null, C, f)), o["\u0275did"](1, 245760, null, 0, p, [h.a], null, null)], (function (e, t) { e(t, 1, 0) }), null) } var M = o["\u0275ccf"]("app-dashboards", p, y, {}, {}, []), b = n("9AJC"), _ = n("xkgV"), x = n("gIcY"), O = n("4GxJ"), k = n("t/Na"), w = { level: 2, path: "/dr-consultation" }, T = { level: 2, path: "/dr-consultation" }, A = { level: 2, path: "/dr-schedule-list" }, P = { level: 2, path: "/doctors" }, E = { level: 2, path: "/diagnosis" }, S = { level: 2, path: "/medicine" }, D = { level: 2, path: "/vaccine" }, R = { level: 2, path: "/current-procedural-terminology" }, I = { level: 2, path: "/op-new-registration" }, N = { level: 2, path: "/op-new-registration" }, F = { level: 2, path: "/op-new-registration" }, j = { level: 2, path: "/appointment" }, V = { level: 2, path: "/op-visit-entry" }, U = { level: 2, path: "/pre-consulting" }, L = { level: 2, path: "/consulting" }, H = { level: 2, path: "/billing" }, J = { level: 2, path: "/insurance" }, G = { level: 2, path: "/patient-query" }, K = { level: 2, path: "/appointment-list" }, $ = { level: 2, path: "/user-access" }, q = { level: 2, path: "/user-management" }, B = function () { return function () { } }(), X = n("A7o+"), Y = n("+Sv0"), Z = n("UVXo"), z = n("5NQ/"), W = n("RygT"), Q = n("eRTK"); n.d(t, "DashboardsModuleNgFactory", (function () { return ee })); var ee = o["\u0275cmf"](r, [], (function (e) { return o["\u0275mod"]([o["\u0275mpd"](512, o.ComponentFactoryResolver, o["\u0275CodegenComponentFactoryResolver"], [[8, [a.a, M, b.a, b.b, b.y, b.u, b.v, b.w, b.x]], [3, o.ComponentFactoryResolver], o.NgModuleRef]), o["\u0275mpd"](4608, l.p, l.o, [o.LOCALE_ID, [2, l.H]]), o["\u0275mpd"](4608, _.b, _.b, []), o["\u0275mpd"](4608, x["\u0275angular_packages_forms_forms_j"], x["\u0275angular_packages_forms_forms_j"], []), o["\u0275mpd"](4608, O.z, O.z, [o.ComponentFactoryResolver, o.Injector, O.nb, O.A]), o["\u0275mpd"](4608, x.FormBuilder, x.FormBuilder, []), o["\u0275mpd"](4608, k.j, k.p, [l.c, o.PLATFORM_ID, k.n]), o["\u0275mpd"](4608, k.q, k.q, [k.j, k.o]), o["\u0275mpd"](5120, k.a, (function (e) { return [e] }), [k.q]), o["\u0275mpd"](4608, k.m, k.m, []), o["\u0275mpd"](6144, k.k, null, [k.m]), o["\u0275mpd"](4608, k.i, k.i, [k.k]), o["\u0275mpd"](6144, k.b, null, [k.i]), o["\u0275mpd"](4608, k.f, k.l, [k.b, o.Injector]), o["\u0275mpd"](4608, k.c, k.c, [k.f]), o["\u0275mpd"](1073742336, l.b, l.b, []), o["\u0275mpd"](1073742336, i.o, i.o, [[2, i.u], [2, i.l]]), o["\u0275mpd"](1073742336, B, B, []), o["\u0275mpd"](1073742336, X.g, X.g, []), o["\u0275mpd"](1073742336, O.w, O.w, []), o["\u0275mpd"](1073742336, _.a, _.a, []), o["\u0275mpd"](1073742336, O.d, O.d, []), o["\u0275mpd"](1073742336, O.g, O.g, []), o["\u0275mpd"](1073742336, O.h, O.h, []), o["\u0275mpd"](1073742336, O.l, O.l, []), o["\u0275mpd"](1073742336, O.n, O.n, []), o["\u0275mpd"](1073742336, x["\u0275angular_packages_forms_forms_bc"], x["\u0275angular_packages_forms_forms_bc"], []), o["\u0275mpd"](1073742336, x.FormsModule, x.FormsModule, []), o["\u0275mpd"](1073742336, O.t, O.t, []), o["\u0275mpd"](1073742336, O.B, O.B, []), o["\u0275mpd"](1073742336, O.F, O.F, []), o["\u0275mpd"](1073742336, O.L, O.L, []), o["\u0275mpd"](1073742336, O.O, O.O, []), o["\u0275mpd"](1073742336, O.S, O.S, []), o["\u0275mpd"](1073742336, O.Z, O.Z, []), o["\u0275mpd"](1073742336, O.db, O.db, []), o["\u0275mpd"](1073742336, O.gb, O.gb, []), o["\u0275mpd"](1073742336, O.jb, O.jb, []), o["\u0275mpd"](1073742336, O.C, O.C, []), o["\u0275mpd"](1073742336, Y.a, Y.a, []), o["\u0275mpd"](1073742336, Z.TextMaskModule, Z.TextMaskModule, []), o["\u0275mpd"](1073742336, x.ReactiveFormsModule, x.ReactiveFormsModule, []), o["\u0275mpd"](1073742336, z.b, z.b, []), o["\u0275mpd"](1073742336, W.b, W.b, []), o["\u0275mpd"](1073742336, k.e, k.e, []), o["\u0275mpd"](1073742336, k.d, k.d, []), o["\u0275mpd"](1073742336, s.b, s.b, []), o["\u0275mpd"](1073742336, r, r, []), o["\u0275mpd"](1024, i.j, (function () { return [[{ path: "", children: [{ path: "", component: p }, { path: "dr-consultation", loadChildren: "../master/dr-consultation/dr-consultation.module#DrConsultationModule", canActivate: [Q.a], data: w }, { path: "dr-consultation/:type/:id", loadChildren: "../master/dr-consultation/dr-consultation.module#DrConsultationModule", canActivate: [Q.a], data: T }, { path: "dr-schedule-list", loadChildren: "../master/dr-schedule-list/dr-schedule-list.module#DrScheduleListModule", canActivate: [Q.a], data: A }, { path: "doctors", loadChildren: "../master/doctors/doctors.module#DoctorsModule", canActivate: [Q.a], data: P }, { path: "diagnosis", loadChildren: "../master/diagnosis/diagnosis.module#DiagnosisModule", canActivate: [Q.a], data: E }, { path: "medicine", loadChildren: "../master/medicine/medicine.module#MedicineModule", canActivate: [Q.a], data: S }, { path: "vaccine", loadChildren: "../master/vaccine/vaccine.module#VaccineModule", canActivate: [Q.a], data: D }, { path: "current-procedural-terminology", loadChildren: "../master/current-procedural-terminology/current-procedural-terminology.module#CurrentProceduralTerminologyModule", canActivate: [Q.a], data: R }, { path: "op-new-registration", loadChildren: "../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule", canActivate: [Q.a], data: I }, { path: "op-new-registration/:app_id", loadChildren: "../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule", canActivate: [Q.a], data: N }, { path: "op-new-registration/:/:id", loadChildren: "../transaction/op-new-registration/op-new-registration.module#OpNewRegistrationModule", canActivate: [Q.a], data: F }, { path: "appointment", loadChildren: "../transaction/appointment/appointment.module#AppointmentModule", canActivate: [Q.a], data: j }, { path: "op-visit-entry", loadChildren: "../transaction/op-visit-entry/op-visit-entry.module#OpVisitEntryModule", canActivate: [Q.a], data: V }, { path: "pre-consulting", loadChildren: "../transaction/pre-consulting/pre-consulting.module#PreConsultingModule", canActivate: [Q.a], data: U }, { path: "consulting", loadChildren: "../transaction/consulting/consulting.module#ConsultingModule", canActivate: [Q.a], data: L }, { path: "billing", loadChildren: "../transaction/billing/billing.module#BillingModule", canActivate: [Q.a], data: H }, { path: "insurance", loadChildren: "../claim-process/insurance/insurance.module#InsuranceModule", canActivate: [Q.a], data: J }, { path: "patient-query", canActivate: [Q.a], data: G }, { path: "appointment-list", canActivate: [Q.a], data: K }, { path: "user-access", loadChildren: "../settings/user-access/user-access.module#UserAccessModule", canActivate: [Q.a], data: $ }, { path: "user-management", loadChildren: "../settings/user-management/user-management.module#UserManagementModule", canActivate: [Q.a], data: q }], runGuardsAndResolvers: "always" }]] }), []), o["\u0275mpd"](256, k.n, "XSRF-TOKEN", []), o["\u0275mpd"](256, k.o, "X-XSRF-TOKEN", [])]) })) }, FpO7: function (e, t, n) { e.exports = function (e) { function t(o) { if (n[o]) return n[o].exports; var r = n[o] = { exports: {}, id: o, loaded: !1 }; return e[o].call(r.exports, r, r.exports, t), r.loaded = !0, r.exports } var n = {}; return t.m = e, t.c = n, t.p = "", t(0) }([function (e, t, n) { "use strict"; function o(e) { return e && e.__esModule ? e : { default: e } } Object.defineProperty(t, "__esModule", { value: !0 }); var r = n(3); Object.defineProperty(t, "conformToMask", { enumerable: !0, get: function () { return o(r).default } }); var a = n(2); Object.defineProperty(t, "adjustCaretPosition", { enumerable: !0, get: function () { return o(a).default } }); var i = n(5); Object.defineProperty(t, "createTextMaskInputElement", { enumerable: !0, get: function () { return o(i).default } }) }, function (e, t) { "use strict"; Object.defineProperty(t, "__esModule", { value: !0 }), t.placeholderChar = "_", t.strFunction = "function" }, function (e, t) { "use strict"; Object.defineProperty(t, "__esModule", { value: !0 }), t.default = function (e) { var t = e.previousConformedValue, r = void 0 === t ? o : t, a = e.previousPlaceholder, i = void 0 === a ? o : a, l = e.currentCaretPosition, s = void 0 === l ? 0 : l, u = e.conformedValue, d = e.rawValue, c = e.placeholderChar, p = e.placeholder, h = e.indexesOfPipedChars, f = void 0 === h ? n : h, m = e.caretTrapIndexes, v = void 0 === m ? n : m; if (0 === s || !d.length) return 0; var g = r.length, C = p.length, y = u.length, M = d.length - g, b = M > 0; if (M > 1 && !b && 0 !== g) return s; var _ = 0, x = void 0, O = void 0; if (!b || r !== u && u !== p) { var k = u.toLowerCase(), w = d.toLowerCase().substr(0, s).split(o).filter((function (e) { return -1 !== k.indexOf(e) })); O = w[w.length - 1]; var T = i.substr(0, w.length).split(o).filter((function (e) { return e !== c })).length, A = p.substr(0, w.length).split(o).filter((function (e) { return e !== c })).length; !b && (A !== T || void 0 !== i[w.length - 1] && void 0 !== p[w.length - 2] && i[w.length - 1] !== c && i[w.length - 1] !== p[w.length - 1] && i[w.length - 1] === p[w.length - 2]) && T > 0 && p.indexOf(O) > -1 && void 0 !== d[s] && (x = !0, O = d[s]); for (var P = f.map((function (e) { return k[e] })).filter((function (e) { return e === O })).length, E = w.filter((function (e) { return e === O })).length, S = p.substr(0, p.indexOf(c)).split(o).filter((function (e, t) { return e === O && d[t] !== e })).length + E + P + (x ? 1 : 0), D = 0, R = 0; R < y && (_ = R + 1, k[R] === O && D++, !(D >= S)); R++); } else _ = s - M; if (b) { for (var I = _, N = _; N <= C; N++)if (p[N] === c && (I = N), p[N] === c || -1 !== v.indexOf(N) || N === C) return I } else if (x) { for (var F = _ - 1; F >= 0; F--)if (u[F] === O || -1 !== v.indexOf(F) || 0 === F) return F } else for (var j = _; j >= 0; j--)if (p[j - 1] === c || -1 !== v.indexOf(j) || 0 === j) return j }; var n = [], o = "" }, function (e, t, n) { "use strict"; Object.defineProperty(t, "__esModule", { value: !0 }); var o = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) { return typeof e } : function (e) { return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e }; t.default = function () { var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : l, t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : i, n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {}; if (!(0, r.isArray)(t)) { if ((void 0 === t ? "undefined" : o(t)) !== a.strFunction) throw new Error("Text-mask:conformToMask; The mask property must be an array."); t = t(e, n), t = (0, r.processCaretTraps)(t).maskWithoutCaretTraps } var s = n.guide, u = void 0 === s || s, d = n.previousConformedValue, c = void 0 === d ? l : d, p = n.placeholderChar, h = void 0 === p ? a.placeholderChar : p, f = n.placeholder, m = void 0 === f ? (0, r.convertMaskToPlaceholder)(t, h) : f, v = n.currentCaretPosition, g = n.keepCharPositions, C = !1 === u && void 0 !== c, y = e.length, M = c.length, b = m.length, _ = t.length, x = y - M, O = x > 0, k = v + (O ? -x : 0), w = k + Math.abs(x); if (!0 === g && !O) { for (var T = l, A = k; A < w; A++)m[A] === h && (T += h); e = e.slice(0, k) + T + e.slice(k, y) } for (var P = e.split(l).map((function (e, t) { return { char: e, isNew: t >= k && t < w } })), E = y - 1; E >= 0; E--) { var S = P[E].char; if (S !== h) { var D = E >= k && M === _; S === m[D ? E - x : E] && P.splice(E, 1) } } var R = l, I = !1; e: for (var N = 0; N < b; N++) { var F = m[N]; if (F === h) { if (P.length > 0) for (; P.length > 0;) { var j = P.shift(), V = j.char, U = j.isNew; if (V === h && !0 !== C) { R += h; continue e } if (t[N].test(V)) { if (!0 === g && !1 !== U && c !== l && !1 !== u && O) { for (var L = P.length, H = null, J = 0; J < L; J++) { var G = P[J]; if (G.char !== h && !1 === G.isNew) break; if (G.char === h) { H = J; break } } null !== H ? (R += V, P.splice(H, 1)) : N-- } else R += V; continue e } I = !0 } !1 === C && (R += m.substr(N, b)); break } R += F } if (C && !1 === O) { for (var K = null, $ = 0; $ < R.length; $++)m[$] === h && (K = $); R = null !== K ? R.substr(0, K + 1) : l } return { conformedValue: R, meta: { someCharsRejected: I } } }; var r = n(4), a = n(1), i = [], l = "" }, function (e, t, n) { "use strict"; function o(e) { return Array.isArray && Array.isArray(e) || e instanceof Array } Object.defineProperty(t, "__esModule", { value: !0 }), t.convertMaskToPlaceholder = function () { var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : a, t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : r.placeholderChar; if (!o(e)) throw new Error("Text-mask:convertMaskToPlaceholder; The mask property must be an array."); if (-1 !== e.indexOf(t)) throw new Error("Placeholder character must not be used as part of the mask. Please specify a character that is not present in your mask as your placeholder character.\n\nThe placeholder character that was received is: " + JSON.stringify(t) + "\n\nThe mask that was received is: " + JSON.stringify(e)); return e.map((function (e) { return e instanceof RegExp ? t : e })).join("") }, t.isArray = o, t.isString = function (e) { return "string" == typeof e || e instanceof String }, t.isNumber = function (e) { return "number" == typeof e && void 0 === e.length && !isNaN(e) }, t.isNil = function (e) { return null == e }, t.processCaretTraps = function (e) { for (var t = [], n = void 0; -1 !== (n = e.indexOf(i));)t.push(n), e.splice(n, 1); return { maskWithoutCaretTraps: e, indexes: t } }; var r = n(1), a = [], i = "[]" }, function (e, t, n) { "use strict"; function o(e) { return e && e.__esModule ? e : { default: e } } function r(e, t) { document.activeElement === e && (m ? v((function () { return e.setSelectionRange(t, t, h) }), 0) : e.setSelectionRange(t, t, h)) } function a(e) { if ((0, d.isString)(e)) return e; if ((0, d.isNumber)(e)) return String(e); if (null == e) return p; throw new Error("The 'value' provided to Text Mask needs to be a string or a number. The value received was:\n\n " + JSON.stringify(e)) } Object.defineProperty(t, "__esModule", { value: !0 }); var i = Object.assign || function (e) { for (var t = 1; t < arguments.length; t++) { var n = arguments[t]; for (var o in n) Object.prototype.hasOwnProperty.call(n, o) && (e[o] = n[o]) } return e }, l = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) { return typeof e } : function (e) { return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e }; t.default = function (e) { var t = { previousConformedValue: void 0, previousPlaceholder: void 0 }; return { state: t, update: function (n) { var o = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : e, h = o.inputElement, m = o.mask, v = o.guide, g = o.pipe, C = o.placeholderChar, y = void 0 === C ? c.placeholderChar : C, M = o.keepCharPositions, b = void 0 !== M && M, _ = o.showMask, x = void 0 !== _ && _; if (void 0 === n && (n = h.value), n !== t.previousConformedValue) { (void 0 === m ? "undefined" : l(m)) === f && void 0 !== m.pipe && void 0 !== m.mask && (g = m.pipe, m = m.mask); var O = void 0, k = void 0; if (m instanceof Array && (O = (0, d.convertMaskToPlaceholder)(m, y)), !1 !== m) { var w = a(n), T = h.selectionEnd, A = t.previousConformedValue, P = t.previousPlaceholder, E = void 0; if ((void 0 === m ? "undefined" : l(m)) === c.strFunction) { if (!1 === (k = m(w, { currentCaretPosition: T, previousConformedValue: A, placeholderChar: y }))) return; var S = (0, d.processCaretTraps)(k), D = S.maskWithoutCaretTraps, R = S.indexes; E = R, O = (0, d.convertMaskToPlaceholder)(k = D, y) } else k = m; var I = { previousConformedValue: A, guide: v, placeholderChar: y, pipe: g, placeholder: O, currentCaretPosition: T, keepCharPositions: b }, N = (0, u.default)(w, k, I), F = N.conformedValue, j = (void 0 === g ? "undefined" : l(g)) === c.strFunction, V = {}; j && (!1 === (V = g(F, i({ rawValue: w }, I))) ? V = { value: A, rejected: !0 } : (0, d.isString)(V) && (V = { value: V })); var U = j ? V.value : F, L = (0, s.default)({ previousConformedValue: A, previousPlaceholder: P, conformedValue: U, placeholder: O, rawValue: w, currentCaretPosition: T, placeholderChar: y, indexesOfPipedChars: V.indexesOfPipedChars, caretTrapIndexes: E }), H = U === O && 0 === L, J = x ? O : p, G = H ? J : U; t.previousConformedValue = G, t.previousPlaceholder = O, h.value !== G && (h.value = G, r(h, L)) } } } } }; var s = o(n(2)), u = o(n(3)), d = n(4), c = n(1), p = "", h = "none", f = "object", m = "undefined" != typeof navigator && /Android/i.test(navigator.userAgent), v = "undefined" != typeof requestAnimationFrame ? requestAnimationFrame : setTimeout }]) }, UVXo: function (e, t, n) { "use strict"; Object.defineProperty(t, "__esModule", { value: !0 }); var o = n("CcnG"), r = n("gIcY"), a = n("ZYjt"), i = n("FpO7"); t.TextMaskConfig = function () { return function () { } }(), t.MASKEDINPUT_VALUE_ACCESSOR = { provide: r.NG_VALUE_ACCESSOR, useExisting: o.forwardRef((function () { return l })), multi: !0 }; var l = function () { function e(e, t, n) { var o; this._renderer = e, this._elementRef = t, this._compositionMode = n, this.textMaskConfig = { mask: [], guide: !0, placeholderChar: "_", pipe: void 0, keepCharPositions: !1 }, this.onChange = function (e) { }, this.onTouched = function () { }, this._composing = !1, null == this._compositionMode && (this._compositionMode = (o = a.\u0275getDOM() ? a.\u0275getDOM().getUserAgent() : "", !/android (\d+)/.test(o.toLowerCase()))) } return e.prototype.ngOnChanges = function (e) { this._setupMask(!0), void 0 !== this.textMaskInputElement && this.textMaskInputElement.update(this.inputElement.value) }, e.prototype.writeValue = function (e) { this._setupMask(), this._renderer.setProperty(this.inputElement, "value", null == e ? "" : e), void 0 !== this.textMaskInputElement && this.textMaskInputElement.update(e) }, e.prototype.registerOnChange = function (e) { this.onChange = e }, e.prototype.registerOnTouched = function (e) { this.onTouched = e }, e.prototype.setDisabledState = function (e) { this._renderer.setProperty(this._elementRef.nativeElement, "disabled", e) }, e.prototype._handleInput = function (e) { (!this._compositionMode || this._compositionMode && !this._composing) && (this._setupMask(), void 0 !== this.textMaskInputElement && (this.textMaskInputElement.update(e), this.onChange(e = this.inputElement.value))) }, e.prototype._setupMask = function (e) { void 0 === e && (e = !1), this.inputElement || (this.inputElement = "INPUT" === this._elementRef.nativeElement.tagName.toUpperCase() ? this._elementRef.nativeElement : this._elementRef.nativeElement.getElementsByTagName("INPUT")[0]), this.inputElement && e && (this.textMaskInputElement = i.createTextMaskInputElement(Object.assign({ inputElement: this.inputElement }, this.textMaskConfig))) }, e.prototype._compositionStart = function () { this._composing = !0 }, e.prototype._compositionEnd = function (e) { this._composing = !1, this._compositionMode && this._handleInput(e) }, e.decorators = [{ type: o.Directive, args: [{ host: { "(input)": "_handleInput($event.target.value)", "(blur)": "onTouched()", "(compositionstart)": "_compositionStart()", "(compositionend)": "_compositionEnd($event.target.value)" }, selector: "[textMask]", exportAs: "textMask", providers: [t.MASKEDINPUT_VALUE_ACCESSOR] }] }], e.propDecorators = { textMaskConfig: [{ type: o.Input, args: ["textMask"] }] }, e }(); t.MaskedInputDirective = l, t.TextMaskModule = function () { function e() { } return e.decorators = [{ type: o.NgModule, args: [{ declarations: [l], exports: [l] }] }], e }(); var s = n("FpO7"); t.conformToMask = s.conformToMask } }]);