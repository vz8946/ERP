<?php /* Smarty version 2.6.19, created on 2014-10-30 11:49:30
         compiled from customer/product-customer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'customer/product-customer.tpl', 11, false),)), $this); ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <form name="searchForm" id="searchForm" action="/admin/customer/product-customer">
    <span style="float:left;line-height:18px;">
    <select name="shop_id">
        <option value="">请选择店铺</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['shop_info'],'selected' => $this->_tpl_vars['params']['shop_id']), $this);?>

        </select>&nbsp;&nbsp;
    </span>
    <span style="float:left;line-height:18px;">制单开始日期：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="start_ts" id="start_ts" size="15" value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    <span style="float:left;line-height:18px;">结束日期：</span>
    <span style="float:left;line-height:18px;"><input type="text" name="end_ts" id="end_ts" size="15" value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" class="Wdate" onClick="WdatePicker()" /></span>
    &nbsp;&nbsp;
    订单状态:<select name="status">
            <option value=''>--请选择--</option>
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['order_status'],'selected' => $this->_tpl_vars['params']['status']), $this);?>

			</select>
    <br><br>
    商品编码：<input name="product_sn" id="product_sn" type="text"  size="10" value="<?php echo $this->_tpl_vars['params']['product_sn']; ?>
" />
    <input type="hidden" name="export" id="export" value="0" />
    <input type="button" name="dosearch" value="查询" onclick="check(0);"/>
    <input type="button" name="dosearch" value="导出" onclick="check(1);"/>
  </form>	
	</td>
    <td>  </td>
  </tr>
</table>

</div>
<?php endif; ?>

<div id="ajax_search">

<div class="title">产品客户购买记录</div>
	<div class="content">
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
                <td>客户ID</td>
				<td>客户姓名</td>
				<td>手机</td>
				<td>电话</td>
                <td>省份</td>
				<td>店铺</td>
				<td>产品名称</td>
                <td>订单ID</td>
                <td>购买次数</td>
				<td>购买数量</td>
				<td>购买总金额</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
            <tr>
                <td><?php echo $this->_tpl_vars['info']['customer_id']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['real_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['mobile']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['telphone']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['province_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['shop_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['goods_name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['order_id']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['buy_count']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['buy_number']; ?>
</td>
                <td><?php echo $this->_tpl_vars['info']['price_goods']; ?>
</td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
<script language="javascript">
    function check(value)
    {
        $product_sn = $('product_sn').value;

        if ($product_sn.trim() == '') {
            alert('产品编码不能为空');
            return false;
        }

        $('export').value = value;
        $('searchForm').submit();
    }
</script>