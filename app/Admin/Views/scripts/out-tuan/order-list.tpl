<style type="text/css">
.mytable{ width:100%; border-collapse:collapse; border:1px solid #ccc;}
.mytable td{ padding:5px;}
</style>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">外部团购订单查询</div>
<form name="searchForm" id="searchForm" action="/admin/out-tuan/order-list">
<div class="search">
  <table border="0">
    <tr>
	  <td>订单导入开始日期：</td><td><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></td>
	  <td>订单导入结束日期：</td><td><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></td>
	  <td>物流公司：</td><td><select name="logistics_com">
            <option value="">请选择</option>
	{{foreach from=$logisticList item=logistic}}
            <option value="{{$logistic.logistic_code}}" {{if $param.logistics_com eq $logistic.logistic_code}}selected{{/if}}>{{$logistic.name}}</option>
    {{/foreach}}
          </select></td>
	</tr>
  </table>
  <br />
  <span class="clear"></span>
  商品名称：<input type="text" name="goods_name_like" value="{{$param.goods_name_like}}" />&nbsp;&nbsp;
  批次：<input type="text" name="batch" id="batch" value="{{$param.batch}}" />&nbsp;&nbsp;
  订单号：<input type="text" name="order_sn" value="{{$param.order_sn}}" /><br />
  收件人：<input type="text" name="name" value="{{$param.name}}" />&nbsp;&nbsp;
  手机：<input type="text" name="mobile" value="{{$param.mobile}}" />&nbsp;&nbsp;
  快递单号：<input type="text" name="logistics_no" value="{{$param.logistics_no}}" /><br />
  
  所属网站：<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  物流状态：<select name="logistics">
  	<option value="">请选择</option>
    <option {{if $param.logistics eq off}}selected{{/if}} value="off">未发货</option>
    <option {{if $param.logistics eq on}}selected{{/if}} value="on">已发货</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  快递单打印：<select name="print">
  	<option value="">请选择</option>
    <option {{if $param.print eq off}}selected{{/if}} value="off">未打印</option>
    <option {{if $param.print eq on}}selected{{/if}} value="on">已打印</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  订单状态：<select name="status">
  	<option value="">请选择</option>
    <option {{if $param.status eq on}}selected{{/if}} value="on">正常</option>
    <option {{if $param.status eq off}}selected{{/if}} value="off">已删除</option>
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
<br />

  结款状态：<select name="isclear"><option value="">请选择</option><option {{if $param.isclear eq off}}selected{{/if}} value="off">未</option><option {{if $param.isclear eq on}}selected{{/if}} value="on">已</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  退单状态：<select name="isback"><option value="">请选择</option><option {{if $param.isback eq off}}selected{{/if}} value="off">未</option><option {{if $param.isback eq on}}selected{{/if}} value="on">已</option><option {{if $param.isback eq ing}}selected{{/if}} value="ing">待退款</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  刷单状态：<select name="ischeat"><option value="">请选择</option><option {{if $param.ischeat eq off}}selected{{/if}} value="off">否</option><option {{if $param.ischeat eq on}}selected{{/if}} value="on" {{if $param.ischeatclear}}selected{{/if}}>是</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  刷单销账：<select name="ischeatclear"><option value="">请选择</option><option {{if $param.ischeatclear eq 1}}selected{{/if}} value="1">未销</option><option {{if $param.ischeatclear eq 2}}selected{{/if}} value="2">已销</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  导入人：<select name="add_user"><option value="">请选择</option>
  {{foreach from=$add_user item=adduser}}
  <option value="{{$adduser.admin_id}}" {{if $param.add_user eq $adduser.admin_id}}selected{{/if}}>{{$adduser.real_name}}</option>
  {{/foreach}}
  </select>&nbsp;&nbsp;<input type="submit" name="dosearch" value="查询"/>
</div>
<div class="content">
<table class="mytable"><tr><td align="left" width="200">&nbsp;</td><td style=" text-align:right;">&nbsp;商品售价总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalPrice}}</sapn>&nbsp;&nbsp;&nbsp;商品供货价总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalSupplyPrice}}</sapn>&nbsp;&nbsp;&nbsp;费率总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalFee}}</sapn></td></tr></table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
            <td>网站</td>
            <td>订单号</td>
            <td>商品名称</td>
			<td>期数</td>
            <td>件数</td>
			<td>金额</td>
            <td>收件人</td>
            <td>手机</td>
            <td>物流</td>
            <td>批次</td>
            <td>快递单号</td>
			<td width="60">状态</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
    	<td>{{$data.id}}</td>
        <td><strong>{{$data.shop_name}}</strong></td>
        <td>{{$data.order_sn}}</td>
        <td><span style=" width:60px; overflow:hidden; display:inline-block;" title="{{$data.goods_name}}">{{$data.goods_name}}</span></td>
		<td><span style=" font-size:12px; width:40px; overflow:hidden; display:inline-block;" title="{{$data.term}}">{{$data.term}}</span></td>
        <td>{{$data.amount}}</td>
		<td><span style="width:80px; font-size:12px; overflow:hidden;"><font color="green">售：</font>{{$data.order_price}}<br /><font color="green">供：</font>{{$data.supply_price}}<br /><font color="green">费：</font>{{$data.fee}}</span></td>
        <td><span style=" text-decoration:underline;">{{$data.name}}</span></td>
        <td><span style=" font-size:10px; display:inline-block; width:80px; overflow:hidden" title="{{$data.mobile}}">{{$data.mobile}}</span></td>
        <td>{{if $data.logistics eq 0}}<font color="red">未发</font>{{else}}已发{{/if}}</td>
        <td><span style=" font-size:10px;" ondblclick="javascript:document.getElementById('batch').value=this.innerHTML;">{{$data.batch}}</span></td>
        <td><span style=" width:90px; overflow:hidden; display:inline-block; font-size:10px;">{{$data.logistics_no}}</span></td>
		<td>结算：{{if $data.isclear eq 0}}<font color="red">未</font>{{else}}<font color="green">已</font>{{/if}}<br />退单：{{if $data.isback eq 0}}<font color="green">未</font>{{elseif  $data.isback eq 1}}<font color="red">已</font>{{elseif  $data.isback eq 2}}待{{/if}}<br />刷单：{{if $data.ischeat eq 0}}<font color="green">否</font>{{else}}<font color="red">是</font>{{/if}}<br />{{if $data.ischeat>0}}销账：{{if $data.ischeat eq 1}}<font color="red">未销</font>{{else if $data.ischeat eq 2}}<font color="green">已销</font>{{/if}}{{/if}}</td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>
</form>
<div class="page_nav">{{$pageNav}}</div>