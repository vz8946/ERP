<div class="title">结款单清算</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/bill-clear-do" method="post" onSubmit="return checkThis()">
<input type="hidden" name="id" value="{{$detail.id}}" />
<input type="hidden" name="clear_amount" value="{{$detail.clear_amount}}" />
<input type="hidden" name="real_back_amount" value="{{$detail.real_back_amount}}" />
<table cellpadding="0" cellspacing="0" border="0" class="table" width="300">
  <tbody>
    <tr>
	  <td width="150">单据编号</td>
	  <td>{{$detail.bill_sn}}</td>
	</tr>
    <tr>
	  <td>网站</td>
	  <td>{{$detail.shop_name}}</td>
	</tr>
    <tr>
	  <td>期数</td>
	  <td>
	    {{foreach from=$detail.terms item=term}}
		{{$term.shop_name}} - {{$term.goods_name}} - <font color="red">{{$term.term}}</font> - <font color="#999">({{$term.stime|date_format:"%Y-%m-%d"}} ~ {{$term.etime|date_format:"%Y-%m-%d"}})<!--( ￥ {{$term.amount}})--></font><br />
		{{/foreach}}
		<!--参考：￥ {{$amt}}-->
	  </td>
	</tr>
    <tr>
	  <td>审核状态</td>
	  <td>
	  {{if $detail.check_status eq 0}}<font color="red">待审核</font>{{/if}}
	  {{if $detail.check_status eq 1}}<font color="green">已审核</font>{{/if}}
	  {{if $detail.check_status eq 2}}<font color="gray">无效</font>{{/if}}
	  </td>
	</tr>
    <tr>
	  <td>结款状态</td>
	  <td>{{if $detail.clear_status eq 0}}<font color="red">待收款</font>{{elseif $detail.clear_status eq 1}}<font color="green">部分结款</font>{{elseif $detail.clear_status eq 2}}<font color="gray">已结清</font>{{else}}未知状态{{/if}}</td>
	</tr>
    <tr>
	  <td>申请人</td>
	  <td>{{$detail.add_name}}</td>
	</tr>
    <tr>
	  <td>申请时间</td>
	  <td>{{$detail.add_time|date_format:"%Y-%m-%d %T"}}</td>
	</tr>
	<tr>
	  <td>备注</td>
	  <td>
	    {{foreach from=$detail.remarks item=remark}}
		{{$remark|replace:'@':'&nbsp;&nbsp;&nbsp;'}}<br />
		{{/foreach}}
	  </td>
	</tr>
	<tr>
	  <td>应结款时间</td>
	  <td>{{$detail.clear_time|date_format:"%Y-%m-%d"}}</td>
	</tr>
    <tr>
	  <td>应结款总额</td>
	  <td><span style=" color:blue; font-weight:bold;">{{$detail.clear_amount}}</span></td>
	</tr>
	<tr>
	  <td>实际结款金额</td>
	  <td>{{$detail.real_back_amount}}</td>
	</tr>
	<tr>
	  <td>还差</td>
	  <td id="haicha">{{$detail.remain}}</td>
	</tr>
	<tr>
	  <td>本次收款</td>
	  <td><input type="text" name="receive" id="receive" onkeyup="if(isNaN(value)){this.value=this.defaultValue;}" onafterpaste="if(isNaN(value)){this.value=this.defaultValue;}" onblur="chaer()" /></td>
	</tr>
	<tr>
	  <td>调整金额</td>
	  <td><input type="text" name="adjust_amount" value="{{$detail.adjust_amount}}" />&nbsp;&nbsp;&nbsp;&nbsp;<span id="tiaozheng"></span></td>
	</tr>
	<tr>
	  <td>添加备注</td>
	  <td><textarea cols="60" rows="7" name="remarks" id="remarks"></textarea></td>
	</tr>
    <tr>
      <td></td>
      <td>{{if $islock eq 1}}{{if $detail.clear_status neq 2}}<input type="submit" value="确认">&nbsp;&nbsp;{{/if}}{{/if}}<input type="button" value="返回" onclick="javascript:history.back();" /></td>
    </tr>
  </tbody>
</table>
</form>
<br />
<table class="mytable">
  <tr>
    <th colspan="4">收款纪录</th>
  </tr>
  <tr>
    <th>收款时间</th><th>操作人</th><th>收到</th><th>备注</th>
  </tr>
  {{foreach from=$logs item=log}}
  <tr>
    <td>{{$log.add_time|date_format:"%Y-%m-%d %T"}}</td><td>{{$log.add_name}}</td><td>{{$log.receipt}}</td><td>{{$log.remark}}</td>
  </tr>
  {{/foreach}}
</table>
</div>
<style type="text/css">
.mytable{ border-collapse:collapse; border:1px solid #ccc;}
.mytable th{ text-align:center;}
.mytable td,.mytable th{ border:1px solid #ccc; padding:5px;}
.mytable tr:hover{ background:#c2f6c0;}
</style>
<script type="text/javascript">
//验证
function checkThis(){
	var shop=$('receive').value.trim();
	if(shop==''){alert('本次收款');return false;}
	var remark=$('remarks').value.trim();
	if(remark==''){alert('请填写备注');return false;}
}
//计算差额
function chaer(){
	var cha=parseFloat(document.getElementById('haicha').innerHTML);
	var receive=parseFloat($('receive').value.trim());
	if(receive==''){alert('本次收款');return false;}
	$('tiaozheng').innerHTML='剩余应收款（参考）：'+(cha-receive);
}
</script>