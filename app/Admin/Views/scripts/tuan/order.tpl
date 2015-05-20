<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">团购订单查询</div>
<div class="search">
<form id="searchForm" method="get">
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td width="70%">
      <span style="float:left;line-height:18px;">开始时间：</span>
      <span style="float:left;width:150px;line-height:18px;">
        <input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
      </span>
      <span style="float:left;line-height:18px;">结束时间：</span>
      <span style="float:left;width:240px;line-height:18px;">
        <input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
        <input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/>
      </span>
    </td>
    <td>
      订单号：
      <input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}">
    </td>
  </tr>
  <tr>
    <td>
      团购标题：
      <input type="text" name="title" size="20" maxLength="50" value="{{$param.title}}">
      收货人名字：<input type="text" name="addr_consignee" size="10" maxLength="50" value="{{$param.addr_consignee}}">
	  用户名：<input type="text" name="user_name" size="10" maxLength="50" value="{{$param.user_name}}">
	  订单状态:
	  <select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>有效单</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>取消单</option>
		<option value="2" {{if $param.status eq '2'}}selected{{/if}}>无效单</option>
	  </select>
    </td>
    <td>
      <input type="button" name="dosearch" id="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
    </td>
  </tr>
</table>
   </form>
</div>
<div class="content">
<div style="padding:0 10px">
<div style="float:right;"><b>订单总金额：￥{{$totalPriceOrder}}</b></div>
</div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>订单号</td>
            <td>下单时间</td>
			<td>团购标题</td>
			<td>团购商品</td>
			<td>商品数量</td>
			<td>收货人</td>
			<td>金额</td>
            <td>订单状态</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$datas item=data}}
        <tr id="ajax_list{{$data.order_batch_goods_id}}">
            <td><a href ="/admin/order/info/batch_sn/{{$data.batch_sn}}">{{$data.batch_sn}}</a></td>
			<td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td><a href="/admin/tuan/edit/id/{{$data.offers_id}}">{{$data.title}}</a></td>
			<td><a href="/admin/tuan/edit-goods/id/{{$data.tuan_goods_id}}">{{$data.goods_name}}</a></td>
			<td>{{$data.number}}</td>
            <td>{{$data.addr_consignee}}</td>
			<td>{{$data.amount}}</td>
            <td>{{if $data.status eq 0}}
				有效单
			    {{elseif $data.status eq 1}}
				取消单
			    {{elseif $data.status eq 2}}
				无效单
			    {{/if }}
			</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
	    <div class="page_nav">{{$pageNav}}</div>
</div>