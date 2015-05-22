<?php /* Smarty version 2.6.19, created on 2014-11-23 10:38:42
         compiled from warehouse/index.tpl */ ?>
<div class="search">
  <form id="searchForm" method="get">
  仓库编码：<input type="text" name="shop_name" size="10" maxLength="50" value="<?php echo $this->_tpl_vars['params']['warehouse_sn']; ?>
">
  <input type="submit" name="dosearch" value="搜索" />
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">仓库列表 [<a href="/admin/warehouse/add">添加仓库</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>仓库ID</td>
				<td>仓库编码</td>
				<td>仓库名称</td>
				<td>省</td>
				<td>市</td>
				<td>区</td>
				<td>地址</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val']):
?>
		<tr >
		    <td valign="top"><?php echo $this->_tpl_vars['val']['warehouse_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['warehouse_sn']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['warehouse_name']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['city_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['city_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['district_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['address']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['val']['created_ts']; ?>
</td>
		    <td valign="top"><a href="/admin/warehouse/edit/warehouse_id/<?php echo $this->_tpl_vars['val']['warehouse_id']; ?>
">编辑</a></td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>