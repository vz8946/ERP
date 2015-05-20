<?php /* Smarty version 2.6.19, created on 2014-10-23 13:47:55
         compiled from brand/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'brand/edit.tpl', 67, false),array('modifier', 'default', 'brand/edit.tpl', 235, false),)), $this); ?>
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
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" enctype="multipart/form-data" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑品牌<?php else: ?>添加品牌<?php endif; ?></div>



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
			  <td><input type="text" name="brand_name" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['brand_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写品牌名称" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌别名</strong> * </td>
			  <td><input type="text" name="as_name" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['as_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写品牌别名" class="required" /> 取值范围(a~z) 注：值为小写字母且无空格</td>
			</tr>
			
			<tr>
			  <td width="10%"><strong>产地</strong> * </td>
			  <td><input type="text" name="region" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['region'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写产地" class="required" /> </td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌首字母</strong> * </td>
			  <td><input type="text" name="char" size="30" value="<?php echo $this->_tpl_vars['data']['char']; ?>
" msg="请填写品牌首字母" class="required" /> 取值范围(A~Z)</td>
			</tr>
			
			<tr>
			  <td><strong>是否启用</strong> * </td>
			  <td>
			   <input type="radio" name="status" value="0" <?php if ($this->_tpl_vars['data']['status'] == 0 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
			   <input type="radio" name="status" value="1" <?php if ($this->_tpl_vars['data']['status'] == 1 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
			  </td>
			</tr>
			
			<tr>
			  <td width="10%"><strong>品牌大图片</strong>  </td>
			  <td>  <input type="file"  name="big_logo"  /> <?php if ($this->_tpl_vars['data']['big_logo']): ?> <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['big_logo']; ?>
"   width="100px"/><?php endif; ?>  </td>
			</tr>
			<tr>
			  <td width="10%"><strong>品牌小图片</strong>  </td>
			  <td>  <input type="file"  name="small_logo"  /> <?php if ($this->_tpl_vars['data']['small_logo']): ?> <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['small_logo']; ?>
"   width="100px"/><?php endif; ?>  </td>
			</tr>
			
			
			
			
		</tbody>
		</table>	
	 </div>
	<div id="show_tab_page_1" style="display:none;">  
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
		<tbody>
			<tr>
			  <td width="10%"><strong>meta标题</strong> * </td>
			  <td><input type="text" name="title" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['title'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写品牌meta标题" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>meta关键词</strong> * </td>
			  <td><input type="text" name="keywords" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['keywords'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写品牌meta关键词" class="required" /></td>
			</tr>
			<tr>
			  <td width="10%"><strong>meta描述</strong> * </td>
			  <td><textarea name="description" rows="5" cols="60" msg="请填写品牌meta描述" class="required" ><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['description'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea></td>
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
			   <input type="radio" name="ispinpaicheng" value="1" <?php if ($this->_tpl_vars['data']['ispinpaicheng'] == 1 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
			   <input type="radio" name="ispinpaicheng" value="0" <?php if ($this->_tpl_vars['data']['ispinpaicheng'] == 0 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
			  </td>
			</tr>
			<tr>
			  <td><strong>是否品牌馆</strong> * </td>
			  <td>
			   <input type="radio" name="bluk" value="1" <?php if ($this->_tpl_vars['data']['bluk'] == 1 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
			   <input type="radio" name="bluk" value="0" <?php if ($this->_tpl_vars['data']['bluk'] == 0 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
			  </td>
			</tr>
			<tr>
			  <td><strong>品牌馆样式</strong> * </td>
			  <td>
				<select name="brand_style">
					<option value="green" <?php if ($this->_tpl_vars['data']['brand_style'] == 'green'): ?>selected<?php endif; ?>>绿色</option>
					<option value="blue" <?php if ($this->_tpl_vars['data']['brand_style'] == 'blue'): ?>selected<?php endif; ?>>蓝色</option>
					<option value="orange" <?php if ($this->_tpl_vars['data']['brand_style'] == 'orange'): ?>selected<?php endif; ?>>橘色</option>
					<option value="red" <?php if ($this->_tpl_vars['data']['brand_style'] == 'red'): ?>selected<?php endif; ?>>红色</option>
				</select>
			  </td>
			</tr>
			<tr class="leibie">
				<td class="label">品牌导航类别：</td>
				<td valign="top">
				<div style="position:relative;z-index:7;">
					<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="topcatenames" value="<?php echo $this->_tpl_vars['data']['topcatenames']; ?>
"  id="topcatenames" readonly="true" onClick="showDiv(2);">最多7个
					<input type="hidden" name="topcateids" id="topcateids"  value="<?php echo $this->_tpl_vars['data']['topcateids']; ?>
" >
					<div align="center" id="cate2" class="cate2">
													<div id="cate2cont" class="content">
														<?php echo $this->_tpl_vars['checktree']; ?>

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
												<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="centercatenames" value="<?php echo $this->_tpl_vars['data']['centercatenames']; ?>
"  id="centercatenames" readonly="true" onClick="showDiv(3);">最多6个
												<input type="hidden" name="centercateids" id="centercateids"  value="<?php echo $this->_tpl_vars['data']['centercateids']; ?>
" >
												<div align="center" id="cate3" class="cate3">
													<div id="cate3cont" class="content">
														<?php echo $this->_tpl_vars['checktree']; ?>

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
												<input style="border: 1px #8A9295 solid;width:217px;" type="text" name="bottomcatenames" value="<?php echo $this->_tpl_vars['data']['bottomcatenames']; ?>
"  id="bottomcatenames" readonly="true" onClick="showDiv(4);">最多6个
												<input type="hidden" name="bottomcateids" id="bottomcateids"  value="<?php echo $this->_tpl_vars['data']['bottomcateids']; ?>
" >
												<div align="center" id="cate4" class="cate4">
													<div id="cate4cont" class="content">
														<?php echo $this->_tpl_vars['checktree']; ?>

													</div>
													<div style="text-align:right;"><a href="javascript:divout(4,'bottomcate');">确定</a></div>
												</div>
											</div>
										</td>
									</tr>
			<tr>
			  <td width="10%"><strong>品牌介绍</strong></td>
			  <td>
			  
				<textarea name="brand_desc" id="brand_desc" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['data']['brand_desc']; ?>
</textarea>
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
					var ids = "<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['topcateids'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
";
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
					var ids = "<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['centercateids'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
";
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
					var ids = "<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['bottomcateids'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
";
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