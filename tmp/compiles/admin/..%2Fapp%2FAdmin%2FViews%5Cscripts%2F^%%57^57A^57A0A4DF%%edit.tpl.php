<?php /* Smarty version 2.6.19, created on 2014-11-01 13:00:39
         compiled from goods/edit.tpl */ ?>
<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>

<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data" />
<input type="hidden" name="goods_sn" value='<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
'>
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(0)" id="show_tab_nav_0" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_attr">商品扩展</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">商品描述</li>
	</ul>
</div>
<div class="content">
<div id="show_tab_page_0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>商品名称</strong> * </td>
      <td><input type="text" name="goods_name" size="30" value="<?php echo $this->_tpl_vars['data']['goods_name']; ?>
" msg="请填写商品名称" class="required"/></td>
    </tr>
    <tr>
      <td><strong>限购数量</strong> * </td>
      <td><input type="text" name="limit_number" size="8" value="<?php echo $this->_tpl_vars['data']['limit_number']; ?>
" msg="请填写限购数量" class="required number" /></td>
    </tr>

    <tr>
      <td width="10%"><strong>产地</strong> * </td>
      <td><input type="text" name="region" size="20" maxlength="6" value="<?php echo $this->_tpl_vars['data']['region']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>展示分类</strong> * </td>
      <td><?php echo $this->_tpl_vars['data']['view_cat_name']; ?>
</td>
    </tr>	
    <tr>
      <td width="10%"><strong>展示分类</strong> * </td>
      <td><?php echo $this->_tpl_vars['catViewSelect']; ?>
    <input type="checkbox" name="editviewcat" value="1"> 是否确认要更改商品展示分类</td>
    </tr>
    <tr>
      <td width="10%"><strong>适种积温带</strong> * </td>
      <td>
	<select name="characters">
	    <option value="">--请选择--</option>
	    <option value="1" <?php if ($this->_tpl_vars['data']['characters'] == '1'): ?>selected="selected"<?php endif; ?>>第一积温带</option>	
	    <option value="2" <?php if ($this->_tpl_vars['data']['characters'] == '2'): ?>selected="selected"<?php endif; ?>>第二积温带</option>	
	    <option value="3" <?php if ($this->_tpl_vars['data']['characters'] == '3'): ?>selected="selected"<?php endif; ?>>第三积温带</option>	
	    <option value="4" <?php if ($this->_tpl_vars['data']['characters'] == '4'): ?>selected="selected"<?php endif; ?>>第四积温带</option>	
	    <option value="5" <?php if ($this->_tpl_vars['data']['characters'] == '5'): ?>selected="selected"<?php endif; ?>>第五积温带</option>	
	    <option value="6" <?php if ($this->_tpl_vars['data']['characters'] == '6'): ?>selected="selected"<?php endif; ?>>第六积温带</option>	
	</select>
      </td>
    </tr>
	 <tr>
      <td width="10%"><strong>是否是赠品</strong> * </td>
      <td>
        <input type="radio" name="is_gift" value="1" <?php if ($this->_tpl_vars['data']['is_gift']): ?>checked<?php endif; ?>>是
        <input type="radio" name="is_gift" value="0" <?php if (! $this->_tpl_vars['data']['is_gift']): ?>checked<?php endif; ?>>否
      </td>
    </tr>	
    <tr>
      <td><strong>扩展分类</strong></td>
      <td><input type="button" onclick="addCats()" value="添加" /><span id="cats"><?php echo $this->_tpl_vars['selectedOtherCat']; ?>
</span><span id="copy" style=" display:none;"><?php echo $this->_tpl_vars['viewcatSelect']; ?>
</span></td>
    </tr>	
	<script type="text/javascript">
	function addCats(){
		var sel = new Element('SELECT',{name:'other_cat_id[]'});
		var selCat = $('other_cat_id');
		for(i = 0; i < selCat.length; i++){
			var opt = new Element("OPTION",{
				text:selCat.options[i].text,
				value:selCat.options[i].value
			});
			sel.appendChild(opt);
		}
		document.getElementById('cats').appendChild(sel);
	}
	</script>

    <tr>
      <td><strong>活动/简要说明：</strong> * </td>
      <td>  
		<textarea name="act_notes" id="act_notes" rows="20" style="width:680px; height:160px;"><?php echo $this->_tpl_vars['data']['act_notes']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="act_notes"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>	
    <tr>
      <td><strong>商品ALT说明：</strong> * </td>
      <td>  
	  <input type="text" name="goods_alt" id="goods_alt" size="60" value="<?php echo $this->_tpl_vars['data']['goods_alt']; ?>
"  />
	  </td>
    </tr>
    <tr>
      <td><strong>url别名：</strong> * </td>
      <td>  
	  <input type="text" name="url_alias" id="url_alias" size="10" value="<?php echo $this->_tpl_vars['data']['url_alias']; ?>
"  />
	  </td>
    </tr>
		
</tbody>
</table>
</div>

<div id="show_tab_page_1" style="display:none;">

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
   <tr>
      <td><strong>meta标题</strong></td>
      <td><input type="text" name="meta_title" size="50" value="<?php echo $this->_tpl_vars['data']['meta_title']; ?>
"></td>
    </tr>
    <tr>
      <td><strong>meta关键字</strong></td>
      <td><input type="text" name="meta_keywords" size="50" value="<?php echo $this->_tpl_vars['data']['meta_keywords']; ?>
"></td>
    </tr>
	<tr>
      <td><strong>meta描述</strong></td>
      <td><textarea name="meta_description" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;"><?php echo $this->_tpl_vars['data']['meta_description']; ?>
</textarea></td>
    </tr>
		
</tbody>
</table>
</div>


<div id="show_tab_page_2" style="display:none;">
<input type="hidden" name="goods_sn" value="<?php echo $this->_tpl_vars['data']['goods_sn']; ?>
">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>

	<tr>
      <td><strong>规格说明</strong></td>
      <td>
	  <textarea name="spec" rows="4" cols="39" id="spec" style="width:350px; height:45px;"><?php echo $this->_tpl_vars['data']['spec']; ?>
</textarea>
	  </td>
    </tr>
    <tr>
      <td width="10%"><strong>商品特点</strong></td>
      <td> 
	   <textarea name="brief" rows="4" cols="39" id="brief" style="width:350px; height:45px;"><?php echo $this->_tpl_vars['data']['brief']; ?>
</textarea>
	   </td>
    </tr>
	<tr>
      <td><strong>商品功效说明</strong></td>
      <td>
		<textarea name="description" id="description" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['data']['description']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="description"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	 </td>
    </tr>

	<tr>
      <td><strong>商品说明书</strong></td>
      <td>
		<textarea name="introduction" id="introduction" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['data']['introduction']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="introduction"]', {
				            filterMode : false,
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
<input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
