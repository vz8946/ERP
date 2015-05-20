<?php /* Smarty version 2.6.19, created on 2014-10-23 14:09:38
         compiled from decoration/list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'decoration/list.tpl', 45, false),)), $this); ?>

	<div class="title">装修管理</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>产品编码</td>
				<td>产品ID</td>
				<td>产品名称</td>
				<td>图片路径/上传图片</td>
				<?php if (! empty ( $this->_tpl_vars['catelist'] )): ?>
				<td>产品类别</td>
				<?php endif; ?>
				<td>显示</td>
				<td>排序(越大优先级越高)</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		<form name="alldata" method="post" action="/admin/decoration/save" enctype="multipart/form-data" >
		<input type="hidden" name="type" value="<?php echo $this->_tpl_vars['type']; ?>
" />
		<input type="hidden" name="pidcode" value="<?php echo $this->_tpl_vars['pid']; ?>
" />
		<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
		<tr id="ajax_list<?php echo $this->_tpl_vars['item']['id']; ?>
">
				<td><?php echo $this->_tpl_vars['item']['id']; ?>
</td>
				<td><input name="goodsnum[<?php echo $this->_tpl_vars['item']['id']; ?>
]" value="<?php echo $this->_tpl_vars['item']['goodsnum']; ?>
" /></td>
				<td><?php echo $this->_tpl_vars['item']['goodsid']; ?>
</td>
				<td><?php echo $this->_tpl_vars['item']['goods_name']; ?>
</td>
				<td>
					<input name="imgurl[<?php echo $this->_tpl_vars['item']['id']; ?>
]" value="<?php echo $this->_tpl_vars['item']['imgurl']; ?>
" />
					<input name="imgupload[<?php echo $this->_tpl_vars['item']['id']; ?>
]" type="file" size="10"/>
				</td>
				<?php if (! empty ( $this->_tpl_vars['catelist'] )): ?>
				<td>
					<select name="pid[<?php echo $this->_tpl_vars['item']['id']; ?>
]">
						<?php $_from = $this->_tpl_vars['catelist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['obj']):
?>
							<option value="<?php echo $this->_tpl_vars['obj']['code']; ?>
" <?php if ($this->_tpl_vars['obj']['code'] == $this->_tpl_vars['item']['pid']): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['obj']['name']; ?>
</option>
						<?php endforeach; endif; unset($_from); ?>
					</select>
				</td>
				<?php endif; ?>
				<td>
					<?php $this->assign('item_id', $this->_tpl_vars['item']['id']); ?>
					<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_yn'],'value' => $this->_tpl_vars['item']['isdisplay'],'onchange' => "ajax_update('/admin/decoration/ajaxupdatedisplay',".($this->_tpl_vars['item_id']).",'band_sort',this.value)"), $this);?>

				<td>
					<input name="sort[<?php echo $this->_tpl_vars['item']['id']; ?>
]" value="<?php echo $this->_tpl_vars['item']['sort']; ?>
" size="5"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdateord',)));?>',<?php echo $this->_tpl_vars['item']['id']; ?>
,'band_sort',this.value)"/>
				</td>
				<td><a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'del','pkid'=>'id',)));?>','<?php echo $this->_tpl_vars['item']['id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'list',)));?>')">删除</a></td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		 <tr >
      	<td colspan="4">
      	</td>
      	<td colspan="4">
		<input type='submit' value="保存修改" class="button"  >
        <input type='button' value="添加一条信息" class="button" onClick="addNewOne()" ></td>
    </tr>
    	</form>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script type="text/javascript">
	function addNewOne(){
		window.location.href="/admin/decoration/add/type/<?php echo $this->_tpl_vars['type']; ?>
/pidcode/<?php echo $this->_tpl_vars['pid']; ?>
";
	}
</script>