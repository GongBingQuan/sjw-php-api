webpackJsonp([1],{"7qo3":function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=new(e("7+uW").default),s={components:{},data:function(){return{active_menu:null,active_child:0,menuList:[{name:"插件",icon:"icon-chajian1",path:"/plugs",children:[{name:"全部插件",icon:null,path:"/plugs/plugs/Index"}]},{name:"权限",icon:"icon-authority",path:"/access",children:[{name:"权限列表",icon:null,path:"/access/Index"}]},{name:"商城",icon:"icon-zhuye",path:"/shop",children:[{name:"商城列表",icon:null,path:"/shop/Index"}]},{name:"消息",icon:"icon-xiaoxi1",path:"/message",children:[{name:"消息列表",icon:null,path:"/message/Index"}]},{name:"地区",icon:"icon-diqu",path:"/region",children:[{name:"地区列表",icon:null,path:"/region/Index"}]},{name:"密码",icon:"icon-authority1",path:"/password",children:[{name:"修改密码",icon:null,path:"/password/Index"}]}]}},created:function(){this.selectMenu(this.$route)},watch:{$route:function(n,t){var e=n.path.split("/").length,i=t.path.split("/").length;this.transitionName=e<i?"slide-right":"slide-left",this.selectMenu(n)}},methods:{selectMenu:function(n){for(var t="首页",e="/"+n.path.split("/")[1],s=0;s<this.menuList.length;s++){if(e==this.menuList[s].path){t=this.menuList[s].name,this.active_menu=s;var a=n.path;if(this.menuList[s].children)for(var c=this.menuList[s].children,u=0;u<c.length;u++){if(a==c[u].path){t=c[u].name,this.active_child=u;break}this.active_child=0}break}this.active_menu=null}this.$emit("selectMenu",this.active_menu),i.$emit("MenuName",t)},choseMenu:function(n){if(null!=n){var t=n.path;this.$router.push(t)}else this.$router.push("/")}}},a={render:function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("div",{staticClass:"left-menu-wrapper"},[e("div",{staticClass:"menu-wrapper"},[e("div",{staticClass:"home-login"},[e("div",{class:null!=n.active_menu?"home-icon":"home-icon router-link-active",on:{click:function(t){return n.choseMenu(null)}}},[e("span",{staticClass:"icon iconfont icon-tubiaozhizuomoban-"})])]),n._v(" "),e("div",{staticClass:"nav-wrapper"},[e("div",{staticClass:"first-menu-content"},[e("ul",{staticClass:"nav-ul"},n._l(n.menuList,function(t,i){return e("li",{key:i,class:n.active_menu==i?"menu-item router-link-active":"menu-item",on:{click:function(e){return n.choseMenu(t)}}},[e("div",{staticClass:"item-box"},[e("span",{class:"icon iconfont menu-item-icon "+t.icon}),n._v(" "),e("span",[n._v(n._s(t.name))])])])}),0)])])]),n._v(" "),e("div",{staticClass:"child-menu-wrapper"},[e("div",{staticClass:"child-menu right-animation"},[null!=n.active_menu?e("ul",n._l(n.menuList[n.active_menu].children,function(t,i){return e("li",{key:i,class:n.active_child==i?"router-link router-link-active":"router-link",on:{click:function(e){return n.choseMenu(t)}}},[e("span",{staticClass:"name"},[n._v(n._s(t.name))])])}),0):n._e()])])])},staticRenderFns:[]};var c=e("VU/8")(s,a,!1,function(n){e("b6ou")},null,null).exports,u=e("vMJZ"),o=e("aFVK"),l={data:function(){return{menu_title:"首页",username:""}},created:function(){var n=this;i.$on("MenuName",function(t){n.menu_title=t}),this.username=Object(o.d)("userinfo")},methods:{login_out:function(){var n=this;this.$confirm("此操作将退出登录, 是否继续?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){u.a.loginOut(!0).then(function(t){Object(o.b)("isLogin"),n.$router.push({path:"/Login"})}).catch(function(n){})}).catch(function(){n.$message({type:"info",message:"已取消退出"})})}}},r={render:function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("div",{staticClass:"common-header"},[e("div",{staticClass:"breadcrumb"},[e("div",{staticClass:"baseInfo-left-base"},[e("span",{staticClass:"name"},[n._v(n._s(n.menu_title))])]),n._v(" "),e("div",{staticClass:"header-navbar"},[e("div",{staticClass:"header-navbar-icon"},[e("span",{staticClass:"ml4 icon iconfont icon-geren9"}),n._v(" "),e("span",{staticClass:"text ml4"},[n._v(n._s(n.username)+"，欢迎您！")])]),n._v(" "),e("div",{staticClass:"header-navbar-icon",on:{click:function(t){return n.login_out()}}},[e("span",{staticClass:"icon iconfont icon-tuichu"}),n._v(" "),e("span",{staticClass:"text ml4"},[n._v("退出")])])])])])},staticRenderFns:[]};var h={components:{Head:e("VU/8")(l,r,!1,function(n){e("QoOa")},null,null).exports},data:function(){return{}},created:function(){},methods:{}},m={render:function(){var n=this.$createElement,t=this._self._c||n;return t("div",{staticClass:"right-content"},[t("Head"),this._v(" "),t("div",{staticClass:"right-content-box"},[t("div",{staticClass:"subject-wrap"},[t("router-view")],1)])],1)},staticRenderFns:[]};var d={components:{LeftMenu:c,RightContent:e("VU/8")(h,m,!1,function(n){e("zH7R")},null,null).exports},data:function(){return{hasChild:null}},created:function(){},methods:{selectMenuFunc:function(n){this.hasChild=n}}},v={render:function(){var n=this.$createElement,t=this._self._c||n;return t("div",{class:(this.hasChild,"main right-big")},[t("LeftMenu",{on:{selectMenu:this.selectMenuFunc}}),this._v(" "),t("RightContent")],1)},staticRenderFns:[]};var f=e("VU/8")(d,v,!1,function(n){e("dHu4")},null,null);t.default=f.exports},QoOa:function(n,t){},b6ou:function(n,t){},dHu4:function(n,t){},zH7R:function(n,t){}});
//# sourceMappingURL=1.555bb03623d3b6b17c0d.js.map