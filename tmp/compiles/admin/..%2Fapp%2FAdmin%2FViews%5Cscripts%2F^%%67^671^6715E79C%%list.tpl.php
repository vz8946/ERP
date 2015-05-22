<?php /* Smarty version 2.6.19, created on 2014-10-23 14:09:31
         compiled from topics/list.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">

		标题：<input type="text" name="title" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['title']; ?>
">

        是否显示：<select name="is_view">
          <option value="null">请选择</option>
          <option value="1" <?php if ($this->_tpl_vars['param']['is_view'] == '1'): ?>selected<?php endif; ?>>显示</option>
          <option value="0" <?php if ($this->_tpl_vars['param']['is_view'] == '0'): ?>selected<?php endif; ?>>不显示</option>
        </select>


		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
		</div>
	</form>
</div>
<div id="ajax_search">
<?php endif; ?>
	<div class="title">专题管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加专题</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>排序</td>
				<td>标题</td>
				<td>是否显示</td>
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
			<td><input type="text" name="update" size="2" value="<?php echo $this->_tpl_vars['item']['sort']; ?>
" style="text-align:center;" 
			onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'sort',this.value)"></td>
			<td><?php echo $this->_tpl_vars['item']['name']; ?>
</td>
			<td  >
            <a href="javascript:void(0);"  id="label_isdis<?php echo $this->_tpl_vars['item']['id']; ?>
" onclick="ajax_status('/admin/topics/toggle-isdis','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this->_tpl_vars['item']['isDisplay']; ?>
','label_isdis');">
	        <?php if ($this->_tpl_vars['item']['isDisplay'] == 1): ?>显示<?php elseif ($this->_tpl_vars['item']['isDisplay'] == 0): ?>不显示 <?php endif; ?></a>
			</td>
			<td>
			<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['item']['id'],'pkid'=>'id',)));?>')">编辑</a>||
			<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'del','pkid'=>'id',)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'list',)));?>')">删除</a>
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
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
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