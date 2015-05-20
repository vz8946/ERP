<?php /* Smarty version 2.6.19, created on 2014-10-23 13:42:09
         compiled from category/edit.tpl */ ?>
<script>
function ajax_get_cat(url,id){
    url = filterUrl(url, 'pid');
    new Request({
        url: url + '/pid/' + id,
        onRequest: loading,
        onSuccess:function(data){
	    $('parent_id').value=id;
	    $('cat_path').value += id + ',';
	    if(id==0){
	    	$('ajax_sel_cat').innerHTML='';
	    	$('cat_path').value=',';
	    	$('parent_name').style.display='none';
	    }
        var span = document.createElement("span");
        span.innerHTML=data;
        $('ajax_sel_cat').appendChild(span);
        loadSucess();
        }
    }).send();
}

</script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑分类<?php else: ?>添加分类<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="10%"><strong>上级分类</strong> * </td>
      <td>
	  <input name="angle_id" type="hidden" value="<?php echo $this->_tpl_vars['angle_id']; ?>
" />
      <input name="old_parent_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
      <input name="old_cat_path" type="hidden" value="<?php echo $this->_tpl_vars['data']['cat_path']; ?>
">
      <input name="parent_id" id="parent_id" type="hidden" value="<?php echo $this->_tpl_vars['data']['parent_id']; ?>
">
      <input name="cat_path" id="cat_path" type="hidden" value="<?php echo $this->_tpl_vars['data']['cat_path']; ?>
">
      <span id="parent_name" style="color:red"><?php echo $this->_tpl_vars['data']['parent_name']; ?>
</span>
	  <?php if ($this->_tpl_vars['action'] == 'edit' && $this->_tpl_vars['angle_id'] == 1): ?> 
	  <?php else: ?>
	   <span id="ajax_sel_cat"></span> <a href="javascript:fGo()" onclick="ajax_get_cat('<?php echo $this -> callViewHelper('url', array(array('action'=>'getproductcat',)));?>',0);">重选</a>
	  <?php endif; ?>
 
	<?php if ($this->_tpl_vars['data']['parent_id'] == 0): ?>
	<script>ajax_get_cat('<?php echo $this -> callViewHelper('url', array(array('action'=>'getcat',)));?>',0)</script>
	<?php endif; ?>
</td>
    </tr>
    <tr> 
      <td><strong>分类名称</strong> * </td>
      <td><input type="text" name="cat_name" size="30" value="<?php echo $this->_tpl_vars['data']['cat_name']; ?>
" msg="请填写分类名称" class="required"></td>
    </tr>	   
   
    <tr> 
      <td><strong>url别名</strong></td>
      <td><input type="text" name="url_alias" id="url_alias" size="10" value="<?php echo $this->_tpl_vars['data']['url_alias']; ?>
" /></td>
    </tr>
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
    <?php if ($this->_tpl_vars['data']['cat_id'] > 0 && $this->_tpl_vars['data']['parent_id'] == 0): ?>
	<tr> 
      <td><strong>关联品牌</strong></td>
      <td><input type="text" name="brand_link_ids" id="brand_link_ids" size="50" value="<?php echo $this->_tpl_vars['data']['brand_link_ids']; ?>
" /></td>
    </tr>
    <?php endif; ?>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>