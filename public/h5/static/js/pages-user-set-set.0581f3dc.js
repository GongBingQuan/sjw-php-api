(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-set-set"],{3450:function(t,e,n){"use strict";n.r(e);var i=n("be05"),s=n("d6a7");for(var a in s)"default"!==a&&function(t){n.d(e,t,(function(){return s[t]}))}(a);n("e5cb");var o,u=n("f0c5"),c=Object(u["a"])(s["default"],i["b"],i["c"],!1,null,"1e6b81c7",null,!1,i["a"],o);e["default"]=c.exports},6254:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".address-form .key-name[data-v-1e6b81c7]{width:%?200?%}.address-form .btn-red[data-v-1e6b81c7]{height:%?88?%;line-height:%?88?%;border-radius:%?44?%;box-shadow:0 %?8?% %?16?% 0 rgba(226,35,26,.6)}.setup-btn[data-v-1e6b81c7]{position:fixed;bottom:%?20?%;left:5%;width:90%;height:%?80?%;line-height:%?80?%;border-radius:%?80?%;background-color:#fd3826;color:#fff;font-size:%?30?%;display:flex;justify-content:center;margin:0 auto}",""]),t.exports=e},"7b64":function(t,e,n){var i=n("6254");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var s=n("4f06").default;s("414d7228",i,!0,{sourceMap:!1,shadowMode:!1})},"7da6":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={components:{},data:function(){return{userInfo:{}}},onShow:function(){this.getData()},methods:{getData:function(){var t=this;uni.showLoading({title:"加载中"}),t._get("user.index/setting",{},(function(e){t.userInfo=e.data.userInfo,uni.hideLoading()}))},gotoBind:function(){this.gotoPage("/pages/login/bindmobile")},logout:function(){uni.clearStorageSync("token"),uni.clearStorageSync("user_id"),uni.switchTab({url:"/pages/index/index"})}}};e.default=i},be05:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return s})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"address-form"},[n("v-uni-view",{staticClass:"bg-white p-0-30 f30"},[n("v-uni-view",{staticClass:"d-b-c p-30-0 border-b"},[n("v-uni-text",{staticClass:"key-name"},[t._v("会员ID")]),n("v-uni-view",{staticClass:"d-e-c"},[n("v-uni-text",{staticClass:"mr20"},[t._v(t._s(t.userInfo.user_id))])],1)],1),n("v-uni-view",{staticClass:"d-b-c p-30-0 border-b"},[n("v-uni-text",{staticClass:"key-name"},[t._v("昵称")]),n("v-uni-view",{staticClass:"d-e-c"},[n("v-uni-text",{staticClass:"mr20"},[t._v(t._s(t.userInfo.nickName))])],1)],1),n("v-uni-view",{staticClass:"d-b-c p-30-0"},[n("v-uni-text",{staticClass:"key-name"},[t._v("手机号码")]),t.userInfo.mobile?n("v-uni-view",{staticClass:"d-e-c"},[n("v-uni-text",{staticClass:"mr20"},[t._v(t._s(t.userInfo.mobile))])],1):t._e(),t.userInfo.mobile?t._e():n("v-uni-view",{staticClass:"d-e-c",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.gotoBind.apply(void 0,arguments)}}},[n("v-uni-text",{staticClass:"mr20"},[t._v("未绑定")]),n("v-uni-text",{staticClass:"iconfont icon-jiantou"})],1)],1),n("v-uni-view",{staticClass:"setup-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.logout()}}},[t._v("退出登录")])],1)],1)},a=[]},d6a7:function(t,e,n){"use strict";n.r(e);var i=n("7da6"),s=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=s.a},e5cb:function(t,e,n){"use strict";var i=n("7b64"),s=n.n(i);s.a}}]);