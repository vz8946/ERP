<style type="text/css">
.mytable{ width:100%; border-collapse:collapse; border:1px solid #ccc;}
.mytable td{ padding:5px;}
</style>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">外部团购刷单销账</div>
<form name="searchForm" id="searchForm" action="/admin/out-tuan/is-cheat-order">
<div class="search">
	  销账开始日期：<input type="text" name="cheatfromdate" id="cheatfromdate" size="15" value="{{$param.cheatfromdate}}"  class="Wdate" onClick="WdatePicker()"/>
	  结束日期：<input  type="text" name="cheattodate" id="cheattodate" size="15" value="{{$param.cheattodate}}"  class="Wdate"  onClick="WdatePicker()"/>
  <span class="clear"></span>
  订单号：<input type="text" name="order_sn" value="{{$param.order_sn}}" /><br />
  所属网站：<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>&nbsp;&nbsp;&nbsp;&nbsp;
 
  结款状态：<select name="isclear"><option value="">请选择</option><option {{if $param.isclear eq off}}selected{{/if}} value="off">未</option><option {{if $param.isclear eq on}}selected{{/if}} value="on">已</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  刷单销账：<select name="ischeatclear"><option value="">请选择</option><option {{if $param.ischeatclear eq 1}}selected{{/if}} value="1">未销</option><option {{if $param.ischeatclear eq 2}}selected{{/if}} value="2">已销</option></select>&nbsp;&nbsp;&nbsp;&nbsp;
  </select><br>
  下单开始日期：<input type="text" name="ordertime_start" id="ordertime_start" size="15" value="{{$param.ordertime_start}}"  class="Wdate" onClick="WdatePicker()"/>
	  结束日期：<input  type="text" name="ordertime_end" id="ordertime_end" size="15" value="{{$param.ordertime_end}}"  class="Wdate"  onClick="WdatePicker()"/>
  结算开始日期：<input type="text" name="cleartime_start" id="cleartime_start" size="15" value="{{$param.cleartime_start}}"  class="Wdate" onClick="WdatePicker()"/>
	  结束日期：<input  type="text" name="cleartime_end" id="cleartime_end" size="15" value="{{$param.cleartime_end}}"  class="Wdate"  onClick="WdatePicker()"/> 
  &nbsp;&nbsp;<input type="submit" name="dosearch" value="查询"/>
</div>
<div class="content">
<table class="mytable"><tr><td align="left" width="200">&nbsp;</td>
<td style=" text-align:right;">&nbsp;销售总额：
  <sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalPrice}}</sapn>&nbsp;&nbsp;&nbsp;结算总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalSupplyPrice}}</sapn>&nbsp;&nbsp;&nbsp;差价总额：<sapn style="font-weight:bold;color:red;">￥&nbsp;{{$totalFee}}</sapn></td></tr></table>
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
		<td></td>
		<td>ID</td>
		<td>网站</td>
		<td>订单号</td>
		<td>下单时间</td>
		<td>销账时间</td>
		<td>结算时间</td>
		<td width="280">金额</td>
		<td width="200">状态</td>
        </tr>
    </thead>
    <tbody>

    {{foreach from=$datas item=data}}
    <tr id="row{{$data.id}}" >
	<td>{{if $data.ischeat eq 1}}<input type="checkbox" name="ids[]" value="{{$data.id}}" />{{/if}}</td>
    	<td>{{$data.id}}</td>
        <td><strong>{{$data.shop_name}}</strong></td>
        <td>{{$data.order_sn}}</td>
	<td>{{$data.order_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	 <td>{{$data.cheat_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	 <td>{{$data.clear_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
	<td><span style="width:80px; font-size:12px; overflow:hidden;">
	<font color="green">售：</font>{{$data.order_price}} <font color="green">供：</font>{{$data.supply_price}}
	<font color="green">费：</font>{{$data.fee}}
		</span></td>
	<td>结算：{{if $data.clear_pay eq 0}}<font color="red">未</font>{{else}}<font color="green">已</font>{{/if}}
	{{if $data.ischeat>0}}销账：{{if $data.ischeat eq 1}}<font color="red">未销</font>{{else if $data.ischeat eq 2}}<font color="green">已销</font>{{/if}}
	{{/if}}</td>
    </tr>
    {{/foreach}}

	<tr>
		<td colspan="15"><input type="checkbox" onclick="selectAll(this)" />&nbsp;&nbsp;<input type="button" onclick="setClear()" value="设为已销账" /></td>
	</tr>

    </tbody>
</table>
</div>
</form>
<div class="page_nav">{{$pageNav}}</div>
<script type="text/javascript">
//
function selectAll(obj){
	var flag = (obj.checked)?true:false;
	var ckb = document.getElementsByName('ids[]');
	for(var i=0,ct=ckb.length; i<ct; i++){ckb[i].checked = flag;}
}
//设置多个
function setClear(){
	var ckb = document.getElementsByName('ids[]');
	var flag = false;
	var idss = new Array();
	var ids = '';
	for(var i=0,ct=ckb.length; i<ct; i++){
		if(ckb[i].checked){
			idss.push(ckb[i].value);
			flag = true;
		}
	}
	if(!flag){alert('请选择');return false;}
	ids = idss.join(',');
	
	if(confirm('确认销账？')){
		new Request({
			url:'/admin/out-tuan/set-ischeat-clear/orderid/'+ids,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('销账成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onFailure:function(){
				alert('网络错误，请稍后重试');
			}
		}).send();
	}
}
//设置单个
function ischeatClear(orderid){
	if(!orderid){alert('参数错误');return;}
	var price = window.prompt("请输入销账金额");
	price = parseFloat(price.trim());
	if(isNaN(price)){alert('请输入正确金额');return;}
	if(confirm('(￥'+price+') 确认销账？')){
		new Request({
			url:'/admin/out-tuan/set-ischeat-clear/orderid/'+orderid+'/price/'+price,
			onSuccess:function(msg){
				if(msg=='ok'){
					alert('销账成功');
					location.reload();
				}else{
					alert(msg);
				}
			},
			onFailure:function(){
				alert('网络错误，请稍后重试');
			}
		}).send();
	}
}
</script>