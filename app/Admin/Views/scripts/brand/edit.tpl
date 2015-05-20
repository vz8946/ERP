<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<link rel="stylesheet" type="text/css" href="/styles/admin/checktree.css" />
	 <style type="text/css" rel="stylesheet">
    form {
        margin: 0;
    }
    .editor {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    ul,li{
		list-style:none outside none;
		margin:0xp;
		padding:0px;
	}
	.tree li{
		text-align: left;
		display: block;
		width:100%;
		line-height:20px;
	}
	.cate1 .tree .checkbox{
		display: none;
	}
	.cate1,.cate2,.cate3,.cate4{
    	background: #FFFFFF;
    	border-left: 1px #8A9295 solid;
    	border-right: 1px #8A9295 solid;
    	border-bottom: 1px #8A9295 solid;
    	display:none;
    	position: absolute;
    	top: 21px;
    	left: 0px;
    	z-index:999;
    }
   .cate1 .content,.cate2 .content,.cate3 .content,.cate4 .content{
   		width:216px;
    	height: 150px;
    	overflow-x:auto;
    	overflow-y:auto;
   }
  </style>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" enctype="multipart/form-data" method="post">
<div class="title">{{if $action eq 'edit'}}编辑品牌{{else}}添加品牌{{/if}}</div>



<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(0)" id="show_tab_nav_0" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_attr">品牌扩展</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">品牌描述</li>
	</ul>
</div>



<div class="content">

	<div id="show_tab_page_0"> 
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
		<tbody>
		
			<tr>
			  <td width="10%"><strong>品牌名称</strong> * </td>
			  <td><input type="text" name="brand_name" size="30" value="{{$data.brand_name|stripslashes}}" msg="请填写品牌名称" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌别名</strong> * </td>
			  <td><input type="text" name="as_name" size="30" value="{{$data.as_name|stripslashes}}" msg="请填写品牌别名" class="required" /> 取值范围(a~z) 注：值为小写字母且无空格</td>
			</tr>
			
			<tr>
			  <td width="10%"><strong>产地</strong> * </td>
			  <td><input type="text" name="region" size="30" value="{{$data.region|stripslashes}}" msg="请填写产地" class="required" /> </td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌首字母</strong> * </td>
			  <td><input type="text" name="char" size="30" value="{{$data.char}}" msg="请填写品牌首字母" class="required" /> 取值范围(A~Z)</td>
			</tr>
			
			<tr>
			  <td><strong>是否启用</strong> * </td>
			  <td>
			   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
			   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
			  </td>
			</tr>
			
			<tr>
			  <td width="10%"><strong>品牌大图片</strong>  </td>
			  <td>  <input type="file"  name="big_logo"  /> {{if $data.big_logo}} <img  src="{{$imgBaseUrl}}/{{$data.big_logo}}"   width="100px"/>{{/if}}  </td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌小图片</strong>  </td>
			  <td>  <input type="file"  name="small_logo"  /> {{if $data.small_logo}} <img  src="{{$imgBaseUrl}}/{{$data.small_logo}}"   width="100px"/>{{/if}}  </td>
			</tr>
			
			
			
			
		</tbody>
		</table>	
	 </div>
	<div id="show_tab_page_1" style="display:none;">  
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
		<tbody>
			<tr>
			  <td width="10%"><strong>meta标题</strong> * </td>
			  <td><input type="text" name="title" size="50" value="{{$data.title|stripslashes}}" msg="请填写品牌meta标题" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>meta关键词</strong> * </td>
			  <td><input type="text" name="keywords" size="50" value="{{$data.keywords|stripslashes}}" msg="请填写品牌meta关键词" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>meta描述</strong> * </td>
			  <td><textarea name="description" rows="5" cols="60" msg="请填写品牌meta描述" class="required" >{{$data.description|stripslashes}}</textarea></td>
			</tr>
		</tbody>
		</table>	
	
	</div>
	<div id="show_tab_page_2" style="display:none;">
	
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
		<tbody>
		
		   <tr>
			  <td><strong>是否品牌城</strong> * </td>
			  <td>
			   <input type="radio" name="ispinpaicheng" value="1" {{if $data.ispinpaicheng==1 && $action eq 'edit'}}checked{{/if}}/> 是
			   <input type="radio" name="ispinpaicheng" value="0" {{if $data.ispinpaicheng==0 or $action eq 'add'}}checked{{/if}}/> 否
			  </td>
			</tr>
			<tr>
			  <td><strong>是否品牌馆</strong> * </td>
			  <td>
			   <input type="radio" name="bluk" value="1" {{if $data.bluk==1 && $action eq 'edit'}}checked{{/if}}/> 是
			   <input type="radio" name="bluk" value="0" {{if $data.bluk==0 or $action eq 'add'}}checked{{/if}}/> 否
			  </td>
			</tr>
			<tr>
			  <td><strong>品牌馆样式</strong> * </td>
			  <td>
				<select name="brand_style">
					<option value="green" {{if $data.brand_style=='green'}}selected{{/if}}>绿色</option>
					<option value="blue" {{if $data.brand_style=='blue'}}selected{{/if}}>蓝色</option>
					<option value="orange" {{if $data.brand_style=='orange'}}selected{{/if}}>橘色</option>
					<option value="red" {{if $data.brand_style=='red'}}selected{{/if}}>红色</option>
				</select>
			  </td>
			</tr>
			<tr class="leibie">
				<td class="label">品牌导航类别：</td>
				<td valign="top">
				<div style="position:relative;z-index:7;">
					<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="topcatenames" value="{{$data.topcatenames}}"  id="topcatenames" readonly="true" onClick="showDiv(2);">最多7个
					<input type="hidden" name="topcateids" id="topcateids"  value="{{$data.topcateids}}" >
					<div align="center" id="cate2" class="cate2">
													<div id="cate2cont" class="content">
														{{$checktree}}
													</div>
													<div style="text-align:right;"><a href="javascript:divout(2,'topcate');">确定</a></div>
												</div>
											</div>
										</td>
									</tr>
									<tr class="leibie">
										<td class="label">品牌推荐产品1类别：</td>
										<td valign="top">
											<div style="position:relative;z-index:6;">
												<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="centercatenames" value="{{$data.centercatenames}}"  id="centercatenames" readonly="true" onClick="showDiv(3);">最多6个
												<input type="hidden" name="centercateids" id="centercateids"  value="{{$data.centercateids}}" >
												<div align="center" id="cate3" class="cate3">
													<div id="cate3cont" class="content">
														{{$checktree}}
													</div>
													<div style="text-align:right;"><a href="javascript:divout(3,'centercate');">确定</a></div>
												</div>
											</div>
										</td>
									</tr>
									<tr class="leibie">
										<td class="label">品牌推荐产品2类别：</td>
										<td valign="top">
											<div style="position:relative;z-index:5;">
												<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="bottomcatenames" value="{{$data.bottomcatenames}}"  id="bottomcatenames" readonly="true" onClick="showDiv(4);">最多6个
												<input type="hidden" name="bottomcateids" id="bottomcateids"  value="{{$data.bottomcateids}}" >
												<div align="center" id="cate4" class="cate4">
													<div id="cate4cont" class="content">
														{{$checktree}}
													</div>
													<div style="text-align:right;"><a href="javascript:divout(4,'bottomcate');">确定</a></div>
												</div>
											</div>
										</td>
									</tr>
			<tr>
			  <td width="10%"><strong>品牌介绍</strong></td>
			  <td>
			  
				<textarea name="brand_desc" id="brand_desc" rows="20" style="width:680px; height:260px;">{{$data.brand_desc}}</textarea>
				<script type="text/javascript">
					KindEditor.ready(function(K) {
						K.create('textarea[name="brand_desc"]', {
									allowFileManager : true
								});
					});
				</script>
			  
			  </td>
			</tr>
				
		
		</tbody>
		</table>	
	
	</div>



</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script type="text/jscript" src="/scripts/admin/jquery-1.4.2.min.js"/></script>
<script type="text/jscript" src="/scripts/admin/jquery.checktree.js"/></script>
	<script type="text/javascript">
		$.noConflict();
		jQuery(document).ready(function($) {
			$("#topcatenames").focus(function(){
				var cate = $("#cate2");
				cate.find(":checkbox").filter(function(index){
					var ids = "{{$data.topcateids|default:0}}";
					var arr = ids.split(",");
					for(var i =0; i<arr.length; i++){
						if(arr[i]==this.value){
							return true;
						}
					}
					return false;
				}).attr("checked", "checked");
				$("#cate2 ul.tree").checkTree({});
			});
		    $("#centercatenames").focus(function(){
				var cate = $("#cate3");
				cate.find(":checkbox").filter(function(index){
					var ids = "{{$data.centercateids|default:0}}";
					var arr = ids.split(",");
					for(var i =0; i<arr.length; i++){
						if(arr[i]==this.value){
							return true;
						}
					}
					return false;
				}).attr("checked", "checked");
				$("#cate3 ul.tree").checkTree({});
			});
			$("#bottomcatenames").focus(function(){
				var cate = $("#cate4");
				cate.find(":checkbox").filter(function(index){
					var ids = "{{$data.bottomcateids|default:0}}";
					var arr = ids.split(",");
					for(var i =0; i<arr.length; i++){
						if(arr[i]==this.value){
							return true;
						}
					}
					return false;
				}).attr("checked", "checked");
				$("#cate4 ul.tree").checkTree({});
			});
		});

		//显示层
		function showDiv(i){
			var cate = jQuery("#cate"+i);
			cate.slideDown("slow");
		}
		function divoutmy(i){
			var cate = jQuery("#cate"+i);
			cate.slideUp("slow");
		}
		//关闭显示
		function divout(i,pos)
		{
			var cate = jQuery("#cate"+i);
			var arrCate = cate.find(":checkbox").filter(":checked");
			var length = arrCate.size();
			var cateid = "";
			var cateName = "";
			for(var i = 0;i<length;i++){
				cateid += ","+arrCate.eq(i).val();
				cateName += ","+arrCate.eq(i).attr("name");
			}
			jQuery("#"+pos+"ids").val(cateid);
			jQuery("#"+pos+"names").val(cateName.substr(1));
			cate.slideUp("slow");

		}

	</script>