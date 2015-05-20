<?php /* Smarty version 2.6.19, created on 2014-10-24 15:06:16
         compiled from data-analysis/in-out-stock.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="<?php echo $this -> callViewHelper('url', array());?>">
    <span style="float:left;line-height:18px;">
      <select name="logic_area">
      <option value="">请选择仓库</option>
      <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
      <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['param']['logic_area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
      </select>
      <select name="stock_type" id="stock_type" onchange="changeStockType()">
        <option value="instock" <?php if ($this->_tpl_vars['param']['stock_type'] == 'instock'): ?>selected<?php endif; ?>>产品入库</option>
        <option value="outstock" <?php if ($this->_tpl_vars['param']['stock_type'] == 'outstock'): ?>selected<?php endif; ?>>产品出库</option>
      </select>&nbsp;&nbsp;
      <select name="bill_type" id="bill_type">
      </select>&nbsp;&nbsp;
      <select name="bill_status" id="bill_status">
      </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">选择日期从：</span><span style="float:left;width:100px;line-height:18px;">
    <input type="text" name="fromdate" id="fromdate" size="12"   value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
" class="Wdate" onClick="WdatePicker()"  /></span>
    <span style="float:left;line-height:18px;">到：</span><span style="float:left;width:150px;line-height:18px;">
    <input type="text" name="todate" id="todate" size="12"  value="<?php echo $this->_tpl_vars['param']['todate']; ?>
" class="Wdate" onClick="WdatePicker()"  /></span>
    <br><br>
    制单人：<input name="admin_name" type="text"  size="20" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
"/>
    产品名称：<input name="product_name" type="text"  size="18" value="<?php echo $this->_tpl_vars['param']['product_name']; ?>
"/>
    产品编号：<input name="product_sn" type="text"  size="8" value="<?php echo $this->_tpl_vars['param']['product_sn']; ?>
"/>
    <input type="button" name="dosearch" value="开始统计" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('todo'=>'search',)));?>','ajax_search')"/>
    </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">产品出入库列表 [<a href="<?php echo $this -> callViewHelper('url', array(array('todo'=>'export',)));?>" target="_blank">导出信息</a>]  </div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td><?php if ($this->_tpl_vars['param']['stock_type'] == 'outstock'): ?>出<?php else: ?>入<?php endif; ?>库单据类型</td>
				<td>产品名称</td>
				<td>产品编码</td>
				<td><?php if ($this->_tpl_vars['param']['stock_type'] == 'outstock'): ?>出库<?php else: ?>入库<?php endif; ?>数量</td>
			  </tr>
		</thead>
		<tbody>
		<?php if ($this->_tpl_vars['datas']): ?>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr>
		  <td><?php echo $this->_tpl_vars['data']['bill_type']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['product_name']; ?>
 <font color="red">(<?php echo $this->_tpl_vars['data']['goods_style']; ?>
)</font></td>
		  <td><?php echo $this->_tpl_vars['data']['product_sn']; ?>
</td>
		  <td><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
		</tr>
		<?php endforeach; endif; unset($_from); ?>
		<thead>
		<tr>
		  <td>合计</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td><?php echo $this->_tpl_vars['total']['Number']; ?>
</td>
		</tr>
		</thead>
		<?php endif; ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
</div>	

<script>
function changeStockType()
{
    $('bill_type').options.length = 0;
    $('bill_type').options.add(new Option('请选择单据类型...', ''));
    
    $('bill_status').options.length = 0;
    $('bill_status').options.add(new Option('请选择单据状态...', ''));
    
    var stock_type = $('stock_type').value;
    if (stock_type == 'instock') {
        <?php $_from = $this->_tpl_vars['in_stock_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['type_name']):
?>
        <?php if ($this->_tpl_vars['type'] == $this->_tpl_vars['param']['bill_type']): ?>
        $('bill_type').options.add(new Option('<?php echo $this->_tpl_vars['type_name']; ?>
', '<?php echo $this->_tpl_vars['type']; ?>
', true, true));
        <?php else: ?>
        $('bill_type').options.add(new Option('<?php echo $this->_tpl_vars['type_name']; ?>
', '<?php echo $this->_tpl_vars['type']; ?>
')); 
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        
        <?php $_from = $this->_tpl_vars['in_stock_status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status'] => $this->_tpl_vars['status_name']):
?>
        <?php if ($this->_tpl_vars['status'] == $this->_tpl_vars['param']['bill_status'] && $this->_tpl_vars['param']['bill_status'] != ''): ?>
        $('bill_status').options.add(new Option('<?php echo $this->_tpl_vars['status_name']; ?>
', '<?php echo $this->_tpl_vars['status']; ?>
', true, true));
        <?php else: ?>
        $('bill_status').options.add(new Option('<?php echo $this->_tpl_vars['status_name']; ?>
', '<?php echo $this->_tpl_vars['status']; ?>
')); 
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    }
    else if (stock_type == 'outstock') {
        <?php $_from = $this->_tpl_vars['out_stock_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['type_name']):
?>
        <?php if ($this->_tpl_vars['type'] == $this->_tpl_vars['param']['bill_type']): ?>
        $('bill_type').options.add(new Option('<?php echo $this->_tpl_vars['type_name']; ?>
', '<?php echo $this->_tpl_vars['type']; ?>
', true, true));
        <?php else: ?>
        $('bill_type').options.add(new Option('<?php echo $this->_tpl_vars['type_name']; ?>
', '<?php echo $this->_tpl_vars['type']; ?>
')); 
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
        
        <?php $_from = $this->_tpl_vars['out_stock_status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status'] => $this->_tpl_vars['status_name']):
?>
        <?php if ($this->_tpl_vars['status'] == $this->_tpl_vars['param']['bill_status'] && $this->_tpl_vars['param']['bill_status'] != ''): ?>
        $('bill_status').options.add(new Option('<?php echo $this->_tpl_vars['status_name']; ?>
', '<?php echo $this->_tpl_vars['status']; ?>
', true, true));
        <?php else: ?>
        $('bill_status').options.add(new Option('<?php echo $this->_tpl_vars['status_name']; ?>
', '<?php echo $this->_tpl_vars['status']; ?>
')); 
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
    }
}
changeStockType();
</script>