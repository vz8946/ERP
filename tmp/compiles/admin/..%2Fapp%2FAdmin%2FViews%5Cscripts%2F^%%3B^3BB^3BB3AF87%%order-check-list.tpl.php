<?php /* Smarty version 2.6.19, created on 2014-11-22 19:31:58
         compiled from shop/order-check-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'shop/order-check-list.tpl', 58, false),array('modifier', 'date_format', 'shop/order-check-list.tpl', 194, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
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
<div class="search">
  <form id="searchForm" method="get">
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
      <?php if ($this->_tpl_vars['data']['shop_type'] != 'tuan' && $this->_tpl_vars['data']['shop_type'] != 'jiankang' && $this->_tpl_vars['data']['shop_type'] != 'credit' && $this->_tpl_vars['data']['shop_type'] != 'distribution'): ?>
      <option value="<?php echo $this->_tpl_vars['data']['shop_id']; ?>
" <?php if ($this->_tpl_vars['data']['shop_id'] == $this->_tpl_vars['param']['shop_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['data']['shop_name']; ?>
<?php if ($this->_tpl_vars['data']['orderCount']): ?>(<?php echo $this->_tpl_vars['data']['orderCount']; ?>
)<?php endif; ?></option>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  </select>
  订单状态：
	<select name="status">
		<option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>待发货</option>
		<option value="3" <?php if ($this->_tpl_vars['param']['status'] == '3'): ?>selected<?php endif; ?>>待确认收货</option>
		<option value="10" <?php if ($this->_tpl_vars['param']['status'] == '10'): ?>selected<?php endif; ?>>已完成</option>
		<option value="11" <?php if ($this->_tpl_vars['param']['status'] == '11'): ?>selected<?php endif; ?>>取消</option>
	</select>
  业务状态：
	<select name="status_business">
		<option value="0" <?php if ($this->_tpl_vars['param']['status_business'] == '0'): ?>selected<?php endif; ?>>未审核</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['status_business'] == '1'): ?>selected<?php endif; ?>>已审核</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['status_business'] == '2'): ?>selected<?php endif; ?>>已打印</option>
		<option value="9" <?php if ($this->_tpl_vars['param']['status_business'] == '9'): ?>selected<?php endif; ?>>审核不通过</option>
	</select>
    限价:<input type="checkbox" name="audit_status" value="1" <?php if ($this->_tpl_vars['param']['audit_status'] == '1'): ?>checked='true'<?php endif; ?>/>
  <br><br>
  是否刷单：
	<select name="is_fake">
		<option value="1" <?php if ($this->_tpl_vars['param']['is_fake'] == '1'): ?>selected<?php endif; ?>>是</option>
		<option value="0" <?php if ($this->_tpl_vars['param']['is_fake'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
  地址匹配：
	<select name="check_address">
		<option value="">请选择...</option>
		<option value="1" <?php if ($this->_tpl_vars['param']['check_address'] == '1'): ?>selected<?php endif; ?>>匹配成功</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['check_address'] == '2'): ?>selected<?php endif; ?>>匹配失败</option>
	</select>
  物流公司：
    <select name="logistic_code">
        <option value="">请选择...</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => $this->_tpl_vars['param']['logistic_code']), $this);?>

    </select>
  开票信息：
    <select name="invoice">
        <option value="">请选择...</option>
        <option value="0" <?php if ($this->_tpl_vars['param']['invoice'] == '0'): ?>selected<?php endif; ?>>不开发票</option>
        <option value="3" <?php if ($this->_tpl_vars['param']['invoice'] == '3'): ?>selected<?php endif; ?>>需开发票</option>
        <!--
		<option value="1" <?php if ($this->_tpl_vars['param']['invoice'] == '1'): ?>selected<?php endif; ?>>个人发票</option>
		<option value="2" <?php if ($this->_tpl_vars['param']['invoice'] == '2'): ?>selected<?php endif; ?>>公司发票</option>
		-->
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
  <br>
  订单号：<input type="text" name="external_order_sn" size="16" maxLength="20" value="<?php echo $this->_tpl_vars['param']['external_order_sn']; ?>
">
  收货人：<input type="text" name="addr_consignee" size="6" maxLength="30" value="<?php echo $this->_tpl_vars['param']['addr_consignee']; ?>
">
  手机：<input type="text" name="addr_mobile" size="10" maxLength="20" value="<?php echo $this->_tpl_vars['param']['addr_mobile']; ?>
">
  备注：<input type="text" name="memo" size="10" value="<?php echo $this->_tpl_vars['param']['memo']; ?>
">
  商品编码：<input type="text" name="goods_sn" size="6" maxLength="10" value="<?php echo $this->_tpl_vars['param']['goods_sn']; ?>
" onchange="if (document.getElementById('goods_number').value == '') document.getElementById('goods_number').value = '1'">
  商品数量：<input type="text" name="goods_number" id="goods_number" size="1" maxLength="5" value="<?php echo $this->_tpl_vars['param']['goods_number']; ?>
">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'<?php echo $this -> callViewHelper('url', array(array('dosearch'=>'search',)));?>','ajax_search')"/>
  <input type="button" name="dodownload" value="下载当天订单" onclick="downloadDayOrder()"/>
  <br>
  收货地址(省)：
  <input type="checkbox" name="chkprovinceall" title="全选/全不选" onclick="checkprovinceall(this)"/>全选/全不选
  <?php $_from = $this->_tpl_vars['provinceData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['province'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['province']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['province_name'] => $this->_tpl_vars['province_id']):
        $this->_foreach['province']['iteration']++;
?>
  <?php if ($this->_tpl_vars['province_id'] != 3982): ?><input type="checkbox" name="province" value="<?php echo $this->_tpl_vars['province_id']; ?>
" <?php if ($this->_tpl_vars['param']['province'][$this->_tpl_vars['province_id']]): ?>checked<?php endif; ?>><?php echo $this->_tpl_vars['province_name']; ?>
<?php endif; ?>
  <?php if ($this->_foreach['province']['iteration'] == 16): ?><br>　　　　　　　　　　　　　　&nbsp;&nbsp;<?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  </form>
</div>
<form name="myForm" id="myForm" method="post" action="<?php echo $this -> callViewHelper('url', array());?>">
    <input type="hidden" name="todo" id="todo">
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	  <?php if ($this->_tpl_vars['param']['status_business'] == '0'): ?>
	  <input type="submit" name="check" value="审核通过选中订单" onclick="return doCheck1('')"/>
	  <input type="submit" name="check" value="审核通过选中订单并导入官网处理" onclick="return doCheck1('import')"/>
	  <input type="submit" name="check" value="审核不通过选中订单" onclick="return doCheck9()"/>
	  <input type="submit" name="export" value="导出需开票订单 > 已开票" onclick="return doExportInvoice()"/>
	  <?php elseif ($this->_tpl_vars['param']['status_business'] == '1'): ?>
	  <input type="submit" name="check" value="反审核选中订单" onclick="return doCheck0()"/>
	  <input type="submit" name="check" value="选中订单导入官网处理" onclick="return doCheck1('import')"/>
	  <?php elseif ($this->_tpl_vars['param']['status_business'] == '2'): ?>
	  <input type="submit" name="check" value="反审核选中订单" onclick="return doCheck0()"/>
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['param']['status_business'] == '2'): ?>
	  <!--
	  &nbsp;物流公司：
        <select name="set_logistic_code">
        	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => 'yt'), $this);?>

        </select>
      -->
	  <input type="submit" name="setLogistics" value="重新设置选中订单的物流公司" onclick="return doSetLogistics()"/>
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['param']['status_business'] == '9'): ?>
	  <!--
	    &nbsp;物流公司：
        <select name="set_logistic_code">
        	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => 'yt'), $this);?>

        </select>
      -->
	  <input type="submit" name="setLogistics" value="设置选中订单的物流公司并审核通过" onclick="return doSetLogisticsAndCheck()"/>
	  <input type="submit" name="check" value="审核通过选中订单并导入官网处理" onclick="return doCheck1('import')"/>
	  <?php endif; ?>
	  <br>
	  <?php if ($this->_tpl_vars['param']['status_business'] == '0'): ?>
	  <!--
	  &nbsp;物流公司：
        <select name="set_logistic_code">
          <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['logisticList'],'selected' => 'yt'), $this);?>

        </select>
      -->
      <input type="submit" name="setLogistics" value="设置选中订单的物流公司" onclick="return doSetLogistics()"/>
	  <input type="submit" name="setLogistics" value="设置选中订单的物流公司并审核通过" onclick="return doSetLogisticsAndCheck()"/>
	  <?php endif; ?>
	  <input type="button" name="export" value="导出订单" onclick="doExport()"/>
	</div>
	<div style="float:right;"><b>订单总金额：￥<?php echo $this->_tpl_vars['amount']; ?>
</b></div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/></td>
			    <td>ID</td>
			    <td>店铺</td>
			    <td>订单号</td>
				<td>金额</td>
				<td >订单商品</td>
				<td >收货人</td>
				<td>下单时间</td>
				<td>状态</td>
				<td>物流</td>
				<td>店铺备注</td>
				<td>物流备注</td>
				<td>状态</td>
				<td>刷单</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
		<tr >
		    <td valign="top"><input type='checkbox' name="ids[]" value="<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
"></td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
</td>
		    <td valign="top"><?php echo $this->_tpl_vars['data']['shop_name']; ?>
</td>
		    <td valign="top">
		      <a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
', 'order<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
', 'height=600,width=800,toolbar=no,scrollbars=yes')"><?php echo $this->_tpl_vars['data']['external_order_sn']; ?>
</a>
		      <?php if ($this->_tpl_vars['data']['invoice_title']): ?><br><?php echo $this->_tpl_vars['data']['invoice_title']; ?>
<?php endif; ?>
		    </td>
			<td valign="top" <?php if ($this->_tpl_vars['data']['audit_status'] == '1'): ?>style="color:#ff0000"<?php endif; ?>><?php echo $this->_tpl_vars['data']['amount']; ?>
</td>
			<td valign="top">
			<?php if ($this->_tpl_vars['data']['goods']): ?>
			  <?php $_from = $this->_tpl_vars['data']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods']):
?>
			  <a title="<?php echo $this->_tpl_vars['goods']['shop_goods_name']; ?>
"><?php if ($this->_tpl_vars['replenishmentInfo'][$this->_tpl_vars['data']['shop_order_id']][$this->_tpl_vars['goods']['goods_sn']]): ?><font color="<?php echo $this->_tpl_vars['replenishmentInfo'][$this->_tpl_vars['data']['shop_order_id']][$this->_tpl_vars['goods']['goods_sn']]; ?>
"><?php echo $this->_tpl_vars['goods']['goods_sn']; ?>
</font><?php else: ?><?php echo $this->_tpl_vars['goods']['goods_sn']; ?>
<?php endif; ?></a>*<?php echo $this->_tpl_vars['goods']['number']; ?>
<br>
			  <?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			</td>
			<td valign="top"><?php echo $this->_tpl_vars['data']['addr_consignee']; ?>
</td>
			<td valign="top"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['order_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
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
			  <select name="order_logistic_code[<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
]">
			    <option value=""></option>
			    <?php if ($this->_tpl_vars['data']['is_cod']): ?>
			    <?php $this->assign('currentLogisticList', $this->_tpl_vars['logisticListCod']); ?>
			    <?php else: ?>
			    <?php $this->assign('currentLogisticList', $this->_tpl_vars['logisticList']); ?>
			    <?php endif; ?>
			    <?php $_from = $this->_tpl_vars['currentLogisticList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['logistic_code'] => $this->_tpl_vars['logistic_name']):
?>
			      <?php if ($this->_tpl_vars['logistic_code'] != 'self' && $this->_tpl_vars['logistic_code'] != 'externalself' || ( $this->_tpl_vars['logistic_code'] == 'externalself' && $this->_tpl_vars['data']['is_cod'] )): ?>
			      <option value="<?php echo $this->_tpl_vars['logistic_code']; ?>
" <?php if ($this->_tpl_vars['data']['logisticPolicy'][$this->_tpl_vars['logistic_code']]): ?>style="color:purple;"<?php endif; ?> <?php if (( $this->_tpl_vars['data']['logistic_code'] == '' && $this->_tpl_vars['data']['shop_id'] != 9 && $this->_tpl_vars['data']['logisticPolicy'][$this->_tpl_vars['logistic_code']] == '1' ) || ( $this->_tpl_vars['data']['logistic_code'] == $this->_tpl_vars['logistic_code'] )): ?>selected<?php endif; ?>><?php if ($this->_tpl_vars['data']['logisticPolicy'][$this->_tpl_vars['logistic_code']]): ?>(<?php echo $this->_tpl_vars['data']['logisticPolicy'][$this->_tpl_vars['logistic_code']]; ?>
)<?php endif; ?><?php echo $this->_tpl_vars['logistic_name']; ?>
</option>
			      <?php endif; ?>
			    <?php endforeach; endif; unset($_from); ?>
			  </select>
			</td>
			<td valign="top"><textarea rows=3 style="width:120px" onblur="updateMemo(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
, this.value)"><?php echo $this->_tpl_vars['data']['memo']; ?>
</textarea></td>
			<td valign="top"><?php if ($this->_tpl_vars['data']['admin_memo']): ?><a href="javascript:fGo()" onclick="showAdminMemoWin(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
)"><b>查看</b></a><?php else: ?><a href="javascript:fGo()" onclick="showAdminMemoWin(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
)">添加</a><?php endif; ?></td>
			<td valign="top">
			  <?php if ($this->_tpl_vars['data']['status_business'] == 0): ?>未审核
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 1): ?>审核通过
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 2): ?>已打印
			  <?php elseif ($this->_tpl_vars['data']['status_business'] == 9): ?>审核不通过
			  <?php endif; ?>
			</td>
			<td valign="top" id="is_fake_<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
">
			  <!--
			  <?php if ($this->_tpl_vars['data']['is_fake'] == '0' || $this->_tpl_vars['data']['is_fake'] == '1'): ?>
			  <input type="checkbox" name="is_fake_<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
" value="1" <?php if ($this->_tpl_vars['data']['is_fake']): ?>checked<?php endif; ?> onchange="changeFake(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
, this.checked)">
			  <?php else: ?>
			  已销账
			  <?php endif; ?>
			  -->
			  <?php if ($this->_tpl_vars['data']['is_fake'] == '0'): ?>
			  否
			  <?php elseif ($this->_tpl_vars['data']['is_fake'] == '1'): ?>
			  未销账
			  <?php else: ?>
			  已销账
			  <?php endif; ?>
			</td>
			<td>
			  <?php if ($this->_tpl_vars['param']['status_business'] == '0' || $this->_tpl_vars['param']['status_business'] == '9'): ?>
			  <a href="javascript:fGo()" onclick="showGoodsWin(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
)">商品</a>
			  <a href="javascript:fGo()" onclick="showInvoiceWin(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
)">发票</a>
			  <?php endif; ?>
			  <?php if ($this->_tpl_vars['param']['status_business'] == '0' || $this->_tpl_vars['param']['status_business'] == '1'): ?>
			  <a href="javascript:fGo()" onclick="showAddressWin(<?php echo $this->_tpl_vars['data']['shop_order_id']; ?>
)">
			    <?php if ($this->_tpl_vars['data']['addr_province_id'] == 0 || $this->_tpl_vars['data']['addr_city_id'] == 0 || $this->_tpl_vars['data']['addr_area_id'] == 0): ?><font color="red">地址</a>
			    <?php else: ?>地址
			    <?php endif; ?>
			  </a>
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
function showAddressWin(id)
{
	if (window.pageYOffset) {
    	y = window.pageYOffset;
    } 
    else if (document.documentElement && document.documentElement.scrollTop) {
    	y = document.documentElement.scrollTop;
    } 
    else if (document.body) {
        y = document.body.scrollTop;
    }
    
	var run = win.createWindow("addressWindow", 200, 100, 400, 200);
	run.setText("修改地址");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/shop/order-address/id/" + id + '/url/' + base64encode('<?php echo $this -> callViewHelper('url', array());?>'), true);
}

function showAdminMemoWin(id)
{
    if (window.pageYOffset) {
    	y = window.pageYOffset;
    } 
    else if (document.documentElement && document.documentElement.scrollTop) {
    	y = document.documentElement.scrollTop;
    } 
    else if (document.body) {
        y = document.body.scrollTop;
    }
    
	var run = win.createWindow("adminMemoWindow", 200, 100, 400, 300);
	run.setText("客服备注");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/shop/order-admin-memo/id/" + id + '/url/' + base64encode('<?php echo $this -> callViewHelper('url', array());?>'), true);
}

function showGoodsWin(id)
{
	if (window.pageYOffset) {
    	y = window.pageYOffset;
    } 
    else if (document.documentElement && document.documentElement.scrollTop) {
    	y = document.documentElement.scrollTop;
    } 
    else if (document.body) {
        y = document.body.scrollTop;
    }
    
	var run = win.createWindow("goodsWindow", 200, 100, 650, 600);
	run.setText("修改商品详情");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/shop/order-goods/id/" + id + '/url/' + base64encode('<?php echo $this -> callViewHelper('url', array());?>'), true);
}

function showInvoiceWin(id)
{
	if (window.pageYOffset) {
    	y = window.pageYOffset;
    } 
    else if (document.documentElement && document.documentElement.scrollTop) {
    	y = document.documentElement.scrollTop;
    } 
    else if (document.body) {
        y = document.body.scrollTop;
    }
    
	var run = win.createWindow("invoiceWindow", 200, 100, 400, 200);
	run.setText("修改发票详情");
	run.button("minmax1").hide();
	run.button("park").hide();
	run.denyResize();
	run.denyPark();
	run.setModal(true);
	run.attachURL("/admin/shop/order-invoice/id/" + id + '/url/' + base64encode('<?php echo $this -> callViewHelper('url', array());?>'), true);
}

function changeArea(type, id)
{
    new Request({
        url: '/admin/shop/get-area/type/' + type + '/id/' + id,
        onRequest: loading,
        onSuccess:function(data){
            if (type == 'province') {
                document.getElementById('cityArea').innerHTML = '<select name="city_id" id="city_id" onchange="changeArea(\'city\', this.value)">' + data + '</select>';
                changeArea('city', document.getElementById('city_id').value);
            }
            else if (type == 'city') {
                document.getElementById('areaArea').innerHTML = '<select name="area_id" id="area_id">' + data + '</select>';
            }
        }
    }).send();
}
var base64encodechars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64decodechars = new Array(
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
    -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,
    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function base64encode(str) {
    var out, i, len;
    var c1, c2, c3;
    len = str.length;
    i = 0;
    out = "";
    while (i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if (i == len) {
            out += base64encodechars.charAt(c1 >> 2);
            out += base64encodechars.charAt((c1 & 0x3) << 4);
            out += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if (i == len) {
            out += base64encodechars.charAt(c1 >> 2);
            out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));
            out += base64encodechars.charAt((c2 & 0xf) << 2);
            out += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        out += base64encodechars.charAt(c1 >> 2);
        out += base64encodechars.charAt(((c1 & 0x3) << 4) | ((c2 & 0xf0) >> 4));
        out += base64encodechars.charAt(((c2 & 0xf) << 2) | ((c3 & 0xc0) >> 6));
        out += base64encodechars.charAt(c3 & 0x3f);
    }
    return out;
}

function doExport()
{
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='/admin/shop/export';
    document.getElementById('searchForm').submit();
}

function doCheck1(todo)
{
    if (todo == 'import') {
        if (!confirm('确定要导入官网订单走正常订单流程吗？')) return false;
        document.getElementById('todo').value = 'import';
    }
    else    document.getElementById('todo').value = 'status1';
    document.getElementById('myForm').action='<?php echo $this -> callViewHelper('url', array());?>';
    return true;
}

function doCheck0(todo)
{
    if (confirm('确定要反审核选中订单吗？')) {
        document.getElementById('todo').value = 'status0';
        document.getElementById('myForm').action='<?php echo $this -> callViewHelper('url', array());?>';
        return true;
    }
    return false;
}

function doCheck9()
{
    document.getElementById('todo').value = 'status9';
    document.getElementById('myForm').action='<?php echo $this -> callViewHelper('url', array());?>';
    return true;
}

function doSetLogistics()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'logistics';
    document.getElementById('myForm').action='<?php echo $this -> callViewHelper('url', array());?>';
    return true;
}

function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
    }
}

function doSetLogisticsAndCheck()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'logistics_status1';
    document.getElementById('myForm').action='<?php echo $this -> callViewHelper('url', array());?>';
    return true;
}

function downloadDayOrder()
{
    if (document.getElementById('shop_id').value == '') {
        alert('请先选择店铺!');
        return false;
    }
    
    window.open('/admin/shop/sync/action_name/order/fromdate/<?php echo $this->_tpl_vars['currentDate']; ?>
/todate/<?php echo $this->_tpl_vars['currentDate']; ?>
/id/' + document.getElementById('shop_id').value);
}

function doExportInvoice()
{
    if (confirm('该操执行后将把状态设为已开票，确认执行吗？')) {
        document.getElementById('myForm').target='';
        document.getElementById('myForm').method='post';
        document.getElementById('myForm').action='/admin/shop/export-invoice';
        return true;
    }
}

function changeFake(shop_order_id, value)
{
    new Request({url: '/admin/shop/order-ajax-change/id/' + shop_order_id + '/field/is_fake/val/' + value,
            method:'get' ,
        onSuccess: function(responseText) {
            $('is_fake_' + shop_order_id).innerHTML = '已设置';
        }
    }).send();
}

function updateMemo(shop_order_id, value)
{
    return false;
    new Request({url: '/admin/shop/order-ajax-change/id/' + shop_order_id + '/field/memo/val/' + encodeURI(value),
            method:'get' ,
        onSuccess: function(responseText) {
            if (responseText != 'same') {
                //alert('保存成功!');
            }
        }
    }).send();
}

</script>