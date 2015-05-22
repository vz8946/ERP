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
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">订单结束日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
  当前店铺：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne 'jiankang' && $data.shop_type ne 'distribution' && $data.shop_type ne 'credit'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
  业务状态：
	<select name="status_business">
	    <option value="">请选择...</option>
	    <option value="0" {{if $param.status_business eq '0'}}selected{{/if}}>未审核</option>
		<option value="1" {{if $param.status_business eq '1'}}selected{{/if}}>审核通过</option>
		<option value="2" {{if $param.status_business eq '2'}}selected{{/if}}>已打印</option>
		<option value="4" {{if $param.status_business eq '4'}}selected{{/if}}>发货中</option>
		<option value="9" {{if $param.status_business eq '9'}}selected{{/if}}>审核不通过</option>
	</select>
  <br><br>
  第3方物流发货：
	<select name="other_logistics">
		<option value="">请选择...</option>
		<option value="1" {{if $param.other_logistics eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.other_logistics eq '0'}}selected{{/if}}>否</option>
	</select>
	</select>
  是否同步：
	<select name="sync">
		<option value="">请选择...</option>
		<option value="1" {{if $param.sync eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.sync eq '0'}}selected{{/if}}>否</option>
	</select>
  是否刷单：
	<select name="is_fake">
		<option value="">请选择...</option>
		<option value="1" {{if $param.is_fake eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.is_fake eq '0'}}selected{{/if}}>否</option>
	</select>
  已开票：
    <select name="done_invoice">
		<option value="">请选择...</option>
		<option value="1" {{if $param.done_invoice eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.done_invoice eq '0'}}selected{{/if}}>否</option>
	</select>
  备注：
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
  开票信息：
    <select name="invoice">
        <option value="">请选择...</option>
        <option value="0" {{if $param.invoice eq '0'}}selected{{/if}}>不开发票</option>
        <option value="3" {{if $param.invoice eq '3'}}selected{{/if}}>需开发票</option>
    </select>
  <br><br>
  订单号：<input type="text" name="external_order_sn" size="16" maxLength="50" value="{{$param.external_order_sn}}">
  收货人：<input type="text" name="addr_consignee" size="6" maxLength="60" value="{{$param.addr_consignee}}">
  手机：<input type="text" name="addr_mobile" size="10" maxLength="20" value="{{$param.addr_mobile}}">
  物流单号：<input type="text" name="logistic_no" size="10" maxLength="20" value="{{$param.logistic_no}}">
  商品编码：<input type="text" name="goods_sn" size="6" maxLength="10" value="{{$param.goods_sn}}" onchange="if (document.getElementById('goods_number').value == '') document.getElementById('goods_number').value = '1'">
  商品数量：<input type="text" name="goods_number" id="goods_number" size="1" maxLength="5" value="{{$param.goods_number}}">
  <input type="submit" name="dosearch" value="搜索"/>
  <input type="button" value="下载指定订单" onclick="downloadOrder()">
  <br>
  订单状态：
  <input type="checkbox" name="status[]" value="1" {{if $param.status.1}}checked{{/if}}>待收款
  <input type="checkbox" name="status[]" value="2" {{if $param.status.2}}checked{{/if}}>待发货
  <input type="checkbox" name="status[]" value="3" {{if $param.status.3}}checked{{/if}}>待确认收货
  <input type="checkbox" name="status[]" value="10" {{if $param.status.10}}checked{{/if}}>已完成
  <input type="checkbox" name="status[]" value="11" {{if $param.status.11}}checked{{/if}}>已取消
  <input type="checkbox" name="status[]" value="12" {{if $param.status.12}}checked{{/if}}>其它
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	{{if $auth.group_id eq 14 or $auth.group_id eq 1 }}
	  <input type="submit" name="export" value="导出订单" onclick="doExport()"/>
	{{/if}}
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$amount}}</b></div>
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
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.shop_order_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
		    <td valign="top"><a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/{{$data.shop_order_id}}', 'order{{$data.shop_order_id}}', 'height=600,width=800,toolbar=no,scrollbars=yes')">{{$data.external_order_sn}}</a></td>
			<td valign="top">{{$data.amount}}</td>
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
			<td valign="top">
			  {{if $data.status eq 1}}待收款
			  {{elseif $data.status eq 2}}待发货
			  {{elseif $data.status eq 3}}待确认收货
			  {{elseif $data.status eq 10}}已完成
			  {{elseif $data.status eq 11}}已取消
			  {{elseif $data.status eq 12}}其它
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.status_business eq 0}}未审核
			  {{elseif $data.status_business eq 1}}审核通过
			  {{elseif $data.status_business eq 2}}已打印
			  {{elseif $data.status_business eq 9}}审核不通过
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.done_invoice eq 1}}是
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.sync eq 1}}是
			  {{else}}否
			  {{/if}}
			</td>
			<td valign="top">
			  {{if $data.is_fake eq 1}}未销账
			  {{elseif $data.is_fake eq 2}}已销账
			  {{else}}否
			  {{/if}}
			</td>
			<td>
			  {{if $data.addr_province_id eq 0 || $data.addr_city_id eq 0 || $data.addr_area_id eq 0}}失败
			  {{else}}成功
			  {{/if}}
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