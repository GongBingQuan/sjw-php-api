(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-diy-page-diy-page"],{"41a7":function(t,e,a){"use strict";var n=a("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n(a("d76f")),o={components:{diy:i.default},data:function(){return{page_id:null,items:{},page_info:{}}},onLoad:function(t){this.page_id=t.page_id,this.getData()},methods:{getData:function(t){var e=this;e._get("index/index",{page_id:e.page_id},(function(t){e.page_info=t.data.page,e.items=t.data.items,e.setPage(e.page_info)}))},setPage:function(t){uni.setNavigationBarTitle({title:t.params.name});var e="#000000";"white"==t.style.titleTextColor&&(e="#ffffff"),uni.setNavigationBarColor({frontColor:e,backgroundColor:t.style.titleBackgroundColor})}}};e.default=o},"69e8":function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={diy:a("d76f").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"diy-page"},[a("diy",{attrs:{diyItems:t.items}})],1)},o=[]},"93ce":function(t,e,a){"use strict";a.r(e);var n=a("69e8"),i=a("e52d");for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);var r,u=a("f0c5"),d=Object(u["a"])(i["default"],n["b"],n["c"],!1,null,"17ecd670",null,!1,n["a"],r);e["default"]=d.exports},e52d:function(t,e,a){"use strict";a.r(e);var n=a("41a7"),i=a.n(n);for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a}}]);