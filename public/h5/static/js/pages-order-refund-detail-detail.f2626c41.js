(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-refund-detail-detail"],{"2f7c":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var s={data:function(){return{loadding:!0,indicatorDots:!0,autoplay:!0,interval:2e3,duration:500,expressList:{},index:-1,order_refund_id:0,detail:{address:{}},express_id:0,temlIds:[]}},onLoad:function(t){this.order_refund_id=t.order_refund_id},mounted:function(){this.getData()},methods:{getData:function(){var t=this;uni.showLoading({title:"加载中"});var e=t.order_refund_id;t._get("user.refund/detail",{order_refund_id:e,platform:t.getPlatform()},(function(e){uni.hideLoading(),t.detail=e.data.detail,t.expressList=e.data.expressList,t.temlIds=e.data.template_arr}))},onExpressChange:function(t){this.index=t.target.value,this.express_id=this.expressList[this.index].express_id},formSubmit:function(t){var e=this,i=t.detail.value;i.order_refund_id=e.order_refund_id,i.express_id=e.express_id;var s=function(){uni.showLoading({title:"正在提交",mask:!0}),e._post("user.refund/delivery",i,(function(t){uni.hideLoading(),uni.showToast({title:t.msg,duration:3e3,complete:function(){e.gotoPage("/pages/order/refund/detail/detail?order_refund_id="+e.order_refund_id)}})}))};e.subMessage(e.temlIds,s)}}};e.default=s},"489b":function(t,e,i){"use strict";var s;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return s}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"order-refund-detail pb100"},[i("v-uni-view",{staticClass:"order-state d-s-c"},[i("v-uni-view",{staticClass:"icon-box"},[i("span",{staticClass:"icon iconfont icon-gantanhao"})]),i("v-uni-view",{staticClass:"state-cont flex-1"},[i("v-uni-view",{staticClass:"state-txt d-b-c"},[i("v-uni-text",{staticClass:"desc f34"},[t._v(t._s(t.detail.state_text))])],1)],1),i("v-uni-view",{staticClass:"dot-bg"})],1),i("v-uni-view",{staticClass:"p30 mt20 bg-white"},[i("v-uni-view",{staticClass:"one-product border-b-e d-s-c pb20"},[i("v-uni-view",{staticClass:"cover"},[i("v-uni-image",{attrs:{src:t.detail.orderproduct.image.file_path,mode:"aspectFit"}})],1),i("v-uni-view",{staticClass:"flex-1"},[i("v-uni-view",{staticClass:"pro-info"},[t._v(t._s(t.detail.orderproduct.product_name))]),i("v-uni-view",{staticClass:"pt10 p-0-30"},[i("v-uni-text",{staticClass:"f24 gray9"})],1)],1)],1),i("v-uni-view",{staticClass:"d-e-c pt20 lh150"},[i("v-uni-view",{staticClass:"f24"},[t._v("商品金额："),i("v-uni-text",{staticClass:"red"},[t._v("¥"+t._s(t.detail.orderproduct.total_price))])],1)],1),i("v-uni-view",{staticClass:"d-e-c pt10 lh150"},[i("v-uni-view",{staticClass:"f24"},[t._v("订单实付金额："),i("v-uni-text",{staticClass:"red"},[t._v("¥"+t._s(t.detail.orderproduct.total_pay_price))])],1)],1)],1),20==t.detail.status.value&&10==t.detail.type.value?i("v-uni-view",{staticClass:"group bg-white"},[i("v-uni-text",{staticClass:"gray9"},[t._v("已退款金额：")]),i("v-uni-text",{staticClass:"gray9"},[t._v("￥"+t._s(t.detail.refund_money))])],1):t._e(),i("v-uni-view",{staticClass:"group bg-white f24"},[i("v-uni-view",{staticClass:"p-20-0 border-b f34"},[t._v("申请退货信息")]),i("v-uni-view",{staticClass:"p-20-0 f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("售后类型：")]),i("v-uni-text",[t._v(t._s(t.detail.type.text))])],1),i("v-uni-view",{staticClass:"p-20-0 f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("申请原因：")]),i("v-uni-text",[t._v(t._s(t.detail.apply_desc))])],1),i("v-uni-view",{staticClass:"p-20-0 upload-list d-s-s f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("申请凭证：")]),i("v-uni-view",{staticClass:"d-s-s f-w"},[t.detail.image.length>0?t._l(t.detail.image,(function(t,e){return i("v-uni-view",{key:e,staticClass:"item"},[i("v-uni-image",{attrs:{src:t.file_path,mode:"aspectFit"}})],1)})):[t._v("无")]],2)],1)],1),10==t.detail.status.value?i("v-uni-view",{staticClass:"group bg-white"},[i("v-uni-view",{staticClass:"p-20-0 border-b f34"},[t._v("拒绝理由")]),i("v-uni-view",{staticClass:"p-20-0"},[i("v-uni-text",{staticClass:"red f28"},[t._v(t._s(t.detail.refuse_desc))])],1)],1):t._e(),10==t.detail.is_agree.value?i("v-uni-view",{staticClass:"group bg-white"},[i("v-uni-view",{staticClass:"p-20-0 border-b f34"},[t._v("退货地址")]),i("v-uni-view",{staticClass:"pt30 f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("收货人：")]),i("v-uni-text",[t._v(t._s(t.detail.address.name))])],1),i("v-uni-view",{staticClass:"pt30 f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("联系电话：")]),i("v-uni-text",[t._v(t._s(t.detail.address.phone))])],1),i("v-uni-view",{staticClass:"pt30 f28"},[i("v-uni-text",{staticClass:"gray9"},[t._v("详情地址：")]),i("v-uni-text",[t._v(t._s(t.detail.address.detail))])],1),t.detail.express_no?i("v-uni-view",{staticClass:"pt30 d-b-c f28"},[i("v-uni-view",{},[i("v-uni-text",{staticClass:"gray9"},[t._v("物流公司：")]),i("v-uni-text",[t._v(t._s(t.detail.express.express_name))])],1),i("v-uni-view",{},[i("v-uni-text",{staticClass:"gray9"},[t._v("物流单号：")]),i("v-uni-text",[t._v(t._s(t.detail.express_no))])],1)],1):t._e(),i("v-uni-view",{staticClass:"mt20 pb20 border-t gray9"},[i("v-uni-view",{staticClass:"pt20"},[t._v("· 未与卖家协商一致情况下，请勿寄到付或平邮")]),i("v-uni-view",{staticClass:"pt10"},[t._v("· 请填写真实有效物流信息")])],1)],1):t._e(),10==t.detail.type.value&&10==t.detail.is_agree.value&&0==t.detail.is_user_send?i("v-uni-form",{attrs:{"report-submit":!0},on:{submit:function(e){arguments[0]=e=t.$handleEvent(e),t.formSubmit.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"group bg-white"},[i("v-uni-view",{staticClass:"p-20-0 border-b f34"},[t._v("填写物流信息")]),i("v-uni-view",{staticClass:"p-20-0 d-s-c"},[i("v-uni-view",{staticClass:"gray9"},[t._v("物流公司：")]),i("v-uni-view",{staticClass:"flex-1 p20 border"},[i("v-uni-picker",{attrs:{mode:"selector",range:t.expressList,"range-key":"express_name",value:t.index},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.onExpressChange.apply(void 0,arguments)}}},[t.index>-1?i("v-uni-text",[t._v(t._s(t.expressList[t.index].express_name))]):i("v-uni-text",{staticClass:"col-80"},[t._v("请选择物流公司")])],1)],1)],1),i("v-uni-view",{staticClass:"p-20-0 d-s-c"},[i("v-uni-view",{staticClass:"gray9"},[t._v("物流单号：")]),i("v-uni-view",{staticClass:"flex-1 border"},[i("v-uni-input",{staticClass:"p10",attrs:{placeholder:"请填写物流单号",name:"express_no"}})],1)],1),i("v-uni-view",{staticClass:"mt20"},[i("v-uni-button",{staticClass:"btn-red",attrs:{formType:"submit"}},[t._v("确认发货")])],1)],1)],1):t._e()],1)},n=[]},"5cce":function(t,e,i){"use strict";var s=i("6e9b"),a=i.n(s);a.a},"6e9b":function(t,e,i){var s=i("7400");"string"===typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);var a=i("4f06").default;a("6068feaa",s,!0,{sourceMap:!1,shadowMode:!1})},7400:function(t,e,i){var s=i("24fb");e=s(!1),e.push([t.i,".order-refund-detail .btn-red[data-v-23ae0428]{height:%?88?%;line-height:%?88?%;border-radius:%?44?%;box-shadow:0 %?8?% %?16?% 0 rgba(226,35,26,.6)}",""]),t.exports=e},"842f":function(t,e,i){"use strict";i.r(e);var s=i("489b"),a=i("adf1");for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);i("5cce");var r,v=i("f0c5"),u=Object(v["a"])(a["default"],s["b"],s["c"],!1,null,"23ae0428",null,!1,s["a"],r);e["default"]=u.exports},adf1:function(t,e,i){"use strict";i.r(e);var s=i("2f7c"),a=i.n(s);for(var n in s)"default"!==n&&function(t){i.d(e,t,(function(){return s[t]}))}(n);e["default"]=a.a}}]);