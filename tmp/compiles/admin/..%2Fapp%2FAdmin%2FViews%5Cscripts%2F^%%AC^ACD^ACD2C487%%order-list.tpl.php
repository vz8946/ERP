<?php /* Smarty version 2.6.19, created on 2014-11-22 22:21:36
         compiled from shop/order-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'shop/order-list.tpl', 143, false),)), $this); ?>
<script type="text/javascript">
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get" action="/admin/shop/order-list">
  <span style="float:left;line-height:18px;">订单开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">订单结束日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
  当前店铺：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    <?php $_from = $this->_tpl_vars['shopDatas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
      <?php if ($this->_tpl_vars['data']['shop_type'] != 'tuan' && $this->_tpl_vars['data']['shop_type'] != 'jiankang' && $this->_tpl_vars['data']['shop_type'] != 'distribution' && $this->_tpl_vars['data']['shop_type'] != 'credit'): ?>
      <option value="<?php echo $this->_tpl_vars['data']['shop_id']; ?>
" <?php if ($this->_tpl_vars['data']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</option>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
  业务状态：
	<select name="status_business">
	    <option value="">请选择...</option>
	    <option value="0" <?php if ($this->_tpl_vars['param']['status_business'] == '0'): ?>selected<?php endif; ?>>未审核</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['status_business'] == '1'): ?>selected<?php endif; ?>>审核通过</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['status_business'] == '2'): ?>selected<?php endif; ?>>已打印</option>
		<option value="4" <?php if ($this->_tpl_vars['param']['status_business'] == '4'): ?>selected<?php endif; ?>>发货中</option>
		<option value="9" <?php if ($this->_tpl_vars['param']['status_business'] == '9'): ?>selected<?php endif; ?>>审核不通过</option>
	</select>
  <br><br>
  第3方物流发货：
	<select name="other_logistics">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['other_logistics'] == '1'): ?>selected<?php endif; ?>>是</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['other_logistics'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
	</select>
  是否同步：
	<select name="sync">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['sync'] == '1'): ?>selected<?php endif; ?>>是</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['sync'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
  是否刷单：
	<select name="is_fake">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['is_fake'] == '1'): ?>selected<?php endif; ?>>是</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['is_fake'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
  已开票：
    <select name="done_invoice">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['done_invoice'] == '1'): ?>selected<?php endif; ?>>是</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['done_invoice'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
  备注：
	<select name="admin_memo">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['admin_memo'] == '1'): ?>selected<?php endif; ?>>有</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['admin_memo'] == '2'): ?>selected<?php endif; ?>>无</option>
	</select>
  付款方式：
	<select name="is_cod">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['is_cod'] == '1'): ?>selected<?php endif; ?>>货到付款</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['is_cod'] == '0'): ?>selected<?php endif; ?>>非货到付款</option>
	</select>
  开票信息：
    <select name="invoice">
        <option value="">请选择...</option>
        <option value="0" <?php if ($this->_tpl_vars['param']['invoice'] == '0'): ?>selected<?php endif; ?>>不开发票</option>
        <option value="3" <?php if ($this->_tpl_vars['param']['invoice'] == '3'): ?>selected<?php endif; ?>>需开发票</option>
    </select>
  <br><br>
  订单号：<input type="text" name="external_order_sn" size="16" maxLength="50" value="<?php echo $this->_tpl_vars['param']['external_order_sn']; ?>
">
  收货人：<input type="text" name="addr_consignee" size="6" maxLength="60" value="<?php echo $this->_tpl_vars['param']['addr_consignee']; ?>
">
  手机：<input type="text" name="addr_mobile" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['addr_mobile']; ?>
">
  物流单号：<input type="text" name="logistic_no" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['logistic_no']; ?>
">
  商品编码：<input type="text" name="goods_sn" size="6" maxLength="10" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
" onchange="if (document.getElementById('goods_number').value == '') document.getElementById('goods_number').value = '1'">
  商品数量：<input type="text" name="goods_number" id="goods_number" size="1" maxLength="5" value="<?php echo $this->_tpl_vars['param']['goods_number']; ?>
">
  <input type="submit" name="dosearch" value="搜索"/>
  <input type="button" value="下载指定订单" onclick="downloadOrder()">
  <br>
  订单状态：
  <input type="checkbox" name="status[]" value="1" <?php if ($this->_tpl_vars['param']['status']['1']): ?>checked<?php endif; ?>>待收款
  <input type="checkbox" name="status[]" value="2" <?php if ($this->_tpl_vars['param']['status']['2']): ?>checked<?php endif; ?>>待发货
  <input type="checkbox" name="status[]" value="3" <?php if ($this->_tpl_vars['param']['status']['3']): ?>checked<?php endif; ?>>待确认收货
  <input type="checkbox" name="status[]" value="10" <?php if ($this->_tpl_vars['param']['status']['10']): ?>checked<?php endif; ?>>已完成
  <input type="checkbox" name="status[]" value="11" <?php if ($this->_tpl_vars['param']['status']['11']): ?>checked<?php endif; ?>>已取消
  <input type="checkbox" name="status[]" value="12" <?php if ($this->_tpl_vars['param']['status']['12']): ?>checked<?php endif; ?>>其它
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	<?php if ($this->_tpl_vars['auth']['group_id'] == 14 || $this->_tpl_vars['auth']['group_id'] == 1): ?>
	  <input type="submit" name="export" value="导出订单" onclick="doExport()"/>
	<?php endif; ?>
	</div>
	<div style="float:right;"><b>订单总金额：￥<?php echo $this->_tpl_vars['amount']; ?>
</b></div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
			    <td>店铺</td>
			    <td>订单号</td>
				<td>订单金额</td>
				<td >订单商品</td>
				<td >收货人</td>
				<td>下单时间</td>
				<td>运费</td>
				<td>订单状态</td>
				<td>业务状态</td>
				<td>已开票</td>
				<td>同步</td>
				<td>刷单</td>
				<td>地址匹配</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
		    <td valign="top"><a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
', 'order<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
', 'height=600,width=800,toolbar=no,scrollbars=yes')"><?php echo $this->_tpl_vars['data']['external_order_sn']; ?>
</a></td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
			<td valign="top">
			<?php if ($this->_tpl_vars['data']['goods']): ?>
			  <?php $_from = $this->_tpl_vars['data']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
			  <a title="<?php echo $this->_tpl_vars['goods']['shop_goods_name']; ?>
"><?php echo $this->_tpl_vars['goods']['goods_sn']; ?>
</a>*<?php echo $this->_tpl_vars['goods']['number']; ?>
<br>
			  <?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['addr_consignee']; ?>
</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['freight']; ?>
</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['status'] == 1): ?>待收款
			  <?php elseif ($this->_tpl_vars['data']['status'] == 2): ?>待发货
			  <?php elseif ($this->_tpl_vars['data']['status'] == 3): ?>待确认收货
			  <?php elseif ($this->_tpl_vars['data']['status'] == 10): ?>已完成
			  <?php elseif ($this->_tpl_vars['data']['status'] == 11): ?>已取消
			  <?php elseif ($this->_tpl_vars['data']['status'] == 12): ?>其它
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['status_business'] == 0): ?>未审核
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 1): ?>审核通过
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 2): ?>已打印
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 9): ?>审核不通过
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['done_invoice'] == 1): ?>是
			  <?php else: ?>否
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['sync'] == 1): ?>是
			  <?php else: ?>否
			  <?php endif; ?>
			</td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['is_fake'] == 1): ?>未销账
			  <?php elseif ($this->_tpl_vars['data']['is_fake'] == 2): ?>已销账
			  <?php else: ?>否
			  <?php endif; ?>
			</td>
			<td>
			  <?php if ($this->_tpl_vars['data']['addr_province_id'] == 0 || $this->_tpl_vars['data']['addr_city_id'] == 0 || $this->_tpl_vars['data']['addr_area_id'] == 0): ?>失败
			  <?php else: ?>成功
			  <?php endif; ?>
			</td>
		  </tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
		</table>
	</div>
	<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>

<script type="text/javascript">
function doExport()
{
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='/admin/shop/export';
    document.getElementById('searchForm').submit();
}

function downloadOrder()
{
    if (document.getElementById('shop_id').value == '') {
        alert('请先选择店铺!');
        return false;
    }
    
    var orderSN = window.prompt("请输入订单号", '');
    if (orderSN == '' || orderSN == null)  return false;
    
    window.open('/admin/shop/sync/action_name/order/orderSN/' + orderSN + '/id/' + document.getElementById('shop_id').value);
}
</script>