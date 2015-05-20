<?php /* Smarty version 2.6.19, created on 2014-10-24 19:09:06
         compiled from news/listdata.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">
		标题：<input type="text" name="name" size="40" maxLength="50" value="<?php echo $this->_tpl_vars['param']['name']; ?>
">

        类型：<select name="type">
          <option value="">请选择</option>
			  <option value="1" <?php if ($this->_tpl_vars['param']['type'] == '1'): ?>selected<?php endif; ?>>文章资讯</option>
			  <option value="2" <?php if ($this->_tpl_vars['param']['type'] == '2'): ?>selected<?php endif; ?>>广告位</option>
			  <option value="3" <?php if ($this->_tpl_vars['param']['type'] == '3'): ?>selected<?php endif; ?>>友情链接</option>
			  <option value="4" <?php if ($this->_tpl_vars['param']['type'] == '4'): ?>selected<?php endif; ?>>爆款类别</option>
			  <option value="5" <?php if ($this->_tpl_vars['param']['type'] == '5'): ?>selected<?php endif; ?>>促销中心类别</option>
			  <option value="6" <?php if ($this->_tpl_vars['param']['type'] == '6'): ?>selected<?php endif; ?>>巨便宜类别</option>
			  <option value="7" <?php if ($this->_tpl_vars['param']['type'] == '7'): ?>selected<?php endif; ?>>首页排行装修</option>
        </select>
		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
		</div>
	</form>
</div>
<div id="ajax_search">
<?php endif; ?>
	<div class="title">数据字典管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'adddataform',)));?>')">添加数据字典</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>名称</td>
				<td>数据码</td>
				<td>类型</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr id="ajax_list<?php echo $this->_tpl_vars['item']['id']; ?>
">
			<td><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
			<td><input value='<?php echo $this->_tpl_vars['item']['name']; ?>
' type='text' style='width:230px' onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatedata',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'name',this.value)"></td>
			<td><input value='<?php echo $this->_tpl_vars['item']['code']; ?>
' type='text' style='width:120px' onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatedata',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'code',this.value)"></td>
			<td>
				<select name="type"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdatedata',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'type',this.value)">
		          <option value="1" <?php if ($this->_tpl_vars['item']['type'] == '1'): ?>selected<?php endif; ?>>文章资讯</option>
		          <option value="2" <?php if ($this->_tpl_vars['item']['type'] == '2'): ?>selected<?php endif; ?>>广告位</option>
		          <option value="3" <?php if ($this->_tpl_vars['item']['type'] == '3'): ?>selected<?php endif; ?>>友情链接</option>
		          <option value="4" <?php if ($this->_tpl_vars['item']['type'] == '4'): ?>selected<?php endif; ?>>爆款类别</option>
	          	  <option value="5" <?php if ($this->_tpl_vars['item']['type'] == '5'): ?>selected<?php endif; ?>>促销中心类别</option>
	          	  <option value="6" <?php if ($this->_tpl_vars['item']['type'] == '6'): ?>selected<?php endif; ?>>巨便宜类别</option>
	          	  <option value="7" <?php if ($this->_tpl_vars['item']['type'] == '7'): ?>selected<?php endif; ?>>首页排行装修</option>
		        </select>
			</td>
			<td>
			<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'deldata',)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'listdata',)));?>')">删除</a>
		</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/news/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>