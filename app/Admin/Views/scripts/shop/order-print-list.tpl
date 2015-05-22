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
  <form id="searchForm" name="searchForm" method="get">
  <input type="hidden" name="toFormalOrderID" id="toFormalOrderID">
  <span style="float:left;line-height:18px;">订单开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">订单结束日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
  当前店铺：
  <select name="shop_id" id="shop_id" onchange="document.getElementById('dosearch').click();">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit' && $data.shop_type ne 'distribution'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}{{if $data.orderCount}}({{$data.orderCount}}){{/if}}</option>
      {{/if}}
    {{/foreach}}
  </select>
  订单状态：
	<select name="status">
		<option value="2" {{if $param.status eq '2'}}selected{{/if}}>待发货</option>
	</select>
  业务状态：
	<select name="status_business">
		<option value="1" {{if $param.status_business eq '1'}}selected{{/if}}>审核通过</option>
	</select>
  <br><br>
  是否刷单：
	<select name="is_fake">
		<option value="">请选择...</option>
		<option value="1" {{if $param.is_fake eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.is_fake eq '0'}}selected{{/if}}>否</option>
	</select>
  开票信息：
    <select name="invoice">
        <option value="">请选择...</option>
        <option value="0" {{if $param.invoice eq '0'}}selected{{/if}}>不开发票</option>
        <option value="3" {{if $param.invoice eq '3'}}selected{{/if}}>需开发票</option>
        <!--
		<option value="1" {{if $param.invoice eq '1'}}selected{{/if}}>个人发票</option>
		<option value="2" {{if $param.invoice eq '2'}}selected{{/if}}>公司发票</option>
		-->
    </select>
  已开票：
    <select name="done_invoice">
		<option value="">请选择...</option>
		<option value="1" {{if $param.done_invoice eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.done_invoice eq '0'}}selected{{/if}}>否</option>
	</select>
  物流公司：
    <select name="logistic_code">
      <option value="">请选择...</option>
      {{html_options options=$logisticList selected=$param.logistic_code}}
    </select>
  客服备注：
	<select name="admin_memo">
		<option value="">请选择...</option>
		<option value="1" {{if $param.admin_memo eq '1'}}selected{{/if}}>有</option>
		<option value="2" {{if $param.admin_memo eq '2'}}selected{{/if}}>无</option>
	</select>
  付款方式：
	<select name="is_cod">
		<option value="">请选择...</option>
		<option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option>
		<option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option>
	</select>
  锁定方式：
	<select name="lock">
		<option value="">请选择...</option>
		<option value="1" {{if $param.lock eq '1'}}selected{{/if}}>当前用户</option>
	</select>
  <br>
  订单号：<input type="text" name="external_order_sn" size="16" maxLength="50" value="{{$param.external_order_sn}}">
  收货人：<input type="text" name="addr_consignee" size="6" maxLength="60" value="{{$param.addr_consignee}}">
  手机：<input type="text" name="addr_mobile" size="10" maxLength="20" value="{{$param.addr_mobile}}">
  商品编码：<input type="text" name="goods_sn" size="6" maxLength="10" value="{{$param.goods_sn}}" onchange="if (document.getElementById('goods_number').value == '') document.getElementById('goods_number').value = '1'">
  商品数量：<input type="text" name="goods_number" id="goods_number" size="1" maxLength="5" value="{{$param.goods_number}}">
  锁定用户：<input type="text" name="lock_admin_name" id="lock_admin_name" size="8" value="{{$param.lock_admin_name}}">
  <input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  <br>
  收货地址(省)：
  <input type="checkbox" name="chkprovinceall" title="全选/全不选" onclick="checkprovinceall(this)"/>全选/全不选
  {{foreach from=$provinceData item=province_id key=province_name name=province}}
  {{if $province_id ne 3982}}<input type="checkbox" name="province" value="{{$province_id}}" {{if $param.province.$province_id}}checked{{/if}}>{{$province_name}}{{/if}}
  {{if $smarty.foreach.province.iteration eq 16}}<br>　　　　　　　　　　　　　　&nbsp;&nbsp;{{/if}}
  {{/foreach}}
  </form>
</div>
<form name="myForm" id="myForm" method="post" action="{{url}}">
<input type="hidden" name="todo" id="todo">
<input type="hidden" name="print_shop_id" id="print_shop_id">
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	  <input type="button" name="fill" value="填充物流单号" onclick="doFill()"/>
	  <input type="button" name="printSales" value="打印选中销售单" onclick="doPrintSales()"/>
	  <input type="button" name="printSum" value="打印选中拣货单" onclick="doPrintSum()"/>
	  <!--<input type="button" name="sendGoodsReport" value="商品发货统计" onclick="doSendGoodsReport()"/>-->
	  <input type="button" name="export" value="导出订单" onclick="doExport()"/>
	  <br>
	  <input type="button" name="printLogistics" value="打印选中运输单" onclick="doPrintLogistics()"/>
	  <!--<input type="button" name="otherLogisticsSend" value="提交选中订单给第3方物流发货(只适用于也买送) > 发货中" onclick="doOtherlogisticsSend()"/>-->
	  <input type="button" name="export" value="导出需开票订单 > 已开票" onclick="doExportInvoice()"/>
       <br>
      <input type="button" name="check" value="更新选中订单状态 > 已打印" onclick="doCheck2()"/>
      <input type="button" name="send1" value="直接发货选中的订单 > 已发货" onclick="doSend1()"/>
      <input type="button" name="otherLogistics" value="导出选中第3方物流发货订单 > 已打印" onclick="doOtherlogistics()"/>
      <br>
      <input type="button" name="lockOrder" value="锁定选中订单" onclick="doLockOrder()"/>
	  <input type="button" name="unlockOrder" value="解锁选中订单" onclick="doUnlockOrder()"/>
	  <!--
	  &nbsp;物流公司：
        <select name="set_logistic_code">
        	{{html_options options=$logisticList selected=st}}
        </select>
      -->
	  <input type="button" name="setLogistics" value="重新设置选中订单的物流公司" onclick="doSetLogistics()"/>
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$amount}}</b></div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/></td>
			    <td>ID</td>
			    <td>店铺</td>
			    <td>订单号</td>
				<td >订单商品</td>
				<td >收货人</td>
				<td>下单时间</td>
				<td>地区</td>
				<td>物流</td>
				<td>付款方式</td>
				<td>物流备注</td>
				<td>发票</td>
				<td>刷单</td>
				<td>已开票</td>
				<!--<td>操作</td>-->
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		{{if $data.goods.1 || $data.goods.0.goods_sn ne 'N9901002'}}
		<tr >
		    <td valign="top"><input type='checkbox' name="ids[]" value="{{$data.shop_order_id}}"></td>
		    <td valign="top">{{$data.shop_order_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
		    <td valign="top">
		      <a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/{{$data.shop_order_id}}', 'order{{$data.shop_order_id}}', 'height=600,width=800,toolbar=no,scrollbars=yes')">{{$data.external_order_sn}}</a>{{if $data.repeat}}<font color="green">*</font>{{/if}}
		      <font color="green">{{$data.repeat_order_sn}}</font>
		      {{if $data.lock_admin_name}}<br><font color="red">{{$data.lock_admin_name}}</font>{{/if}}
		    </td>
			<td valign="top">
			{{if $data.goods}}
			  {{foreach from=$data.goods item=goods}}
			  <a title="{{$goods.shop_goods_name}}">{{$goods.goods_sn}}</a>*{{$goods.number}}<br>
			  {{/foreach}}
			{{/if}}
			</td>
			<td valign="top">{{$data.addr_consignee}}</td>
			<td valign="top">{{$data.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">{{$data.addr_province}}{{$data.addr_city}}{{$data.addr_area}}</td>
			<td valign="top">
			  <!--{{if $data.logistic_code}}<b><font color="{{$data.logistic_code_color}}">{{$data.logistic_code}}</font><b/>{{/if}}-->
			  <select name="order_logistic_code[{{$data.shop_order_id}}]">
			    <option value=""></option>
			    {{if $data.is_cod}}
			    {{assign var="currentLogisticList" value=$logisticListCod}}
			    {{else}}
			    {{assign var="currentLogisticList" value=$logisticList}}
			    {{/if}}
			    {{foreach from=$currentLogisticList key=logistic_code item=logistic_name}}
			      {{if $logistic_code ne 'self' && $logistic_code ne 'externalself' || ($logistic_code eq 'externalself' && $data.is_cod)}}
			      <option value="{{$logistic_code}}" {{if $data.logisticPolicy.$logistic_code}}style="color:purple;"{{/if}} {{if ($data.logistic_code eq '' && $data.shop_id ne 9 && $data.logisticPolicy.$logistic_code eq '1') || ($data.logistic_code eq $logistic_code)}}selected{{/if}}>{{if $data.logisticPolicy.$logistic_code}}({{$data.logisticPolicy.$logistic_code}}){{/if}}{{$logistic_name}}</option>
			      {{/if}}
			    {{/foreach}}
			  </select>
			</td>
			<td valign="top">{{if $data.is_cod}}<b>货到付款</b>{{else}}非货到付款{{/if}}</td>
			<td valign="top"><b>{{$data.admin_memo}}</b></td>
			<td valign="top">{{if $data.invoice_title}}{{if $data.done_invoice eq 1}}{{$data.invoice_title}}{{else}}<b>{{$data.invoice_title}}</b>{{/if}}{{/if}}</td>
			<td valign="top">
			  {{if $data.is_fake eq 1}}未销账
			  {{elseif $data.is_fake eq 2}}已销账
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.done_invoice eq 1}}是
			  {{else}}否
			  {{/if}}
			</td>
			<!--
			<td valign="top">
			  <a href="javascript:;void(0)" onclick="importToFormalOrder({{$data.shop_order_id}})">导入官网处理</a>
			</td>
			-->
		  </tr>
		  {{/if}}
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>

<script type="text/javascript">
function doSearch()
{
    document.getElementById('searchForm').target='';
    document.getElementById('searchForm').method='get';
    document.getElementById('searchForm').action='{{url}}';
    document.getElementById('searchForm').submit();
}
function doExport()
{
    document.getElementById('searchForm').target='';
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='/admin/shop/export';
    document.getElementById('searchForm').submit();
}

function doPrintSum()
{
    document.getElementById('myForm').method='post';
    document.getElementById('myForm').target='_blank';
    document.getElementById('myForm').action='/admin/shop/print-sum';
    document.getElementById('myForm').submit();
}

function doSendGoodsReport()
{
    document.getElementById('myForm').method='post';
    document.getElementById('myForm').target='_blank';
    document.getElementById('myForm').action='/admin/shop/send-goods-report';
    document.getElementById('myForm').submit();
}

function doPrintSales()
{
    document.getElementById('print_shop_id').value =  document.getElementById('shop_id').value;
    document.getElementById('myForm').target='_blank';
    document.getElementById('myForm').action='/admin/shop/print-sales';
    document.getElementById('myForm').submit();
}

function doPrintLogistics()
{
    document.getElementById('myForm').target='_blank';
    document.getElementById('myForm').action='/admin/shop/print-logistics';
    document.getElementById('myForm').submit();
}

function doCheck2()
{
    if (confirm('该操执行后将不能再打印运输单和销售单，确认执行吗？')) {
        document.getElementById('myForm').target='';
        document.getElementById('todo').value = 'status2';
        document.getElementById('myForm').action='{{url}}';
        document.getElementById('myForm').submit();
    }
}

function doFill()
{
    var checkbox = document.myForm.getElements('input[type=checkbox]');
    var ids = '';
    for ( i = 1; i < checkbox.length; i++ ) {
        if (checkbox[i].checked) {
            ids += checkbox[i].value + ',';
        }
    }
    if (ids == '')  return false;
    ids = ids.substring(0, ids.length - 1);
    openDiv('/admin/shop/order-print-input/shop_id/{{$param.shop_id}}/direct/1/ids/' + ids,'ajax','订单物流单填充',560,300);
}

function doLockOrder()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'lock';
    document.getElementById('myForm').action='{{url}}';
    document.getElementById('myForm').submit();
}

function doUnlockOrder()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'unlock';
    document.getElementById('myForm').action='{{url}}';
    document.getElementById('myForm').submit();
}

function doOtherlogistics()
{
    if (confirm('该操作执行后将由第3方物流发货，不能再打印运输单和销售单，确认执行吗？')) {
        document.getElementById('myForm').target='';
        document.getElementById('print_shop_id').value = document.getElementById('shop_id').value;
        document.getElementById('todo').value = 'other-logistics';
        document.getElementById('myForm').action='{{url}}';
        document.getElementById('myForm').submit();
    }
}

function doOtherlogisticsSend()
{
    if (confirm('该操作执行后将由第3方物流直接发货，确认执行吗？')) {
        document.getElementById('myForm').target='';
        document.getElementById('todo').value = 'other-logistics-send';
        document.getElementById('myForm').action='{{url}}';
        document.getElementById('myForm').submit();
    }
}

function doSetLogistics()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'logistics';
    document.getElementById('myForm').action='{{url}}';
    document.getElementById('myForm').submit();
}

function doExportInvoice()
{
    if (confirm('该操执行后将把状态设为已开票，确认执行吗？')) {
        document.getElementById('myForm').target='';
        document.getElementById('myForm').method='post';
        document.getElementById('myForm').action='/admin/shop/export-invoice';
        document.getElementById('myForm').submit();
    }
}

function importToFormalOrder(shopOrderID)
{
    if (confirm('确认要导入官网订单走正常流程吗？')) {
        document.getElementById('searchForm').target='';
        document.getElementById('searchForm').method = 'post';
        document.getElementById('toFormalOrderID').value = shopOrderID;
        document.getElementById('searchForm').action='{{url}}';
        document.getElementById('searchForm').submit();
    }
}

function doSend1()
{
    var checkbox = document.myForm.getElements('input[type=checkbox]');
    var ids = '';
    for ( i = 1; i < checkbox.length; i++ ) {
        if (checkbox[i].checked) {
            ids += checkbox[i].value + ',';
        }
    }
    if (ids == '')  return false;
    ids = ids.substring(0, ids.length - 1);
    openDiv('/admin/shop/order-send-input/shop_id/{{$param.shop_id}}/direct/1/ids/' + ids,'ajax','订单发货',560,300);
}

function checkprovinceall(current)
{
    var province = document.getElementsByName('province');
    for ( i = 0; i < province.length; i++ ) {
        province[i].checked = current.checked;
    }
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
	run.attachURL("/admin/shop/order-admin-memo/id/" + id + "/view/1", true);
}

</script>