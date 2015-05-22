<style type="text/css">
.mytable{ width:100%; border-collapse:collapse; border:1px solid #ccc;}
.mytable td{ padding:5px;}
</style>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">外部团购订单&nbsp;&nbsp;&nbsp;&nbsp;（<a href="/admin/out-tuan/order-add">添加外部团购订单</a>）</div>
<form name="searchForm" id="searchForm" action="/admin/out-tuan/order">
<div class="search">
  <table border="0">
    <tr>
	  <td>订单导入开始日期：</td><td><input type="text" name="fromdate" id="fromdate" size="22" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></td>
	  <td>订单导入结束日期：</td><td><input  type="text" name="todate" id="todate" size="22" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></td>
	  <td><input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value='' "/></td><td>
          </td>
          <td></td><td> </td>
	</tr>
  </table>
  物流公司：<select name="logistics_com">
            <option value="">请选择</option>
	{{foreach from=$logisticList item=logistic}}
            <option value="{{$logistic.logistic_code}}" {{if $param.logistics_com eq $logistic.logistic_code}}selected{{/if}}>{{$logistic.name}}</option>
    {{/foreach}}
          </select>&nbsp;&nbsp;
  期数：<input type="text" name="term" value="{{$param.term}}" />&nbsp;&nbsp; 
  期数不在：<input type="text" name="term_not_in" value="{{$param.term_not_in}}" />(格式：1,2,3)
  <br />
  <span class="clear"></span>
  商品名称：<input type="text" name="goods_name_like" value="{{$param.goods_name_like}}" />&nbsp;&nbsp;
  批次：<input type="text" name="batch" id="batch" value="{{$param.batch}}" />&nbsp;&nbsp;
  订单号：<input type="text" name="order_sn" id="order_sn" value="{{$param.order_sn}}" /><br />
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
  </select>&nbsp;&nbsp;<input type="submit" name="dosearch" value="查询"/><br /><input type="button" value="导出批次订单" onclick="exportBatchOrder()" />&nbsp;&nbsp;<input type="button" onclick="batchPrintSummary(this.form)" value="打印批次销售汇总单" />&nbsp;&nbsp;<input type="button" onclick="modifyOrderPriceEqZero()" value="按批次修正以前金额为0的订单" style="display:none;" /><!--&nbsp;&nbsp;&nbsp;
  <input type="button" value="更改批次结款状态" onclick="clearBatch()" />--><input type="button" value="按条件导出订单" onclick="exportOrder()" /><input type="button" value="修正shop_order_batch_goods.cat_id" onclick="modifyCatID()" style="display:none;" /><input type="button" value="同步刷单" onclick="tongbuShuadan()" style="display:none;" /><span style="display:none;">id:<input type="text" size="4" id="theid" />st:<input type="text" size="3" id="thest" /><input type="button" value="更改减库存状态" onclick="checkStock()" /></span>
</div>
<div class="content">
<table class="mytable"><tr><td align="left" width="200"><input style="display:none;" type="button" value="恢复批次"  onclick="resetOrder()" /><input type="button" style="display:none;" value="彻底删除批次订单（慎用）" onclick="deleteBatchOrder()" /><input type="button" style="display:none;" value="设置所有刷单为'已经销账'" onclick="setAllIscheatClear()" />{{if $param.status eq off}}&nbsp;&nbsp;&nbsp;<input type="button" onclick="deleteOrder('on')" value="恢复选中项" />{{else}}&nbsp;&nbsp;&nbsp;<input type="button" onclick="deleteOrder('off')" value="删除选中项" />{{/if}}<input type="button" value="删除单条记录" onclick="orderDelOne()" style="display:none" /></td><td style=" text-align:right;">&nbsp;商品售价总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalPrice}}</sapn>&nbsp;&nbsp;&nbsp;商品供货价总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalSupplyPrice}}</sapn>&nbsp;&nbsp;&nbsp;费率总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalFee}}</sapn>
&nbsp;&nbsp;&nbsp;商品销售数量：<sapn style="font-weight:bold;color:red;">{{$totalAmount}}</sapn>
</td></tr></table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
        	<td>选择</td>
            <td>ID</td>
            <td>网站</td>
            <td>订单号</td>
            <td>商品名称</td>
			<td>期数</td>
            <td>件数</td>
			<td>金额</td>
            <td>收件人</td>
            <!--<td>固定电话</td>-->
            <!--<td>手机</td>-->
            <td>收货地址</td>
			<td>订单时间</td>
            <!--<td>备注</td>-->
			<td>库存</td>
            <td>打印</td>
            <td>物流</td>
            <td>批次</td>
            <td>快递单号</td>
			<td width="60">状态</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
    	<td><input type="checkbox" name="xz[]" value="{{$data.id}}" /></td>
    	<td>{{$data.id}}</td>
        <td><strong>{{$data.shop_name}}</strong></td>
        <td>{{$data.order_sn}}</td>
        <td><span style=" width:60px; overflow:hidden; display:inline-block;" title="{{$data.goods_name}}">{{$data.goods_name}}</span></td>
		<td><span style=" font-size:10px; width:40px; overflow:hidden; display:inline-block;" title="{{$data.term}}">{{$data.term}}</span><span style="display:none;">{{$data.xls_id}}</span></td>
        <td>{{$data.amount}}</td>
		<td><span style="width:80px; font-size:10px; overflow:hidden;"><font color="green">售：</font>{{$data.order_price}}<br /><font color="green">供：</font>{{$data.supply_price}}<br /><font color="green">费：</font>{{$data.fee}}</span></td>
        <td><span style=" text-decoration:underline;">{{$data.name}}</span></td>
        <!--<td><span style=" font-size:10px; display:inline-block; width:100px; overflow:hidden" title="{{$data.phone}}">{{$data.phone}}</span></td>-->
        <!--<td><span style=" font-size:10px; display:inline-block; width:80px; overflow:hidden" title="{{$data.mobile}}">{{$data.mobile}}</span></td>-->
        <td><span style=" width:90px; overflow:hidden; display:inline-block" title="{{$data.addr}}">{{$data.addr|truncate:15:"..."}}</span></td>
		<td><span style=" width:65px; overflow:hidden; display:inline-block; font-size:10px;" title="订单时间：{{$data.ctime|date_format:"%Y-%m-%d %T"}}">{{if $data.order_time}}{{$data.order_time|date_format:"%Y-%m-%d %T"}}{{else}}{{$data.ctime|date_format:"%Y-%m-%d %T"}}{{/if}}</span></td>
        <!--<td><span style=" font-size:10px; display:inline-block; width:80px; overflow:hidden" title="{{$data.phone}}">{{$data.remark}}</span></td>-->
		<td>{{if $data.check_stock eq 1}}有{{else}}<span style="color:red;">无</span>{{/if}}</td>
        <td>{{if $data.print eq 0}}<font color="red">未打</font>{{else}}已打{{/if}}</td>
        <td>{{if $data.logistics eq 0}}<font color="red">未发</font>{{else}}已发{{/if}}</td>
        <td><span style=" font-size:10px;" ondblclick="javascript:document.getElementById('batch').value=this.innerHTML;">{{$data.batch}}</span></td>
        <td><span style=" width:90px; overflow:hidden; display:inline-block; font-size:10px;">{{$data.logistics_no}}</span></td>
		<td>结算：{{if $data.isclear eq 0}}<font color="red">未</font>{{else}}<font color="green">已</font>{{/if}}<br />退单：{{if $data.isback eq 0}}<font color="green">未</font>{{elseif  $data.isback eq 1}}<font color="red">已</font>{{elseif  $data.isback eq 2}}待{{/if}}<br />刷单：{{if $data.ischeat eq 0}}<font color="green">否</font>{{else}}<font color="red">是</font>{{/if}}<br />{{if $data.ischeat>0}}销账：{{if $data.ischeat eq 1}}<font color="red">未销</font>{{else if $data.ischeat eq 2}}<font color="green">已销</font>{{/if}}{{/if}}</td>
        <td width="40"><a href="javascript:fGo();" onclick="G('/admin/out-tuan/order-edit/id/{{$data.id}}')">修改</a></td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>
</form>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//只删除一条记录
function orderDelOne(){
	var order_sn = $('order_sn').value.trim();
	if(order_sn==''){alert('请输入订单号');return false;}
	new Request({
		url:'/admin/out-tuan/order-del-one/order_sn/'+order_sn,
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('删除成功');
				location.reload();
			}
			else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//批次导出xls
function exportBatchOrder(){
	var bat=$('batch').value.trim();
	if(bat==''){alert('请填写批次号');return;}
	G('/admin/out-tuan/export-batch-order/batch/'+bat);
}
//删除（只改变status，并不是真正的删除）
function deleteOrder(zt){
	var xz=document.getElementsByName('xz[]');
	var ids='';
	for(i=0;i<xz.length;i++){
		if(xz[i].checked){
			ids+=xz[i].value+',';
		}
	}
	if(ids==''){alert('请选择需要删除的订单');return;}
	var st=zt;if(st!='on'){st='off';}
	new Request({
		url:'/admin/out-tuan/select-delete/ids/'+ids+'/st/'+st,
		onSuccess:function(msg){
			if(msg=='ok'){
				if(zt=='on'){alert('恢复成功');}
				else{alert('删除成功');}
				location.reload();
			}
			else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
//重置订单
function resetOrder(){
	var bat=$('batch').value.trim();
	if(bat==''){alert('请填写批次号');return;}
	G('/admin/out-tuan/reset-batch-order/batch/'+bat);
}
//彻底删除批次订单
function deleteBatchOrder(){
	var bat=$('batch').value.trim();
	if(bat==''){alert('请填写批次号');return;}
	G('/admin/out-tuan/delete-batch-order/batch/'+bat);
}
//更改批次“结款”状态
/*function clearBatch(){
	var bat=$('batch').value.trim();
	if(bat==''){alert('请填写批次号');return;}
	G('/admin/out-tuan/clear-batch/batch/'+bat);
}*/

//打印批次销售汇总单
function batchPrintSummary(tf){
	var batch=$('batch').value.trim();
	tf.method='post';
	tf.target='_blank';
	tf.action='/admin/out-tuan/batch-print-summary/batch/'+batch;
	tf.submit();
}

//按批次修正以前为0金额的订单
function modifyOrderPriceEqZero(){
	var bat=$('batch').value.trim();
	if(bat==''){alert('请填写批次号');return;}
	new Request({
		url:'/admin/out-tuan/modify-order-price-eq-zero/batch/'+bat,
		onSuccess:function(msg){
			if(msg=='ok'){
				alert('操作成功');
				location.reload();
			}
			else{alert(msg);}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}

//按条件导出订单
function exportOrder(){
	var f=document.getElementById('searchForm');
	f.action='/admin/out-tuan/export-order';
	f.method='post';
	f.submit();
	f.action = "/admin/out-tuan/order";
}

//设置所有刷单为"已经销账"
function setAllIscheatClear(){
	if(confirm('确定吗？')){
		new Request({
			url:'/admin/out-tuan/set-all-ischeat-clear',
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('操作成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}

//修正shop_order_batch_goods.cat_id
function modifyCatID(){
	if(confirm('确定吗？')){
		new Request({
			url:'/admin/out-tuan/modify-catid',
			onSuccess:function(msg){
				alert(msg);
				location.reload();
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}

//同步刷单
function tongbuShuadan(){
	if(confirm('确定吗？')){
		new Request({
			url:'/admin/out-tuan/tongbu-shuadan',
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('刷单同步成功');
				}else{
					alert(msg);
				}
				location.reload();
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}

//同步刷单
function checkStock(){
	if(confirm('确定吗？')){
		var id = $('theid').value.trim();
		var st = $('thest').value.trim();
		if(!id){alert('id不正确');return false;}
		if(!st){alert('st不正确');return false;}
		new Request({
			url:'/admin/out-tuan/check-stock-change/id/'+id+'/st/'+st,
			onSuccess:function(msg){
				if(msg == 'ok'){
					alert('操作成功');
				}else{
					alert(msg);
				}
				location.reload();
			},
			onFailure:function(){
				alert('网络繁忙，请稍后重试');
			}
		}).send();
	}
}
</script>