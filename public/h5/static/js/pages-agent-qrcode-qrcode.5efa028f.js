(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-agent-qrcode-qrcode"],{"3e2f":function(t,e,n){"use strict";n.r(e);var i=n("d47a"),o=n("dd19");for(var a in o)"default"!==a&&function(t){n.d(e,t,(function(){return o[t]}))}(a);n("d3d5");var u,r=n("f0c5"),d=Object(r["a"])(o["default"],i["b"],i["c"],!1,null,"7c735ade",null,!1,i["a"],u);e["default"]=d.exports},d3d5:function(t,e,n){"use strict";var i=n("e51d"),o=n.n(i);o.a},d47a:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return a})),n.d(e,"a",(function(){return i}));var o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"qrcode"},[n("v-uni-image",{attrs:{src:t.qrcode_url,mode:"widthFix"}}),n("v-uni-view",{staticClass:"btns-wrap"},[n("v-uni-view",{staticClass:"f34 tc ww100",attrs:{type:"default"}},[t._v("长按保存图片")])],1)],1)},a=[]},dd19:function(t,e,n){"use strict";n.r(e);var i=n("f85e"),o=n.n(i);for(var a in i)"default"!==a&&function(t){n.d(e,t,(function(){return i[t]}))}(a);e["default"]=o.a},de2c9:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".qrcode uni-image[data-v-7c735ade]{width:100%}.btns-wrap[data-v-7c735ade]{position:fixed;height:%?88?%;right:0;bottom:0;left:0;display:flex;z-index:10}.btns-wrap .btn-red[data-v-7c735ade]{width:100%;height:%?88?%;line-height:%?88?%;border-radius:0}",""]),t.exports=e},e51d:function(t,e,n){var i=n("de2c9");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var o=n("4f06").default;o("149be6dc",i,!0,{sourceMap:!1,shadowMode:!1})},f85e:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{qrcode_url:""}},mounted:function(){this.getData()},methods:{getData:function(){var t=this;uni.showLoading({title:"加载中"});var e=t.getPlatform();t._get("plus.agent.qrcode/poster",{source:e},(function(e){uni.hideLoading(),t.qrcode_url=e.data.qrcode}))},savePosterImg:function(){var t=this;uni.showLoading({title:"加载中"}),uni.downloadFile({url:t.qrcode_url,success:function(e){uni.hideLoading(),uni.saveImageToPhotosAlbum({filePath:e.tempFilePath,success:function(e){uni.showToast({title:"保存成功",icon:"success",duration:2e3}),t.isCreatedImg=!1},fail:function(t){console.log(t.errMsg),"saveImageToPhotosAlbum:fail auth deny"===t.errMsg&&(uni.showToast({title:"请允许访问相册后重试",icon:"none",duration:1e3}),setTimeout((function(){uni.openSetting()}),1e3))},complete:function(t){console.log("complete")}})}})}}};e.default=i}}]);