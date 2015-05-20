<?php /* Smarty version 2.6.19, created on 2014-11-23 17:28:58
         compiled from box/index.tpl */ ?>
<div class="search">
  <script src="/scripts/my97/WdatePicker.js" type="text/javascript" language="javascript"></script>
  <form id="searchForm" method="get">
  <span style="float:left">箱号：
  <input type="text" value="<?php echo $this->_tpl_vars['params']['box_sn']; ?>
" maxlength="50" size="12" name="box_sn"></span>
  <span style="float:left">新建开始日期：
        <input type="text"  value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        新建截止日期：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <input type="hidden" name="export" value="0" id="export" />
    <input type="button" onclick="doExport(0)" name="dosearch" value="查询" /><input type="button" onclick="doExport(1)" value="导出">

</div>

	<div class="title">装箱列表 [<a href="/admin/box/add">添加箱子</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>箱子ID</td>
				<td>箱号</td>
				<td>备注</td>
                <td>商品SKU种类</th>
                <td>sku数</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
		<tr>
		    <td><?php echo $this->_tpl_vars['info']['box_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['box_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['remark']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_count']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['product_sum']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            <td>[<a href="javascript:void(0);" onclick="del('<?php echo $this->_tpl_vars['info']['box_id']; ?>
', '<?php echo $this->_tpl_vars['info']['product_count']; ?>
')" >删除箱子</a>]&nbsp;&nbsp;&nbsp;&nbsp;[<a href="/admin/box/add-product/box_id/<?php echo $this->_tpl_vars['info']['box_id']; ?>
" >装箱扫描</a>]&nbsp;&nbsp;&nbsp;&nbsp;[<a href="javascript:void(0);" onclick="window.open('/admin/box/print-product/box_id/<?php echo $this->_tpl_vars['info']['box_id']; ?>
')">打印</a>]</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
        <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
	</div>
</form>
<script>
    function del(box_id, number)
    {
        box_id = parseInt(box_id);
        if (box_id < 1) {
            alert('箱子ID不正确');
            return false;
        }

        if (parseInt(number) > 0) {
            alert('箱子里有商品，不能删除');
            return false;
        }

        if (!confirm("确定删除该箱子吗")) {
            return false;
        }

        location.href="/admin/box/del-box/box_id/"+ box_id;
    }

    function doExport(export_id)
    {
        $("export").value = export_id;
        $("searchForm").submit();
    }
</script>