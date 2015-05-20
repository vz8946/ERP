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
  <select name="shop_id" id="shop_id">
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
		<option value="3" {{if $param.status eq '3'}}selected{{/if}}>待确认收货</option>
		<option value="10" {{if $param.status eq '10'}}selected{{/if}}>已完成</option>
		<option value="11" {{if $param.status eq '11'}}selected{{/if}}>取消</option>
	</select>
  业务状态：
	<select name="status_business">
		<option value="0" {{if $param.status_business eq '0'}}selected{{/if}}>未审核</option>
		<option value="1" {{if $param.status_business eq '1'}}selected{{/if}}>已审核</option>
		<option value="2" {{if $param.status_business eq '2'}}selected{{/if}}>已打印</option>
		<option value="9" {{if $param.status_business eq '9'}}selected{{/if}}>审核不通过</option>
	</select>
    限价:<input type="checkbox" name="audit_status" value="1" {{if $param.audit_status eq '1'}}checked='true'{{/if}}/>
  <br><br>
  是否刷单：
	<select name="is_fake">
		<option value="1" {{if $param.is_fake eq '1'}}selected{{/if}}>是</option>
		<option value="0" {{if $param.is_fake eq '0'}}selected{{/if}}>否</option>
	</select>
  地址匹配：
	<select name="check_address">
		<option value="">请选择...</option>
		<option value="1" {{if $param.check_address eq '1'}}selected{{/if}}>匹配成功</option>
		<option value="2" {{if $param.check_address eq '2'}}selected{{/if}}>匹配失败</option>
	</select>
  物流公司：
    <select name="logistic_code">
        <option value="">请选择...</option>
        {{html_options options=$logisticList selected=$param.logistic_code}}
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
  <br>
  订单号：<input type="text" name="external_order_sn" size="16" maxLength="20" value="{{$param.external_order_sn}}">
  收货人：<input type="text" name="addr_consignee" size="6" maxLength="30" value="{{$param.addr_consignee}}">
  手机：<input type="text" name="addr_mobile" size="10" maxLength="20" value="{{$param.addr_mobile}}">
  备注：<input type="text" name="memo" size="10" value="{{$param.memo}}">
  商品编码：<input type="text" name="goods_sn" size="6" maxLength="10" value="{{$param.goods_sn}}" onchange="if (document.getElementById('goods_number').value == '') document.getElementById('goods_number').value = '1'">
  商品数量：<input type="text" name="goods_number" id="goods_number" size="1" maxLength="5" value="{{$param.goods_number}}">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  <input type="button" name="dodownload" value="下载当天订单" onclick="downloadDayOrder()"/>
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
	<div class="title">店铺订单列表</div>
	<div class="content">
	<div style="float:left">
	  {{if $param.status_business eq '0'}}
	  <input type="submit" name="check" value="审核通过选中订单" onclick="return doCheck1('')"/>
	  <input type="submit" name="check" value="审核通过选中订单并导入官网处理" onclick="return doCheck1('import')"/>
	  <input type="submit" name="check" value="审核不通过选中订单" onclick="return doCheck9()"/>
	  <input type="submit" name="export" value="导出需开票订单 > 已开票" onclick="return doExportInvoice()"/>
	  {{elseif $param.status_business eq '1'}}
	  <input type="submit" name="check" value="反审核选中订单" onclick="return doCheck0()"/>
	  <input type="submit" name="check" value="选中订单导入官网处理" onclick="return doCheck1('import')"/>
	  {{elseif $param.status_business eq '2'}}
	  <input type="submit" name="check" value="反审核选中订单" onclick="return doCheck0()"/>
	  {{/if}}
	  {{if $param.status_business eq '2'}}
	  <!--
	  &nbsp;物流公司：
        <select name="set_logistic_code">
        	{{html_options options=$logisticList selected=yt}}
        </select>
      -->
	  <input type="submit" name="setLogistics" value="重新设置选中订单的物流公司" onclick="return doSetLogistics()"/>
	  {{/if}}
	  {{if $param.status_business eq '9'}}
	  <!--
	    &nbsp;物流公司：
        <select name="set_logistic_code">
        	{{html_options options=$logisticList selected=yt}}
        </select>
      -->
	  <input type="submit" name="setLogistics" value="设置选中订单的物流公司并审核通过" onclick="return doSetLogisticsAndCheck()"/>
	  <input type="submit" name="check" value="审核通过选中订单并导入官网处理" onclick="return doCheck1('import')"/>
	  {{/if}}
	  <br>
	  {{if $param.status_business eq '0'}}
	  <!--
	  &nbsp;物流公司：
        <select name="set_logistic_code">
          {{html_options options=$logisticList selected=yt}}
        </select>
      -->
      <input type="submit" name="setLogistics" value="设置选中订单的物流公司" onclick="return doSetLogistics()"/>
	  <input type="submit" name="setLogistics" value="设置选中订单的物流公司并审核通过" onclick="return doSetLogisticsAndCheck()"/>
	  {{/if}}
	  <input type="button" name="export" value="导出订单" onclick="doExport()"/>
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$amount}}</b></div>
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
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top"><input type='checkbox' name="ids[]" value="{{$data.shop_order_id}}"></td>
		    <td valign="top">{{$data.shop_order_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
		    <td valign="top">
		      <a href="javascript:;void(0)" onclick="window.open('/admin/shop/order-detail/id/{{$data.shop_order_id}}', 'order{{$data.shop_order_id}}', 'height=600,width=800,toolbar=no,scrollbars=yes')">{{$data.external_order_sn}}</a>
		      {{if $data.invoice_title}}<br>{{$data.invoice_title}}{{/if}}
		    </td>
			<td valign="top" {{if $data.audit_status eq '1'}}style="color:#ff0000"{{/if}}>{{$data.amount}}</td>
			<td valign="top">
			{{if $data.goods}}
			  {{foreach from=$data.goods item=goods}}
			  <a title="{{$goods.shop_goods_name}}">{{if $replenishmentInfo[$data.shop_order_id][$goods.goods_sn]}}<font color="{{$replenishmentInfo[$data.shop_order_id][$goods.goods_sn]}}">{{$goods.goods_sn}}</font>{{else}}{{$goods.goods_sn}}{{/if}}</a>*{{$goods.number}}<br>
			  {{/foreach}}
			{{/if}}
			</td>
			<td valign="top">{{$data.addr_consignee}}</td>
			<td valign="top">{{$data.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
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
			<td valign="top"><textarea rows=3 style="width:120px" onblur="updateMemo({{$data.shop_order_id}}, this.value)">{{$data.memo}}</textarea></td>
			<td valign="top">{{if $data.admin_memo}}<a href="javascript:fGo()" onclick="showAdminMemoWin({{$data.shop_order_id}})"><b>查看</b></a>{{else}}<a href="javascript:fGo()" onclick="showAdminMemoWin({{$data.shop_order_id}})">添加</a>{{/if}}</td>
			<td valign="top">
			  {{if $data.status_business eq 0}}未审核
			  {{elseif $data.status_business eq 1}}审核通过
			  {{elseif $data.status_business eq 2}}已打印
			  {{elseif $data.status_business eq 9}}审核不通过
			  {{/if}}
			</td>
			<td valign="top" id="is_fake_{{$data.shop_order_id}}">
			  <!--
			  {{if $data.is_fake eq '0' or $data.is_fake eq '1'}}
			  <input type="checkbox" name="is_fake_{{$data.shop_order_id}}" value="1" {{if $data.is_fake}}checked{{/if}} onchange="changeFake({{$data.shop_order_id}}, this.checked)">
			  {{else}}
			  已销账
			  {{/if}}
			  -->
			  {{if $data.is_fake eq '0'}}
			  否
			  {{elseif $data.is_fake eq '1'}}
			  未销账
			  {{else}}
			  已销账
			  {{/if}}
			</td>
			<td>
			  {{if $param.status_business eq '0' || $param.status_business eq '9'}}
			  <a href="javascript:fGo()" onclick="showGoodsWin({{$data.shop_order_id}})">商品</a>
			  <a href="javascript:fGo()" onclick="showInvoiceWin({{$data.shop_order_id}})">发票</a>
			  {{/if}}
			  {{if $param.status_business eq '0' || $param.status_business eq '1'}}
			  <a href="javascript:fGo()" onclick="showAddressWin({{$data.shop_order_id}})">
			    {{if $data.addr_province_id eq 0 || $data.addr_city_id eq 0 || $data.addr_area_id eq 0}}<font color="red">地址</a>
			    {{else}}地址
			    {{/if}}
			  </a>
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
	run.attachURL("/admin/shop/order-address/id/" + id + '/url/' + base64encode('{{url}}'), true);
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
	run.attachURL("/admin/shop/order-admin-memo/id/" + id + '/url/' + base64encode('{{url}}'), true);
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
	run.attachURL("/admin/shop/order-goods/id/" + id + '/url/' + base64encode('{{url}}'), true);
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
	run.attachURL("/admin/shop/order-invoice/id/" + id + '/url/' + base64encode('{{url}}'), true);
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
    document.getElementById('myForm').action='{{url}}';
    return true;
}

function doCheck0(todo)
{
    if (confirm('确定要反审核选中订单吗？')) {
        document.getElementById('todo').value = 'status0';
        document.getElementById('myForm').action='{{url}}';
        return true;
    }
    return false;
}

function doCheck9()
{
    document.getElementById('todo').value = 'status9';
    document.getElementById('myForm').action='{{url}}';
    return true;
}

function doSetLogistics()
{
    document.getElementById('myForm').target='';
    document.getElementById('todo').value = 'logistics';
    document.getElementById('myForm').action='{{url}}';
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
    document.getElementById('myForm').action='{{url}}';
    return true;
}

function downloadDayOrder()
{
    if (document.getElementById('shop_id').value == '') {
        alert('请先选择店铺!');
        return false;
    }
    
    window.open('/admin/shop/sync/action_name/order/fromdate/{{$currentDate}}/todate/{{$currentDate}}/id/' + document.getElementById('shop_id').value);
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