(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{KZdf:function(l,n,u){"use strict";u.r(n);var t=u("CcnG"),a=function(){},o=u("pMnS"),i=u("gIcY"),e=u("Ip0R"),s=u("KD9O"),r=u("ZYCi"),d=u("Gt+l"),c=u("s2JW"),b=u("EVdn"),p=u("uMUK"),m=u("fdbx"),g=function(){function l(l,n,u,t){var a=this;this.app=l,this.route=n,this.router=u,this.store=t,this.data={id:"",username:"",distributor_id:"",name:"",telephone:"",password:"",password_confirmation:"",email:"",active:1,is_admin:"",type:0},this.role=[{id:3,name:"Order Manager"},{id:2,name:"Order Viewer"}],this.store.select(p.a.getCurrentUser).subscribe(function(l){a.customer_id=l.id,a.permission=l.is_admin})}return l.prototype.ngOnInit=function(){this.fd=new c.a(this.data),this.getprofile()},l.prototype.getprofile=function(){var l=this;this.app.get("v1/customers/"+this.customer_id).subscribe(function(n){l.ld=n.data,l.fd.setData(n.data)})},l.prototype.save=function(){this.fd.form.value.permission=this.app.constant.GROUP_ADMIN,this.app.post("v1/staffs/"+this.ld.id,this.fd.form.value).subscribe(function(l){b("#myModal").show()})},l.prototype.hidenModal=function(){b(".has-error").removeClass("has-error"),b("small.help-block").remove(),b("#myModal").hide(),this.router.navigate(["/profile/detail"])},l}(),f=t.Sa({encapsulation:0,styles:[[".block__bottom[_ngcontent-%COMP%]{padding-top:70px}.list-info[_ngcontent-%COMP%]   th[_ngcontent-%COMP%]{width:250px}input[_ngcontent-%COMP%], select[_ngcontent-%COMP%]{width:550px;padding-left:5px!important}input[type=checkbox][_ngcontent-%COMP%], input[type=radio][_ngcontent-%COMP%]{height:20px}.has-error[_ngcontent-%COMP%]{border:1px solid red!important}.help-block[_ngcontent-%COMP%]{font-size:100%!important}#active[_ngcontent-%COMP%]{width:50px}"]],data:{}});function h(l){return t.nb(0,[(l()(),t.Ua(0,0,null,null,1,"span",[],null,null,null,null,null)),(l()(),t.lb(1,null,["",""]))],null,function(l,n){l(n,1,0,"ACCOUNT HOLDER")})}function v(l){return t.nb(0,[(l()(),t.Ua(0,0,null,null,1,"span",[],null,null,null,null,null)),(l()(),t.lb(1,null,["",""]))],null,function(l,n){l(n,1,0,"ORDER VIEWER")})}function U(l){return t.nb(0,[(l()(),t.Ua(0,0,null,null,1,"span",[],null,null,null,null,null)),(l()(),t.lb(1,null,["",""]))],null,function(l,n){l(n,1,0,"ORDER MANAGER")})}function C(l){return t.nb(0,[(l()(),t.Ua(0,0,null,null,100,"table",[["class","list-info"]],null,null,null,null,null)),(l()(),t.Ua(1,0,null,null,99,"tbody",[],null,null,null,null,null)),(l()(),t.Ua(2,0,null,null,7,"tr",[],null,null,null,null,null)),(l()(),t.Ua(3,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(4,null,[""," "])),t.hb(5,1),(l()(),t.Ua(6,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(8,0,null,null,1,"td",[],null,null,null,null,null)),(l()(),t.lb(9,null,["",""])),(l()(),t.Ua(10,0,null,null,13,"tr",[],null,null,null,null,null)),(l()(),t.Ua(11,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(12,null,[""," "])),t.hb(13,1),(l()(),t.Ua(14,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(16,0,null,null,7,"td",[],null,null,null,null,null)),(l()(),t.Ua(17,0,null,null,6,"input",[["autocomplete","off"],["formControlName","name"],["type","text"]],[[8,"placeholder",0],[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,u){var a=!0;return"input"===n&&(a=!1!==t.db(l,18)._handleInput(u.target.value)&&a),"blur"===n&&(a=!1!==t.db(l,18).onTouched()&&a),"compositionstart"===n&&(a=!1!==t.db(l,18)._compositionStart()&&a),"compositionend"===n&&(a=!1!==t.db(l,18)._compositionEnd(u.target.value)&&a),a},null,null)),t.Ta(18,16384,null,0,i.d,[t.G,t.m,[2,i.a]],null,null),t.ib(1024,null,i.i,function(l){return[l]},[i.d]),t.Ta(20,671744,null,0,i.f,[[3,i.c],[8,null],[8,null],[6,i.i],[2,i.w]],{name:[0,"name"]},null),t.ib(2048,null,i.j,null,[i.f]),t.Ta(22,16384,null,0,i.k,[[4,i.j]],null,null),t.hb(23,1),(l()(),t.Ua(24,0,null,null,13,"tr",[],null,null,null,null,null)),(l()(),t.Ua(25,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(26,null,[""," "])),t.hb(27,1),(l()(),t.Ua(28,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(30,0,null,null,7,"td",[],null,null,null,null,null)),(l()(),t.Ua(31,0,null,null,6,"input",[["autocomplete","off"],["formControlName","email"],["type","text"]],[[8,"placeholder",0],[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,u){var a=!0;return"input"===n&&(a=!1!==t.db(l,32)._handleInput(u.target.value)&&a),"blur"===n&&(a=!1!==t.db(l,32).onTouched()&&a),"compositionstart"===n&&(a=!1!==t.db(l,32)._compositionStart()&&a),"compositionend"===n&&(a=!1!==t.db(l,32)._compositionEnd(u.target.value)&&a),a},null,null)),t.Ta(32,16384,null,0,i.d,[t.G,t.m,[2,i.a]],null,null),t.ib(1024,null,i.i,function(l){return[l]},[i.d]),t.Ta(34,671744,null,0,i.f,[[3,i.c],[8,null],[8,null],[6,i.i],[2,i.w]],{name:[0,"name"]},null),t.ib(2048,null,i.j,null,[i.f]),t.Ta(36,16384,null,0,i.k,[[4,i.j]],null,null),t.hb(37,1),(l()(),t.Ua(38,0,null,null,13,"tr",[],null,null,null,null,null)),(l()(),t.Ua(39,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(40,null,[""," "])),t.hb(41,1),(l()(),t.Ua(42,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(44,0,null,null,7,"td",[],null,null,null,null,null)),(l()(),t.Ua(45,0,null,null,6,"input",[["autocomplete","off"],["formControlName","telephone"],["type","text"]],[[8,"placeholder",0],[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,u){var a=!0;return"input"===n&&(a=!1!==t.db(l,46)._handleInput(u.target.value)&&a),"blur"===n&&(a=!1!==t.db(l,46).onTouched()&&a),"compositionstart"===n&&(a=!1!==t.db(l,46)._compositionStart()&&a),"compositionend"===n&&(a=!1!==t.db(l,46)._compositionEnd(u.target.value)&&a),a},null,null)),t.Ta(46,16384,null,0,i.d,[t.G,t.m,[2,i.a]],null,null),t.ib(1024,null,i.i,function(l){return[l]},[i.d]),t.Ta(48,671744,null,0,i.f,[[3,i.c],[8,null],[8,null],[6,i.i],[2,i.w]],{name:[0,"name"]},null),t.ib(2048,null,i.j,null,[i.f]),t.Ta(50,16384,null,0,i.k,[[4,i.j]],null,null),t.hb(51,1),(l()(),t.Ua(52,0,null,null,13,"tr",[],null,null,null,null,null)),(l()(),t.Ua(53,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(54,null,[""," "])),t.hb(55,1),(l()(),t.Ua(56,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(58,0,null,null,7,"td",[],null,null,null,null,null)),(l()(),t.Ua(59,0,null,null,6,"input",[["autocomplete","off"],["formControlName","password"],["type","password"]],[[8,"placeholder",0],[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,u){var a=!0;return"input"===n&&(a=!1!==t.db(l,60)._handleInput(u.target.value)&&a),"blur"===n&&(a=!1!==t.db(l,60).onTouched()&&a),"compositionstart"===n&&(a=!1!==t.db(l,60)._compositionStart()&&a),"compositionend"===n&&(a=!1!==t.db(l,60)._compositionEnd(u.target.value)&&a),a},null,null)),t.Ta(60,16384,null,0,i.d,[t.G,t.m,[2,i.a]],null,null),t.ib(1024,null,i.i,function(l){return[l]},[i.d]),t.Ta(62,671744,null,0,i.f,[[3,i.c],[8,null],[8,null],[6,i.i],[2,i.w]],{name:[0,"name"]},null),t.ib(2048,null,i.j,null,[i.f]),t.Ta(64,16384,null,0,i.k,[[4,i.j]],null,null),t.hb(65,1),(l()(),t.Ua(66,0,null,null,13,"tr",[],null,null,null,null,null)),(l()(),t.Ua(67,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(68,null,[""," "])),t.hb(69,1),(l()(),t.Ua(70,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(72,0,null,null,7,"td",[],null,null,null,null,null)),(l()(),t.Ua(73,0,null,null,6,"input",[["autocomplete","off"],["formControlName","password_confirmation"],["type","password"]],[[8,"placeholder",0],[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"input"],[null,"blur"],[null,"compositionstart"],[null,"compositionend"]],function(l,n,u){var a=!0;return"input"===n&&(a=!1!==t.db(l,74)._handleInput(u.target.value)&&a),"blur"===n&&(a=!1!==t.db(l,74).onTouched()&&a),"compositionstart"===n&&(a=!1!==t.db(l,74)._compositionStart()&&a),"compositionend"===n&&(a=!1!==t.db(l,74)._compositionEnd(u.target.value)&&a),a},null,null)),t.Ta(74,16384,null,0,i.d,[t.G,t.m,[2,i.a]],null,null),t.ib(1024,null,i.i,function(l){return[l]},[i.d]),t.Ta(76,671744,null,0,i.f,[[3,i.c],[8,null],[8,null],[6,i.i],[2,i.w]],{name:[0,"name"]},null),t.ib(2048,null,i.j,null,[i.f]),t.Ta(78,16384,null,0,i.k,[[4,i.j]],null,null),t.hb(79,1),(l()(),t.Ua(80,0,null,null,7,"tr",[],null,null,null,null,null)),(l()(),t.Ua(81,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(82,null,[""," "])),t.hb(83,1),(l()(),t.Ua(84,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(86,0,null,null,1,"td",[],null,null,null,null,null)),(l()(),t.lb(87,null,["",""])),(l()(),t.Ua(88,0,null,null,12,"tr",[],null,null,null,null,null)),(l()(),t.Ua(89,0,null,null,4,"th",[],null,null,null,null,null)),(l()(),t.lb(90,null,[""," "])),t.hb(91,1),(l()(),t.Ua(92,0,null,null,1,"span",[["class","text-danger"]],null,null,null,null,null)),(l()(),t.lb(-1,null,["*"])),(l()(),t.Ua(94,0,null,null,6,"td",[],null,null,null,null,null)),(l()(),t.La(16777216,null,null,1,null,h)),t.Ta(96,16384,null,0,e.l,[t.S,t.P],{ngIf:[0,"ngIf"]},null),(l()(),t.La(16777216,null,null,1,null,v)),t.Ta(98,16384,null,0,e.l,[t.S,t.P],{ngIf:[0,"ngIf"]},null),(l()(),t.La(16777216,null,null,1,null,U)),t.Ta(100,16384,null,0,e.l,[t.S,t.P],{ngIf:[0,"ngIf"]},null)],function(l,n){var u=n.component;l(n,20,0,"name"),l(n,34,0,"email"),l(n,48,0,"telephone"),l(n,62,0,"password"),l(n,76,0,"password_confirmation"),l(n,96,0,u.ld.is_admin===u.app.constant.GROUP_ADMIN),l(n,98,0,u.ld.is_admin===u.app.constant.ORDER_VIEWER),l(n,100,0,u.ld.is_admin===u.app.constant.ORDER_MANAGER)},function(l,n){var u=n.component;l(n,4,0,t.mb(n,4,0,l(n,5,0,t.db(n.parent,0),"Username"))),l(n,9,0,u.ld.username),l(n,12,0,t.mb(n,12,0,l(n,13,0,t.db(n.parent,0),"Name"))),l(n,17,0,t.Wa(1,"",t.mb(n,17,0,l(n,23,0,t.db(n.parent,0),"Name")),""),t.db(n,22).ngClassUntouched,t.db(n,22).ngClassTouched,t.db(n,22).ngClassPristine,t.db(n,22).ngClassDirty,t.db(n,22).ngClassValid,t.db(n,22).ngClassInvalid,t.db(n,22).ngClassPending),l(n,26,0,t.mb(n,26,0,l(n,27,0,t.db(n.parent,0),"Email"))),l(n,31,0,t.Wa(1,"",t.mb(n,31,0,l(n,37,0,t.db(n.parent,0),"Email")),""),t.db(n,36).ngClassUntouched,t.db(n,36).ngClassTouched,t.db(n,36).ngClassPristine,t.db(n,36).ngClassDirty,t.db(n,36).ngClassValid,t.db(n,36).ngClassInvalid,t.db(n,36).ngClassPending),l(n,40,0,t.mb(n,40,0,l(n,41,0,t.db(n.parent,0),"Phone"))),l(n,45,0,t.Wa(1,"",t.mb(n,45,0,l(n,51,0,t.db(n.parent,0),"Phone")),""),t.db(n,50).ngClassUntouched,t.db(n,50).ngClassTouched,t.db(n,50).ngClassPristine,t.db(n,50).ngClassDirty,t.db(n,50).ngClassValid,t.db(n,50).ngClassInvalid,t.db(n,50).ngClassPending),l(n,54,0,t.mb(n,54,0,l(n,55,0,t.db(n.parent,0),"Password"))),l(n,59,0,t.Wa(1,"",t.mb(n,59,0,l(n,65,0,t.db(n.parent,0),"Password")),""),t.db(n,64).ngClassUntouched,t.db(n,64).ngClassTouched,t.db(n,64).ngClassPristine,t.db(n,64).ngClassDirty,t.db(n,64).ngClassValid,t.db(n,64).ngClassInvalid,t.db(n,64).ngClassPending),l(n,68,0,t.mb(n,68,0,l(n,69,0,t.db(n.parent,0),"Password confirmation"))),l(n,73,0,t.Wa(1,"",t.mb(n,73,0,l(n,79,0,t.db(n.parent,0),"Password confirmation")),""),t.db(n,78).ngClassUntouched,t.db(n,78).ngClassTouched,t.db(n,78).ngClassPristine,t.db(n,78).ngClassDirty,t.db(n,78).ngClassValid,t.db(n,78).ngClassInvalid,t.db(n,78).ngClassPending),l(n,82,0,t.mb(n,82,0,l(n,83,0,t.db(n.parent,0),"Distributor"))),l(n,87,0,u.ld.distributor.name),l(n,90,0,t.mb(n,90,0,l(n,91,0,t.db(n.parent,0),"Role")))})}function _(l){return t.nb(0,[t.fb(0,s.a,[]),(l()(),t.Ua(1,0,null,null,21,"div",[["class","main main__none-cart ad-home"],["id","main"]],null,null,null,null,null)),(l()(),t.Ua(2,0,null,null,20,"div",[["class","container block"]],null,null,null,null,null)),(l()(),t.Ua(3,0,null,null,1,"h2",[["class","block__title orange__title"]],null,null,null,null,null)),(l()(),t.lb(-1,null,[" Profile "])),(l()(),t.Ua(5,0,null,null,17,"div",[["class","row justify-content-center custom-info-page"]],null,null,null,null,null)),(l()(),t.Ua(6,0,null,null,16,"div",[["class","col-md-9 col-sm-10"]],null,null,null,null,null)),(l()(),t.Ua(7,0,null,null,15,"form",[["class","form-horizontal"],["novalidate",""]],[[2,"ng-untouched",null],[2,"ng-touched",null],[2,"ng-pristine",null],[2,"ng-dirty",null],[2,"ng-valid",null],[2,"ng-invalid",null],[2,"ng-pending",null]],[[null,"submit"],[null,"reset"]],function(l,n,u){var a=!0;return"submit"===n&&(a=!1!==t.db(l,9).onSubmit(u)&&a),"reset"===n&&(a=!1!==t.db(l,9).onReset()&&a),a},null,null)),t.Ta(8,16384,null,0,i.u,[],null,null),t.Ta(9,540672,null,0,i.g,[[8,null],[8,null]],{form:[0,"form"]},null),t.ib(2048,null,i.c,null,[i.g]),t.Ta(11,16384,null,0,i.l,[[4,i.c]],null,null),(l()(),t.La(16777216,null,null,1,null,C)),t.Ta(13,16384,null,0,e.l,[t.S,t.P],{ngIf:[0,"ngIf"]},null),(l()(),t.Ua(14,0,null,null,8,"div",[["class","block__bottom row justify-content-center"]],null,null,null,null,null)),(l()(),t.Ua(15,0,null,null,4,"div",[["class","col-sm-3 col-6 right"]],null,null,null,null,null)),(l()(),t.Ua(16,0,null,null,3,"button",[["class","button_black button_black--double"]],null,[[null,"click"]],function(l,n,u){var a=!0;return"click"===n&&(a=!1!==t.db(l,17).onClick()&&a),a},null,null)),t.Ta(17,16384,null,0,r.n,[r.m,r.a,[8,null],t.G,t.m],{routerLink:[0,"routerLink"]},null),t.eb(18,1),(l()(),t.lb(-1,null,["Back"])),(l()(),t.Ua(20,0,null,null,2,"div",[["class","col-sm-3 col-6 left"]],null,null,null,null,null)),(l()(),t.Ua(21,0,null,null,1,"button",[["class","button_black button_black--double"]],null,[[null,"click"]],function(l,n,u){var t=!0;return"click"===n&&(t=!1!==l.component.save()&&t),t},null,null)),(l()(),t.lb(-1,null,["Submit"])),(l()(),t.Ua(23,0,null,null,10,"div",[["class","modal"],["id","myModal"]],null,null,null,null,null)),(l()(),t.Ua(24,0,null,null,9,"div",[["class","modal-dialog"]],null,null,null,null,null)),(l()(),t.Ua(25,0,null,null,8,"div",[["class","modal-content"]],null,null,null,null,null)),(l()(),t.Ua(26,0,null,null,1,"button",[["class","close"],["data-dismiss","modal"],["type","button"]],null,null,null,null,null)),(l()(),t.Ua(27,0,null,null,0,"img",[["alt",""],["src","assets/images/icon-delete.png"]],null,null,null,null,null)),(l()(),t.Ua(28,0,null,null,5,"div",[["class","alert-modal"]],null,null,null,null,null)),(l()(),t.Ua(29,0,null,null,1,"div",[["class","alert-modal__text"]],null,null,null,null,null)),(l()(),t.lb(-1,null,[" Profile updated successfully! Please check your email ! "])),(l()(),t.Ua(31,0,null,null,2,"div",[["class","alert-modal__button"]],null,null,null,null,null)),(l()(),t.Ua(32,0,null,null,1,"button",[["class","button_black"]],null,[[null,"click"]],function(l,n,u){var t=!0;return"click"===n&&(t=!1!==l.component.hidenModal()&&t),t},null,null)),(l()(),t.lb(-1,null,["OK"]))],function(l,n){var u=n.component;l(n,9,0,u.fd.form),l(n,13,0,u.ld),l(n,17,0,l(n,18,0,"/staff/list"))},function(l,n){l(n,7,0,t.db(n,11).ngClassUntouched,t.db(n,11).ngClassTouched,t.db(n,11).ngClassPristine,t.db(n,11).ngClassDirty,t.db(n,11).ngClassValid,t.db(n,11).ngClassInvalid,t.db(n,11).ngClassPending)})}var y=t.Qa("app-profile",g,function(l){return t.nb(0,[(l()(),t.Ua(0,0,null,null,1,"app-profile",[],null,null,null,_,f)),t.Ta(1,114688,null,0,g,[d.a,r.a,r.m,m.i],null,null)],function(l,n){l(n,1,0)},null)},{},{},[]),P=u("ADsi"),T=function(){};u.d(n,"ProfileModuleNgFactory",function(){return w});var w=t.Ra(a,[],function(l){return t.bb([t.cb(512,t.l,t.Ga,[[8,[o.a,y]],[3,t.l],t.A]),t.cb(4608,e.n,e.m,[t.x,[2,e.u]]),t.cb(4608,i.e,i.e,[]),t.cb(4608,i.v,i.v,[]),t.cb(1073742336,e.b,e.b,[]),t.cb(1073742336,r.p,r.p,[[2,r.v],[2,r.m]]),t.cb(1073742336,P.a,P.a,[]),t.cb(1073742336,i.s,i.s,[]),t.cb(1073742336,i.p,i.p,[]),t.cb(1073742336,T,T,[]),t.cb(1073742336,a,a,[]),t.cb(1024,r.k,function(){return[[{path:"",redirectTo:"detail"},{path:"detail",component:g}]]},[])])})},s2JW:function(l,n,u){"use strict";u.d(n,"a",function(){return a});var t=u("gIcY"),a=function(){function l(l){this.isNew=!0,this.structureParent={},this.structureChild=[],this.formBuilder=new t.e,this.structureParent=l,this.form=this.formBuilder.group(l)}return l.prototype.initChild=function(l,n){this.structureChild[l]=n,this.form.registerControl(l,this.formBuilder.array([]))},l.prototype.addItem=function(l){this.form.controls[l].push(this.formBuilder.group(this.structureChild[l]))},l.prototype.removeItem=function(l,n){this.form.controls[l].removeAt(n)},l.prototype.setData=function(l){var n=this;this.form.patchValue(l);for(var u=function(u){void 0!==l[u]&&l[u].forEach(function(l){n.form.controls[u].push(n.formBuilder.group(l))})},t=0,a=this.structureChild;t<a.length;t++)u(a[t])},l}()}}]);