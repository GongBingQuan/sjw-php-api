(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-login-weblogin"],{"0f56":function(e,t,i){"use strict";var s=i("539b"),o=i.n(s);o.a},"539b":function(e,t,i){var s=i("96db");"string"===typeof s&&(s=[[e.i,s,""]]),s.locals&&(e.exports=s.locals);var o=i("4f06").default;o("fed3c016",s,!0,{sourceMap:!1,shadowMode:!1})},"5dba":function(e,t,i){"use strict";var s;i.d(t,"b",(function(){return o})),i.d(t,"c",(function(){return n})),i.d(t,"a",(function(){return s}));var o=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"login-container",style:"height: "+e.phoneHeight+"px;"},[2==e.is_login?i("v-uni-view",{staticClass:"p-30-75"},[i("v-uni-view",{staticClass:"login_topbpx"},[i("v-uni-view",{staticClass:"login_tit"},[e._v("注册")]),i("v-uni-view",{staticClass:"login_top"},[e._v("已有账户，"),i("v-uni-text",{staticClass:"red",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.is_login=1}}},[e._v("立即登录")])],1)],1),i("v-uni-view",{staticClass:"group-bd"},[i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",placeholder:"请填写手机号",disabled:e.is_send},model:{value:e.register.mobile,callback:function(t){e.$set(e.register,"mobile",t)},expression:"register.mobile"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",password:"true",placeholder:"请输入密码"},model:{value:e.register.password,callback:function(t){e.$set(e.register,"password",t)},expression:"register.password"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",password:"true",placeholder:"请确认密码"},model:{value:e.register.repassword,callback:function(t){e.$set(e.register,"repassword",t)},expression:"register.repassword"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 d-b-c input_botom"},[i("v-uni-input",{staticClass:"flex-1",attrs:{type:"number",placeholder:"请填写验证码"},model:{value:e.register.code,callback:function(t){e.$set(e.register,"code",t)},expression:"register.code"}}),i("v-uni-button",{staticClass:"get-code-btn",attrs:{type:"default",disabled:e.is_send},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.sendCode.apply(void 0,arguments)}}},[e._v(e._s(e.send_btn_txt))])],1)],1)],1)],1):e._e(),1==e.is_login?i("v-uni-view",{staticClass:"p-30-75"},[i("v-uni-view",{staticClass:"login_topbpx"},[i("v-uni-view",{staticClass:"login_tit"},[e._v("登录")]),i("v-uni-view",{staticClass:"login_top"},[e._v("还没有账号，"),i("v-uni-text",{staticClass:"red",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.is_login=2}}},[e._v("立即注册")])],1)],1),i("v-uni-view",{staticClass:"group-bd"},[i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",placeholder:"请填写手机号"},model:{value:e.formData.mobile,callback:function(t){e.$set(e.formData,"mobile",t)},expression:"formData.mobile"}})],1)],1),e.is_code?e._e():i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",password:"true",placeholder:"请输入密码"},model:{value:e.loging_password,callback:function(t){e.loging_password=t},expression:"loging_password"}})],1)],1),e.is_code?i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 d-b-c input_botom"},[i("v-uni-input",{staticClass:"flex-1",attrs:{type:"number",placeholder:"请填写验证码"},model:{value:e.formData.code,callback:function(t){e.$set(e.formData,"code",t)},expression:"formData.code"}}),i("v-uni-button",{staticClass:"get-code-btn",attrs:{type:"default",disabled:e.is_send},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.sendCode.apply(void 0,arguments)}}},[e._v(e._s(e.send_btn_txt))])],1)],1):e._e()],1)],1):e._e(),0==e.is_login?i("v-uni-view",{staticClass:"p-30-75"},[i("v-uni-view",{staticClass:"login_topbpx"},[i("v-uni-view",{staticClass:"login_tit"},[e._v("重置密码")]),i("v-uni-view",{staticClass:"login_top"},[i("v-uni-text",{staticClass:"red",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.is_login=1}}},[e._v("立即登录")])],1)],1),i("v-uni-view",{staticClass:"group-bd"},[i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",placeholder:"请填写手机号",disabled:e.is_send},model:{value:e.resetpassword.mobile,callback:function(t){e.$set(e.resetpassword,"mobile",t)},expression:"resetpassword.mobile"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",password:"true",placeholder:"请输入密码"},model:{value:e.resetpassword.password,callback:function(t){e.$set(e.resetpassword,"password",t)},expression:"resetpassword.password"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 input_botom"},[i("v-uni-input",{attrs:{type:"text",password:"true",placeholder:"请确认密码"},model:{value:e.resetpassword.repassword,callback:function(t){e.$set(e.resetpassword,"repassword",t)},expression:"resetpassword.repassword"}})],1)],1),i("v-uni-view",{staticClass:"form-level d-s-c"},[i("v-uni-view",{staticClass:"val flex-1 d-b-c input_botom"},[i("v-uni-input",{staticClass:"flex-1",attrs:{type:"number",placeholder:"请填写验证码"},model:{value:e.resetpassword.code,callback:function(t){e.$set(e.resetpassword,"code",t)},expression:"resetpassword.code"}}),i("v-uni-button",{staticClass:"get-code-btn",attrs:{type:"default",disabled:e.is_send},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.sendCode.apply(void 0,arguments)}}},[e._v(e._s(e.send_btn_txt))])],1)],1)],1)],1):e._e(),1==e.is_login?i("v-uni-view",{staticClass:" gray6 p-0-75",class:e.is_code?"d-e-c":"d-b-c"},[e.is_code?e._e():i("v-uni-view",{on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.is_login=0}}},[e._v("忘记密码?")]),i("v-uni-view",{on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.isCode()}}},[e._v(e._s(e.is_code?"使用密码登录":"使用验证码登录"))])],1):e._e(),2==e.is_login?i("v-uni-view",{staticClass:"btns p-30-75",staticStyle:{"padding-top":"80rpx"}},[i("v-uni-button",{attrs:{type:"default"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.registerSub.apply(void 0,arguments)}}},[e._v("注册")])],1):e._e(),1==e.is_login?i("v-uni-view",{staticClass:"btns p-30-75",staticStyle:{"padding-top":"80rpx"}},[i("v-uni-button",{attrs:{type:"default"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.formSubmit.apply(void 0,arguments)}}},[e._v("登录")])],1):e._e(),0==e.is_login?i("v-uni-view",{staticClass:"btns p-30-75",staticStyle:{"padding-top":"80rpx"}},[i("v-uni-button",{attrs:{type:"default"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.resetpasswordSub.apply(void 0,arguments)}}},[e._v("重置密码")])],1):e._e()],1)},n=[]},"939b":function(e,t,i){"use strict";i.r(t);var s=i("ff48"),o=i.n(s);for(var n in s)"default"!==n&&function(e){i.d(t,e,(function(){return s[e]}))}(n);t["default"]=o.a},"96db":function(e,t,i){var s=i("24fb");t=s(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */uni-page-body[data-v-44e4fd2c]{background-color:#fff}.p-30-75[data-v-44e4fd2c]{padding:%?30?% %?75?%}.p-0-75[data-v-44e4fd2c]{padding:0 %?75?%}.t-r[data-v-44e4fd2c]{text-align:right}.login-container[data-v-44e4fd2c]{position:relative;background:#fff}.login-container uni-input[data-v-44e4fd2c]{height:%?88?%;line-height:%?88?%}.wechatapp[data-v-44e4fd2c]{padding:%?80?% 0 %?48?%;border-bottom:%?1?% solid #e3e3e3;margin-bottom:%?72?%;text-align:center}.wechatapp .header[data-v-44e4fd2c]{width:%?190?%;height:%?190?%;border:2px solid #fff;margin:%?0?% auto 0;border-radius:50%;overflow:hidden;box-shadow:1px 0 5px rgba(50,50,50,.3)}.auth-title[data-v-44e4fd2c]{color:#585858;font-size:%?34?%;margin-bottom:%?40?%}.auth-subtitle[data-v-44e4fd2c]{color:#888;margin-bottom:%?88?%;font-size:%?28?%}.login-btn[data-v-44e4fd2c]{padding:0 %?20?%}.login-btn uni-button[data-v-44e4fd2c]{height:%?88?%;line-height:%?88?%;background:#04be01;color:#fff;font-size:%?30?%;border-radius:%?999?%;text-align:center}.no-login-btn[data-v-44e4fd2c]{margin-top:%?20?%;padding:0 %?20?%}.no-login-btn uni-button[data-v-44e4fd2c]{height:%?88?%;line-height:%?88?%;background:#dfdfdf;color:#fff;font-size:%?30?%;border-radius:%?999?%;text-align:center}.get-code-btn[data-v-44e4fd2c]{width:%?200?%;height:%?80?%;line-height:%?76?%;padding:%?0?% %?30?%;border-radius:%?40?%;white-space:nowrap;background-color:#fff;color:#e2231a;font-size:%?30?%}.get-code-btn[disabled="true"][data-v-44e4fd2c]{background-color:#fff}.btns uni-button[data-v-44e4fd2c]{height:%?90?%;line-height:%?90?%;font-size:%?34?%;border-radius:%?45?%;background:#e2231a;color:#fff}.login_topbpx[data-v-44e4fd2c]{padding:%?181?% 0;padding-bottom:%?110?%}.login_tit[data-v-44e4fd2c]{font-size:%?52?%;font-weight:600;margin-bottom:%?33?%}.login_top[data-v-44e4fd2c]{font-size:%?24?%;color:#adafb3}.input_botom[data-v-44e4fd2c]{border-bottom:1px solid #f4f4f4}.bottom_nav[data-v-44e4fd2c]{width:100%;position:absolute;bottom:%?100?%}.bottom-box[data-v-44e4fd2c]{width:70%;margin:0 auto}.other_tit[data-v-44e4fd2c]{height:%?1?%;background-color:#cacaca;width:100%;line-height:%?1?%;text-align:center}.weixin_box[data-v-44e4fd2c]{background-color:#04be01;border-radius:50%;width:%?80?%;height:%?80?%;line-height:%?80?%;text-align:center}.weixin_box .icon-weixin[data-v-44e4fd2c]{font-size:%?40?%;color:#fff}body.?%PAGE?%[data-v-44e4fd2c]{background-color:#fff}',""]),e.exports=t},dbf7:function(e,t,i){"use strict";i.r(t);var s=i("5dba"),o=i("939b");for(var n in o)"default"!==n&&function(e){i.d(t,e,(function(){return o[e]}))}(n);i("0f56");var a,r=i("f0c5"),d=Object(r["a"])(o["default"],s["b"],s["c"],!1,null,"44e4fd2c",null,!1,s["a"],a);t["default"]=d.exports},ff48:function(e,t,i){"use strict";i("b64b"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var s={data:function(){return{formData:{mobile:"",code:""},loging_password:"",register:{mobile:"",password:"",repassword:"",code:""},resetpassword:{mobile:"",password:"",repassword:"",code:""},is_send:!1,send_btn_txt:"获取验证码",second:60,ip:"",isShow:!0,is_login:1,is_code:!0,phoneHeight:0}},onLoad:function(){},onShow:function(){this.init()},methods:{init:function(){var e=this;uni.getSystemInfo({success:function(t){e.phoneHeight=t.windowHeight}})},formSubmit:function(){var e=this,t={mobile:e.formData.mobile},i="";if(!/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.formData.mobile))return console.log(e.formData.mobile),void uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"});if(e.is_code){if(console.log(e.is_code),""==e.formData.code)return void uni.showToast({title:"验证码不能为空！",duration:2e3,icon:"none"});t.code=e.formData.code,i="user.useropen/smslogin"}else{if(""==e.loging_password)return void uni.showToast({title:"密码不能为空！",duration:2e3,icon:"none"});t.password=e.loging_password,i="user.useropen/phonelogin"}uni.showLoading({title:"正在提交"}),e._post(i,t,(function(t){uni.setStorageSync("token",t.data.token),uni.setStorageSync("user_id",t.data.user_id);var i="/"+uni.getStorageSync("currentPage"),s=uni.getStorageSync("currentPageOptions");if(Object.keys(s).length>0){for(var o in i+="?",s)i+=o+"="+s[o]+"&";i=i.substring(0,i.length-1)}e.gotoPage(i)}),!1,(function(){uni.hideLoading()}))},registerSub:function(){var e=this;if(!/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.register.mobile))return console.log(e.register.mobile),void uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"});""!=e.register.code?e.register.password.length<6?uni.showToast({title:"密码至少6位数！",duration:2e3,icon:"none"}):e.register.password===e.register.repassword?(e.register.invitation_id=uni.getStorageSync("invitation_id")?uni.getStorageSync("invitation_id"):0,e.register.reg_source="h5",e.register.referee_id=uni.getStorageSync("referee_id"),uni.showLoading({title:"正在提交"}),e._post("user.useropen/register",e.register,(function(t){uni.showToast({title:"注册成功",duration:3e3}),e.formData.mobile=e.register.mobile,e.register={mobile:"",password:"",repassword:"",code:""},e.second=0,e.changeMsg(),e.is_login=1}),!1,(function(){uni.hideLoading()}))):uni.showToast({title:"两次密码输入不一致！",duration:2e3,icon:"none"}):uni.showToast({title:"验证码不能为空！",duration:2e3,icon:"none"})},resetpasswordSub:function(){var e=this;/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.resetpassword.mobile)?""!=e.resetpassword.code?e.resetpassword.password.length<6?uni.showToast({title:"密码至少6位数！",duration:2e3,icon:"none"}):e.resetpassword.password===e.resetpassword.repassword?(uni.showLoading({title:"正在提交"}),e._post("user.useropen/resetpassword",e.resetpassword,(function(t){uni.showToast({title:"重置成功",duration:3e3}),e.formData.mobile=e.resetpassword.mobile,e.resetpassword={mobile:"",password:"",repassword:"",code:""},e.second=0,e.changeMsg(),e.is_login=1}),!1,(function(){uni.hideLoading()}))):uni.showToast({title:"两次密码输入不一致！",duration:2e3,icon:"none"}):uni.showToast({title:"验证码不能为空！",duration:2e3,icon:"none"}):uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"})},isCode:function(){this.is_code?this.$set(this,"is_code",!1):this.$set(this,"is_code",!0),console.log(this.is_code)},sendCode:function(){var e=this;if(1==e.is_login){if(!/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.formData.mobile))return void uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"})}else if(2==e.is_login){if(!/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.register.mobile))return void uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"})}else if(0==e.is_login&&!/^1(3|4|5|6|7|8|9)\d{9}$/.test(e.resetpassword.mobile))return void uni.showToast({title:"手机有误,请重填！",duration:2e3,icon:"none"});var t="register",i=e.register.mobile;1==e.is_login?(t="login",i=e.formData.mobile):0==e.is_login&&(t="login",i=e.resetpassword.mobile),e._post("user.useropen/sendCode",{mobile:i,type:t},(function(t){1==t.code&&(uni.showToast({title:"发送成功"}),e.is_send=!0,e.changeMsg())}))},changeMsg:function(){this.second>0?(this.send_btn_txt=this.second+"秒",this.second--,setTimeout(this.changeMsg,1e3)):(this.send_btn_txt="获取验证码",this.second=60,this.is_send=!1)}}};t.default=s}}]);