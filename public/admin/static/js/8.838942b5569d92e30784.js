webpackJsonp([8],{"18M7":function(e,t){},"25Sm":function(e,t,o){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=o("vLgD"),r={shopList:function(e,t){return a.a._post("/admin/shop/index",e,t)},addShop:function(e,t){return a.a._post("/admin/shop/add",e,t)},editShop:function(e,t){return a.a._post("/admin/shop/edit",e,t)},updateStatus:function(e,t){return a.a._post("/admin/shop/updateStatus",e,t)},storeEnter:function(e,t){return a.a._post("/admin/shop/enter",e,t)},deleteShop:function(e,t){return a.a._post("/admin/shop/delete",e,t)}},i={data:function(){var e=this;return{form:{no_expire:!1},formLabelWidth:"120px",dialogVisible:!1,loading:!1,rules:{app_name:[{validator:function(t,o,a){return o?e.$filter.isAllSpace(o)?a(new Error("不能全是空格")):void a():a(new Error("请输入商城名称"))},required:!0,trigger:"blur"}],user_name:[{validator:function(t,o,a){return e.$filter.replaceSpace(o)?e.$filter.hasSpace(o)?a(new Error("不能包含空格")):void a():a(new Error("商家账户名"))},required:!0,trigger:"blur"}],password:[{validator:function(t,o,a){if(o){if(e.$filter.hasSpace(o))return a(new Error("不能包含空格"));if(o.length<6)return a(new Error("长度不能小于6位"));a()}else a()},trigger:"change"}],password_confirm:[{validator:function(t,o,a){e.form.password&&o!==e.form.password?a(new Error("确认密码不一致")):a()},trigger:"blur"}]}}},props:["open_edit","curModel"],created:function(){this.dialogVisible=this.open_edit,this.form=this.curModel},methods:{addClick:function(){var e=this,t=this.form;e.$refs.form.validate(function(o){o&&(e.loading=!0,r.editShop(t,!0).then(function(t){e.loading=!1,e.$message({message:"恭喜你，修改成功",type:"success"}),e.dialogFormVisible(!0)}).catch(function(t){e.loading=!1}))})},dialogFormVisible:function(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})}}},l={render:function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("el-dialog",{attrs:{title:"编辑小程序商城",visible:e.dialogVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(t){e.dialogVisible=t},close:e.dialogFormVisible}},[o("el-form",{ref:"form",attrs:{size:"small",model:e.form,rules:e.rules}},[o("div",{staticStyle:{height:"0",overflow:"hidden"}},[o("input",{attrs:{type:"password"}})]),e._v(" "),o("el-form-item",{attrs:{label:"商城名称","label-width":e.formLabelWidth,prop:"app_name"}},[o("el-input",{attrs:{autocomplete:"off",placeholder:"请输入商城名称"},model:{value:e.form.app_name,callback:function(t){e.$set(e.form,"app_name",t)},expression:"form.app_name"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"过期时间","label-width":e.formLabelWidth,prop:"expire_time"}},[o("el-date-picker",{attrs:{type:"date",placeholder:"过期时间","value-format":"yyyy-MM-dd",disabled:e.form.no_expire},model:{value:e.form.expire_time_text,callback:function(t){e.$set(e.form,"expire_time_text",t)},expression:"form.expire_time_text"}}),e._v(" "),o("el-checkbox",{staticClass:"pl16",model:{value:e.form.no_expire,callback:function(t){e.$set(e.form,"no_expire",t)},expression:"form.no_expire"}},[e._v("永不过期")])],1),e._v(" "),o("el-form-item",{attrs:{label:"商家账户名","label-width":e.formLabelWidth,prop:"user_name"}},[o("el-input",{attrs:{autocomplete:"off",placeholder:"请输入商家账户名"},model:{value:e.form.user_name,callback:function(t){e.$set(e.form,"user_name",t)},expression:"form.user_name"}}),e._v(" "),o("p",{staticClass:"gray"},[e._v("注：商家后台用户名")])],1),e._v(" "),o("el-form-item",{attrs:{label:"商家账户密码","label-width":e.formLabelWidth,prop:"password"}},[o("el-input",{attrs:{type:"password",autocomplete:"off",placeholder:"请输入密码"},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}}),e._v(" "),o("p",{staticClass:"gray"},[e._v("注：商家后台用户密码")])],1),e._v(" "),o("el-form-item",{attrs:{label:"确认密码","label-width":e.formLabelWidth,prop:"password_confirm"}},[o("el-input",{attrs:{type:"password",autocomplete:"off",placeholder:"请输入确认密码"},model:{value:e.form.password_confirm,callback:function(t){e.$set(e.form,"password_confirm",t)},expression:"form.password_confirm"}})],1)],1),e._v(" "),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:e.dialogFormVisible}},[e._v("取 消")]),e._v(" "),o("el-button",{attrs:{type:"primary",loading:e.loading},on:{click:function(t){return e.addClick()}}},[e._v("确 定")])],1)],1)},staticRenderFns:[]};var n=o("VU/8")(i,l,!1,function(e){o("wXXb")},null,null).exports,s={data:function(){var e=this;return{form:{no_expire:!1},formLabelWidth:"120px",dialogVisible:!1,loading:!1,rules:{app_name:[{validator:function(t,o,a){return o?e.$filter.isAllSpace(o)?a(new Error("不能全是空格")):void a():a(new Error("请输入商城名称"))},required:!0,trigger:"blur"}],user_name:[{validator:function(t,o,a){return e.$filter.replaceSpace(o)?e.$filter.hasSpace(o)?a(new Error("不能包含空格")):void a():a(new Error("商家账户名"))},required:!0,trigger:"blur"}],password:[{validator:function(t,o,a){if(o){if(e.$filter.hasSpace(o))return a(new Error("不能包含空格"));if(o.length<6)return a(new Error("长度不能小于6位"));a()}else a(new Error("请输入密码"))},required:!0,trigger:"change"}],password_confirm:[{validator:function(t,o,a){o?o!==e.form.password?a(new Error("确认密码不一致")):a():a(new Error("请填写确认密码"))},required:!0,trigger:"blur"}]}}},props:["open_add"],created:function(){this.dialogVisible=this.open_add},methods:{addClick:function(){var e=this,t=this.form;e.$refs.form.validate(function(o){o&&(e.loading=!0,r.addShop(t,!0).then(function(t){e.loading=!1,1==t.code&&(e.$message({message:"恭喜你，添加成功",type:"success"}),e.dialogFormVisible(!0))}).catch(function(t){e.loading=!1}))})},dialogFormVisible:function(e){e?this.$emit("closeDialog",{type:"success",openDialog:!1}):this.$emit("closeDialog",{type:"error",openDialog:!1})}}},c={render:function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("el-dialog",{attrs:{title:"新增小程序商城",visible:e.dialogVisible,"close-on-click-modal":!1,"close-on-press-escape":!1},on:{"update:visible":function(t){e.dialogVisible=t},close:e.dialogFormVisible}},[o("el-form",{ref:"form",attrs:{size:"small",model:e.form,rules:e.rules}},[o("div",{staticStyle:{height:"0",overflow:"hidden"}},[o("input",{attrs:{type:"password"}})]),e._v(" "),o("el-form-item",{attrs:{label:"商城名称","label-width":e.formLabelWidth,prop:"app_name"}},[o("el-input",{attrs:{autocomplete:"off",placeholder:"请输入商城名称"},model:{value:e.form.app_name,callback:function(t){e.$set(e.form,"app_name",t)},expression:"form.app_name"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"过期时间","label-width":e.formLabelWidth,prop:"expire_time"}},[o("el-date-picker",{attrs:{type:"date",placeholder:"过期时间","value-format":"yyyy-MM-dd",disabled:e.form.no_expire},model:{value:e.form.expire_time,callback:function(t){e.$set(e.form,"expire_time",t)},expression:"form.expire_time"}}),e._v(" "),o("el-checkbox",{staticClass:"pl16",model:{value:e.form.no_expire,callback:function(t){e.$set(e.form,"no_expire",t)},expression:"form.no_expire"}},[e._v("永不过期")])],1),e._v(" "),o("el-form-item",{attrs:{label:"商家账户名","label-width":e.formLabelWidth,prop:"user_name"}},[o("el-input",{attrs:{autocomplete:"off",placeholder:"请输入商家账户名"},model:{value:e.form.user_name,callback:function(t){e.$set(e.form,"user_name",t)},expression:"form.user_name"}}),e._v(" "),o("p",{staticClass:"gray"},[e._v("注：商家后台用户名")])],1),e._v(" "),o("el-form-item",{attrs:{label:"商家账户密码","label-width":e.formLabelWidth,prop:"password"}},[o("el-input",{attrs:{type:"password",autocomplete:"off",placeholder:"请输入密码"},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}}),e._v(" "),o("p",{staticClass:"gray"},[e._v("注：商家后台用户密码")])],1),e._v(" "),o("el-form-item",{attrs:{label:"确认密码","label-width":e.formLabelWidth,prop:"password_confirm"}},[o("el-input",{attrs:{type:"password",autocomplete:"off",placeholder:"请输入确认密码"},model:{value:e.form.password_confirm,callback:function(t){e.$set(e.form,"password_confirm",t)},expression:"form.password_confirm"}})],1)],1),e._v(" "),o("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:e.dialogFormVisible}},[e._v("取 消")]),e._v(" "),o("el-button",{attrs:{type:"primary",loading:e.loading},on:{click:function(t){return e.addClick()}}},[e._v("确 定")])],1)],1)},staticRenderFns:[]};var d=o("VU/8")(s,c,!1,function(e){o("82NN")},null,null).exports,p=o("aFVK"),u={components:{Edit:n,Add:d},data:function(){return{loading:!0,tableData:[],pageSize:20,totalDataNumber:0,curPage:1,open_add:!1,open_edit:!1,curModel:{}}},created:function(){this.getData()},methods:{handleCurrentChange:function(e){this.page=e,this.loading=!0,this.getData()},handleSizeChange:function(e){this.curPage=1,this.pageSize=e,this.getData()},getData:function(){var e=this;r.shopList({page:e.curPage,list_rows:e.pageSize},!0).then(function(t){e.loading=!1,e.tableData=t.data.list.data,e.totalDataNumber=t.data.list.total,e.tableData.forEach(function(e){e.is_recycle=0===e.is_recycle})}).catch(function(e){})},addClick:function(){this.open_add=!0},editClick:function(e){this.open_edit=!0,this.curModel=Object(p.a)(e),0==this.curModel.expire_time?(this.curModel.expire_time_text="",this.$set(this.curModel,"no_expire",!0)):this.$set(this.curModel,"no_expire",!1)},closeDialogFunc:function(e,t){"add"==t&&(this.open_add=e.openDialog,"success"==e.type&&this.getData()),"edit"==t&&(this.open_edit=e.openDialog,"success"==e.type&&this.getData())},statusChange:function(e,t){var o=this;o.loading=!0,r.updateStatus({app_id:t.app_id},!0).then(function(a){o.loading=!1,t.is_recycle=e}).catch(function(a){o.loading=!1,t.is_recycle=!e})},deleteClick:function(e){var t=this;t.$confirm("删除后不可恢复，确认删除该记录吗?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then(function(){t.loading=!0,r.deleteShop({app_id:e.app_id},!0).then(function(e){t.loading=!1,1==e.code&&(t.$message({message:e.msg,type:"success"}),t.getData())}).catch(function(e){t.loading=!1})}).catch(function(){})},storeEnter:function(e){r.storeEnter({app_id:e},!0).then(function(e){}).catch(function(e){})}}},f={render:function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}]},[o("div",{staticClass:"common-level-rail"},[o("el-button",{attrs:{size:"small",type:"primary"},on:{click:e.addClick}},[e._v("添加商城")])],1),e._v(" "),o("div",{staticClass:"product-content"},[o("div",{staticClass:"table-wrap"},[o("div",[o("el-table",{directives:[{name:"loading",rawName:"v-loading",value:e.loading,expression:"loading"}],staticStyle:{width:"100%"},attrs:{size:"small",data:e.tableData,"row-key":"access_id",border:"","default-expand-all":""}},[o("el-table-column",{attrs:{prop:"app_id",label:"商城ID"}}),e._v(" "),o("el-table-column",{attrs:{prop:"app_name",label:"商城名称"}}),e._v(" "),o("el-table-column",{attrs:{prop:"user_name",label:"超管账号"}}),e._v(" "),o("el-table-column",{attrs:{prop:"is_recycle",label:"状态"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-checkbox",{on:{change:function(o){return e.statusChange(o,t.row)}},model:{value:t.row.is_recycle,callback:function(o){e.$set(t.row,"is_recycle",o)},expression:"scope.row.is_recycle"}},[e._v("启用")])]}}])}),e._v(" "),o("el-table-column",{attrs:{prop:"expire_time_text",label:"过期时间"}}),e._v(" "),o("el-table-column",{attrs:{prop:"create_time",label:"添加时间"}}),e._v(" "),o("el-table-column",{attrs:{label:"操作",width:"150"},scopedSlots:e._u([{key:"default",fn:function(t){return[o("el-link",{attrs:{href:"/index.php/admin/shop/enter/app_id/"+t.row.app_id,target:"_blank",type:"text",size:"small"}},[e._v("进入商城")]),e._v(" "),o("el-link",{staticClass:"ml10",attrs:{type:"text",size:"small"},on:{click:function(o){return e.editClick(t.row)}}},[e._v("编辑")]),e._v(" "),o("el-link",{staticClass:"ml10",attrs:{type:"text",size:"small"},on:{click:function(o){return e.deleteClick(t.row)}}},[e._v("删除")])]}}])})],1)],1)])]),e._v(" "),e.open_add?o("Add",{attrs:{open_add:e.open_add},on:{closeDialog:function(t){return e.closeDialogFunc(t,"add")}}}):e._e(),e._v(" "),e.open_edit?o("Edit",{attrs:{open_edit:e.open_edit,curModel:e.curModel},on:{closeDialog:function(t){return e.closeDialogFunc(t,"edit")}}}):e._e(),e._v(" "),o("div",{staticClass:"pagination"},[o("el-pagination",{attrs:{background:"","current-page":e.curPage,"page-size":e.pageSize,layout:"total, prev, pager, next, jumper",total:e.totalDataNumber},on:{"size-change":e.handleSizeChange,"current-change":e.handleCurrentChange}})],1)],1)},staticRenderFns:[]};var m=o("VU/8")(u,f,!1,function(e){o("18M7")},null,null);t.default=m.exports},"82NN":function(e,t){},wXXb:function(e,t){}});
//# sourceMappingURL=8.838942b5569d92e30784.js.map