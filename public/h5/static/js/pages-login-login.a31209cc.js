(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-login-login"],{"1b51":function(t,n,i){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var e={data:function(){return{background:"",listData:[],invitation_id:0}},onLoad:function(t){this.invitation_id=uni.getStorageSync("invitation_id")?uni.getStorageSync("invitation_id"):0},methods:{onNotLogin:function(){uni.switchTab({url:"/pages/index/index"})},getUserInfo:function(){var t=this;wx.getUserProfile({lang:"zh_CN",desc:"用于完善会员资料",success:function(n){if("getUserProfile:ok"!==n.errMsg)return!1;uni.showLoading({title:"正在登录",mask:!0}),wx.login({success:function(i){t._post("user.user/login",{invitation_id:t.invitation_id,code:i.code,user_info:n.rawData,encrypted_data:encodeURIComponent(n.encryptedData),iv:encodeURIComponent(n.iv),signature:n.signature,referee_id:uni.getStorageSync("referee_id"),source:"wx"},(function(t){uni.setStorageSync("token",t.data.token),uni.setStorageSync("user_id",t.data.user_id),uni.navigateBack()}),!1,(function(){uni.hideLoading()}))}})}})}}};n.default=e},"203d":function(t,n,i){var e=i("4e44");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var a=i("4f06").default;a("e2d9b7c8",e,!0,{sourceMap:!1,shadowMode:!1})},"3add":function(t,n,i){"use strict";var e=i("203d"),a=i.n(e);a.a},"4e44":function(t,n,i){var e=i("24fb");n=e(!1),n.push([t.i,".login-container[data-v-3b236272]{padding:%?30?%}.wechatapp[data-v-3b236272]{padding:%?80?% 0 %?48?%;border-bottom:%?1?% solid #e3e3e3;margin-bottom:%?72?%;text-align:center}.wechatapp .header[data-v-3b236272]{width:%?190?%;height:%?190?%;border:2px solid #fff;margin:%?0?% auto 0;border-radius:50%;overflow:hidden;box-shadow:1px 0 5px rgba(50,50,50,.3)}.auth-title[data-v-3b236272]{color:#585858;font-size:%?34?%;margin-bottom:%?40?%}.auth-subtitle[data-v-3b236272]{color:#888;margin-bottom:%?88?%;font-size:%?28?%}.login-btn[data-v-3b236272]{padding:0 %?20?%}.login-btn uni-button[data-v-3b236272]{height:%?88?%;line-height:%?88?%;background:#04be01;color:#fff;font-size:%?30?%;border-radius:%?999?%;text-align:center}.no-login-btn[data-v-3b236272]{margin-top:%?20?%;padding:0 %?20?%}.no-login-btn uni-button[data-v-3b236272]{height:%?88?%;line-height:%?88?%;background:#dfdfdf;color:#fff;font-size:%?30?%;border-radius:%?999?%;text-align:center}",""]),t.exports=n},b85f:function(t,n,i){"use strict";var e;i.d(n,"b",(function(){return a})),i.d(n,"c",(function(){return o})),i.d(n,"a",(function(){return e}));var a=function(){var t=this,n=t.$createElement,i=t._self._c||n;return i("v-uni-view",{staticClass:"login-container"},[i("v-uni-view",{staticClass:"wechatapp"},[i("v-uni-view",{staticClass:"header"},[i("v-uni-open-data",{attrs:{type:"userAvatarUrl"}})],1)],1),i("v-uni-view",{staticClass:"auth-title"},[t._v("申请获取以下权限")]),i("v-uni-view",{staticClass:"auth-subtitle"},[t._v("获得你的公开信息（昵称、头像等）")]),i("v-uni-view",{staticClass:"login-btn"}),i("v-uni-view",{staticClass:"no-login-btn"},[i("v-uni-button",{staticClass:"btn-normal",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.onNotLogin.apply(void 0,arguments)}}},[t._v("暂不登录")])],1)],1)},o=[]},b9d5:function(t,n,i){"use strict";i.r(n);var e=i("1b51"),a=i.n(e);for(var o in e)"default"!==o&&function(t){i.d(n,t,(function(){return e[t]}))}(o);n["default"]=a.a},d8c9:function(t,n,i){"use strict";i.r(n);var e=i("b85f"),a=i("b9d5");for(var o in a)"default"!==o&&function(t){i.d(n,t,(function(){return a[t]}))}(o);i("3add");var r,d=i("f0c5"),u=Object(d["a"])(a["default"],e["b"],e["c"],!1,null,"3b236272",null,!1,e["a"],r);n["default"]=u.exports}}]);