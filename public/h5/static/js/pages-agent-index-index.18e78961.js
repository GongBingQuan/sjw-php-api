(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-agent-index-index"],{"18d8":function(t,a,i){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var e={data:function(){return{loadding:!0,indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,is_agent:!1,isData:!1,agent:{},top_background:"",info_words:{},words:{},user:{},titel:""}},onLoad:function(t){t.is_agent&&(this.is_agent=t.is_agent)},onShow:function(){uni.showLoading({title:"加载中"}),this.getData()},methods:{getData:function(){var t=this;t._get("user.agent/center",{},(function(a){t.info_words=a.data.words,uni.setNavigationBarTitle({title:""!=t.info_words.index.title.value?t.info_words.index.title.value:t.info_words.index.title.default}),t.titel=a.data.words.index.title.value,t.is_agent=a.data.is_agent,t.top_background=a.data.background,t.agent=a.data.agent,t.user=a.data.user,t.isData=!0,t.loadding=!1,uni.hideLoading()}))},applyagent:function(){this.gotoPage("/pages/agent/apply/apply")},gotoShop:function(){this.gotoPage("/pages/index/index")},gotoCash:function(){this.gotoPage("/pages/agent/cash/apply/apply")},goback:function(){uni.navigateBack()}}};a.default=e},2310:function(t,a,i){var e=i("685d");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var n=i("4f06").default;n("1a5f4015",e,!0,{sourceMap:!1,shadowMode:!1})},4892:function(t,a,i){t.exports=i.p+"static/img/icon-zijinmingxi.f659f731.png"},"5dcf":function(t,a,i){t.exports=i.p+"static/img/icon-fenxiaodingdan.2f97537c.png"},"685d":function(t,a,i){var e=i("24fb");a=e(!1),a.push([t.i,"uni-page-body[data-v-236cf89a]{background-color:#f2f2f2}.index-agent .banner[data-v-236cf89a]{position:absolute;width:100%;z-index:0;min-height:%?167?%;\n\t/* padding-bottom: 60rpx; */background-repeat:no-repeat;background-size:100%}.index-agent .banner uni-image[data-v-236cf89a]{width:100%}.no-agent[data-v-236cf89a]{padding-top:%?190?%}.no-agent-img[data-v-236cf89a]{padding-top:%?60?%}.no-agent-img uni-image[data-v-236cf89a]{width:%?300?%}.agent-wrap[data-v-236cf89a]{border-radius:%?12?%;background-position:-8px %?52?%;background-size:100% 100%;background-image:radial-gradient(circle at 8px,transparent 0,transparent 8px,#fff 0,#fff 100%);padding:%?31?% %?25?% %?36?% %?25?%}.index-agent .agent-wrap .iconfont[data-v-236cf89a]{font-size:%?60?%}.index-agent .btn-gcred[data-v-236cf89a]{height:%?88?%;line-height:%?88?%;border-radius:%?44?%}.reg180[data-v-236cf89a]{padding-right:%?20?%;text-align:right;-webkit-transform:rotateY(180deg);transform:rotateY(180deg);position:absolute;bottom:0}.icon-jiantou[data-v-236cf89a]{color:#fff;font-size:%?30?%}.head_top[data-v-236cf89a]{position:absolute;width:100%;height:30px;line-height:30px;color:#fff;font-size:%?32?%;z-index:2}.top_dash[data-v-236cf89a]{border-bottom:%?1?% dashed #d9d9d9;padding-bottom:9px}.agent_index_img[data-v-236cf89a]{width:%?90?%;height:%?90?%}body.?%PAGE?%[data-v-236cf89a]{background-color:#f2f2f2}",""]),t.exports=a},"6f11":function(t,a,i){"use strict";var e;i.d(a,"b",(function(){return n})),i.d(a,"c",(function(){return s})),i.d(a,"a",(function(){return e}));var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return t.loadding?t._e():e("v-uni-view",{staticClass:"index-agent o-h"},[e("v-uni-view",{staticClass:"banner d-c-c d-c"},[e("v-uni-image",{attrs:{src:t.top_background,mode:"widthFix"}})],1),t.is_agent&&t.isData?[e("v-uni-view",{staticClass:"agent-wrap pr m-0-20",staticStyle:{"margin-top":"300rpx"}},[e("v-uni-view",{staticClass:"d-b-c border-b pb30 f28 lh150"},[e("v-uni-view",[e("v-uni-text",{staticClass:"gray3 f32"},[t._v("用户名：")]),e("v-uni-text",{staticClass:"gray3 f32"},[t._v(t._s(t.user.nickName))])],1),e("v-uni-view",[e("v-uni-text",{staticClass:"gray3 f32"},[t._v(t._s(t.info_words.index.words.referee.value)+"：")]),e("v-uni-text",{staticClass:"f32 gray3"},[t._v(t._s(t.agent.referee?t.agent.referee.nickName:"平台"))])],1)],1),e("v-uni-view",{staticClass:"d-s-c p-30-0 top_dash"},[e("v-uni-view",{staticClass:"flex-1 d-c-c d-c"},[e("v-uni-view",{staticClass:"redF6"},[e("v-uni-text",{staticClass:"f24"},[t._v("￥")]),e("v-uni-text",{staticClass:"f40"},[t._v(t._s(t.agent.money))])],1),e("v-uni-view",{staticClass:"pt20 f26 gray3"},[t._v(t._s(t.info_words.index.words.money.value))])],1),e("v-uni-view",{staticClass:"flex-1 d-c-c d-c"},[e("v-uni-view",{},[e("v-uni-text",{staticClass:"f24"},[t._v("￥")]),e("v-uni-text",{staticClass:"f40"},[t._v(t._s(t.agent.freeze_money))])],1),e("v-uni-view",{staticClass:"pt20 f28 gray3"},[t._v(t._s(t.info_words.index.words.freeze_money.value))])],1),e("v-uni-view",{staticClass:"flex-1 d-c-c d-c"},[e("v-uni-view",{},[e("v-uni-text",{staticClass:"f24"},[t._v("￥")]),e("v-uni-text",{staticClass:"f40"},[t._v(t._s(t.agent.total_money))])],1),e("v-uni-view",{staticClass:"pt20 f28 gray3"},[t._v(t._s(t.info_words.index.words.total_money.value))])],1)],1),e("v-uni-view",{staticClass:"d-c-c pt30"},[e("v-uni-button",{staticClass:"btn-gcred flex-1",attrs:{type:"primary"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.gotoCash.apply(void 0,arguments)}}},[t._v(t._s(t.info_words.index.words.cash.value))])],1)],1),e("v-uni-view",{staticClass:"agent-wrap m-0-20 p30 d-s-c f-w mt20 bg-white"},[e("v-uni-view",{staticClass:"d-c-c d-c flex-1",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.gotoPage("/pages/agent/cash/list/list")}}},[e("v-uni-view",[e("v-uni-image",{staticClass:"agent_index_img",attrs:{src:i("4892"),mode:""}})],1),e("v-uni-text",{staticClass:"pt10 f26 mt20"},[t._v(t._s(t.info_words.cash_list.title.value))])],1),e("v-uni-view",{staticClass:"d-c-c d-c flex-1",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.gotoPage("/pages/agent/order/order")}}},[e("v-uni-view",[e("v-uni-image",{staticClass:"agent_index_img",attrs:{src:i("5dcf"),mode:""}})],1),e("v-uni-text",{staticClass:"pt10 f26 mt20"},[t._v(t._s(t.info_words.order.title.value))])],1),e("v-uni-view",{staticClass:"d-c-c d-c flex-1",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.gotoPage("/pages/agent/team/team")}}},[e("v-uni-view",[e("v-uni-image",{staticClass:"agent_index_img",attrs:{src:i("d7e7"),mode:""}})],1),e("v-uni-text",{staticClass:"pt10 f26 mt20"},[t._v(t._s(t.info_words.team.title.value))])],1),e("v-uni-view",{staticClass:"d-c-c d-c flex-1",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.gotoPage("/pages/agent/qrcode/qrcode")}}},[e("v-uni-view",[e("v-uni-image",{staticClass:"agent_index_img",attrs:{src:i("791c"),mode:""}})],1),e("v-uni-text",{staticClass:"pt10 f26 mt20"},[t._v(t._s(t.info_words.qrcode.title.value))])],1)],1)]:t._e(),!t.is_agent&&t.isData?[e("v-uni-view",{staticClass:"no-agent"},[e("v-uni-view",{staticClass:"mt50 p-0-20 red f34 tc"},[t._v(t._s(t.info_words.index.words.not_agent.value))]),e("v-uni-view",{staticClass:"p30 mt30"},[e("v-uni-button",{staticClass:"btn-gcred",attrs:{type:"primary"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.applyagent.apply(void 0,arguments)}}},[t._v(t._s(t.info_words.index.words.apply_now.value))])],1)],1)]:t._e()],2)},s=[]},"791c":function(t,a,i){t.exports=i.p+"static/img/icon-erweima.713ea666.png"},"80ac":function(t,a,i){"use strict";var e=i("2310"),n=i.n(e);n.a},"86fd":function(t,a,i){"use strict";i.r(a);var e=i("6f11"),n=i("ed6f");for(var s in n)"default"!==s&&function(t){i.d(a,t,(function(){return n[t]}))}(s);i("80ac");var o,d=i("f0c5"),c=Object(d["a"])(n["default"],e["b"],e["c"],!1,null,"236cf89a",null,!1,e["a"],o);a["default"]=c.exports},d7e7:function(t,a,i){t.exports=i.p+"static/img/icon-tuandui.b62c9814.png"},ed6f:function(t,a,i){"use strict";i.r(a);var e=i("18d8"),n=i.n(e);for(var s in e)"default"!==s&&function(t){i.d(a,t,(function(){return e[t]}))}(s);a["default"]=n.a}}]);