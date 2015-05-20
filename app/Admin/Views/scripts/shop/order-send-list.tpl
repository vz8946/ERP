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
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">订单结束日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
  当前店铺：
  <select name="shop_id" id="shop_id" onchange="document.getElementById('dosearch').click();">
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
		<option value="2" {{if $param.status_business eq '2'}}selected{{/if}}>已打印</option>
	</select>
  <br><br>
  付款方式：
	<select name="is_cod">
		<option value="">请选择...</option>
		<option value="1" {{if $param.is_cod eq '1'}}selected{{/if}}>货到付款</option>
		<option value="0" {{if $param.is_cod eq '0'}}selected{{/if}}>非货到付款</option>
	</select>
  已开票：
    <select name="done_invoice">
		<option value="">请选择...</option>
		<option value="1" {{if $param.done_invoice eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.done_invoice eq '0'}}selected{{/if}}>否</option>
	</select>
  第3方物流发货：
	<select name="other_logistics">
		<option value="">请选择...</option>
		<option value="1" {{if $param.other_logistics eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.other_logistics eq '0'}}selected{{/if}}>否</option>
	</select>
  物流公司：
    <select name="logistic_code">
      <option value="">请选择...</option>
      {{html_options options=$logisticList selected=$param.logistic_code}}
    </select>
  订单号：<input type="text" name="external_order_sn" size="15" maxLength="50" value="{{$param.external_order_sn}}">
  收货人：<input type="text" name="addr_consignee" size="10" maxLength="60" value="{{$param.addr_consignee}}">
  <input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm" method="post" action="{{url}}">
    <input type="hidden" name="todo" id="todo">
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	  <input type="button" name="send1" value="发货 选中的订单" onclick="doSend1()"/>
	  <input type="button" name="send2" value="发货 按文件导入" onclick="doSend2()"/>
	  <input type="button" name="back-print" value="返回打印 > 审核通过" onclick="backPrint()"/>
	  <!--<input type="button" name="export" value="导出订单" onclick="doExport()"/>-->
	  <br>
      <input type="button" name="lockOrder" value="锁定选中订单" onclick="doLockOrder()"/>
	  <input type="button" name="unlockOrder" value="解锁选中订单" onclick="doUnlockOrder()"/>
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
				<td>运费</td>
				<td>物流</td>
				<td>运单号</td>
				<td>付款方式</td>
				<td>已开票</td>
				<td>刷单</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top"><input type='checkbox' name="ids[]" value="{{$data.shop_order_id}}"></td>
		    <td valign="top">{{$data.shop_order_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
		    <td valign="top">
		      <a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/{{$data.shop_order_id}}', 'order{{$data.shop_order_id}}', 'height=600,width=800,toolbar=no,scrollbars=yes')">{{$data.external_order_sn}}</a>{{if $data.repeat}}<font color="green">*</font>{{/if}}
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
			<td valign="top">{{$data.freight}}</td>
			<td valign="top">{{if $data.logistic_code}}<b><font color="{{$data.logistic_code_color}}">{{$data.logistic_code}}</font><b/>{{/if}}</td>
			<td valign="top">{{$data.logistic_no}}</td>
			<td valign="top">{{if $data.is_cod}}货到付款{{else}}非货到付款{{/if}}</td>
			<td valign="top">
			  {{if $data.done_invoice eq 1}}是
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.is_fake eq 1}}未销账
			  {{elseif $data.is_fake eq 2}}已销账
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">
			  <a href="javascript:void(0)" onclick="openDiv('/admin/shop/order-send-input/id/{{$data.shop_order_id}}/shop_id/{{$param.shop_id}}','ajax','订单发货',560,300);">发货</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>

<script type="text/javascript">
function doExport()
{
    document.getElementById('searchForm').method='post';
    document.getElementById('searchForm').action='/admin/shop/export';
    document.getElementById('searchForm').submit();
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
    openDiv('/admin/shop/order-send-input/shop_id/{{$param.shop_id}}/ids/' + ids,'ajax','订单发货',560,300);
}

function doSend2()
{
    openDiv('/admin/shop/order-send-input/shop_id/{{$param.shop_id}}','ajax','订单发货',560,300);
}

function backPrint()
{
    if (confirm('确认要返回到打印队列吗？')) {
        document.getElementById('todo').value = 'back';
        document.getElementById('myForm').action='{{url}}';
        document.getElementById('myForm').submit();
    }
}

function doLockOrder()
{
    document.getElementById('todo').value = 'lock';
    document.getElementById('myForm').action='{{url}}';
    document.getElementById('myForm').submit();
}

function doUnlockOrder()
{
    document.getElementById('todo').value = 'unlock';
    document.getElementById('myForm').action='{{url}}';
    document.getElementById('myForm').submit();
}


</script>