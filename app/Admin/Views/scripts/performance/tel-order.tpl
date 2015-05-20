<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm" method="get">
     <div>
        <span style="float:left;">下单开始日期：</span><span style="float:left;width:150px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
        <span style="float:left;margin-left:10px">下单结束日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></span>
        
        
<select id="operator_id" class="required" msg="请选择客服" name="operator_id">
<option value="">选择客服</option>
{{foreach from =$adminMessages key=key item =admin }} 
<option value="{{$admin.admin_id}}" label="{{$admin.real_name}}" {{if $param.operator_id eq  $admin.admin_id}} selected="selected"  {{/if}} >{{$admin.real_name}}</option>
{{/foreach}}
</select>
        
    </div>
<div style="clear:both; padding-top:5px">
    订单号：<input type="text" name="batch_sn" size="16" maxLength="50" value="{{$param.batch_sn}}">
     订单状态:
    <select name="status">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status eq '0'}}selected{{/if}}>有效单</option>
    <option value="1" {{if $param.status eq '1'}}selected{{/if}}>取消单</option>
    <option value="2" {{if $param.status eq '2'}}selected{{/if}}>无效单</option>
    </select>
     支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_pay eq '0'}}selected{{/if}}>未收款</option>
    <option value="1" {{if $param.status_pay eq '1'}}selected{{/if}}>未退款</option>
    <option value="2" {{if $param.status_pay eq '2'}}selected{{/if}}>已收款</option>
    <option value="3" {{if $param.status_pay eq '3'}}selected{{/if}}>部分收款</option>
    </select>
    配送状态:
    <select name="status_logistic">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_logistic eq '0'}}selected{{/if}}>未确认</option>
    <option value="1" {{if $param.status_logistic eq '1'}}selected{{/if}}>已确认[待收款]</option>
    <option value="2" {{if $param.status_logistic eq '2'}}selected{{/if}}>待发货</option>
    <option value="3" {{if $param.status_logistic eq '3'}}selected{{/if}}>已发货未签收</option>
    <option value="4" {{if $param.status_logistic eq '4'}}selected{{/if}}>客户已签收</option>
    </select>
    退换货状态:
    <select name="status_return">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_return eq '0'}}selected{{/if}}>正常单</option>
    <option value="1" {{if $param.status_return eq '1'}}selected{{/if}}>退货单</option>
    </select>
    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search param.page=1}}','ajax_search')"/>
   [<a href="{{url param.action=export-tel-order}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出查询结果</a>]
    </div>	
    </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">订单查询列表</div>
	<div class="content">
<div style="padding:0 5px">
	<div style="float:right;"><b>订单总金额：￥{{$totalPrice}}</b></div>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>订单号</td>
                <td>订单状态</td>
                <td>下单时间</td>
                <td>下单客服</td>
				<td>收货人</td>
				<td>金额</td>
                <td>运费</td>
				<td>支付方式</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=item}}
		<tr>
			<td valign="top">{{$item.order_id}}</td>
			<td valign="top">{{$item.batch_sn}}</td>
            <td valign="top">{{$item.status}}  {{$item.status_pay}} {{$item.status_logistic}}  {{$item.status_return}}</td>
            <td valign="top">{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
            <td valign="top">{{$item.real_name}}</td>
            <td valign="top">{{$item.addr_consignee}}</td>
			<td valign="top">{{$item.price_pay}}</td>
            <td valign="top">{{$item.price_logistic}}</td>
			<td valign="top">{{$item.pay_name}}</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>