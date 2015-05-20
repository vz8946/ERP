<div class="title">结款单审核</div>
<div class="content">
<form name="myForm" id="myForm" action="/admin/out-tuan/bill-verify" method="post" onSubmit="return checkThis()">
<input type="hidden" name="id" value="{{$detail.id}}" />
<input type="hidden" value="{{$detail.clear_amount}}" name="ori_clear_amount" />
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
	  <td>应结款总额</td>
	  <td><input type="text" value="{{$detail.clear_amount}}" name="clear_amount" id="clear_amount" onkeyup="if(isNaN(value)){this.value=this.defaultValue;}" onafterpaste="if(isNaN(value)){this.value=this.defaultValue;}" {{if $detail.check_status neq 0}} disabled="disabled"{{/if}}  /></td>
	</tr>
	<tr>
	  <td>应结款时间</td>
	  <td>{{$detail.clear_time|date_format:"%Y-%m-%d"}}</td>
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
	  <td>设定审核状态</td>
	  <td><!--<input type="radio" name="check_status" value="0" {{if $detail.check_status eq 0}}checked="checked"{{/if}} /><font color="red">待审核</font><br />--><input type="radio" name="check_status" value="1" {{if $detail.check_status eq 1}}checked="checked"{{/if}}{{if $detail.check_status neq 0}} disabled="disabled"{{/if}} /><font color="green">同意</font><br /><input type="radio" name="check_status" value="2" {{if $detail.check_status eq 2}}checked="checked"{{/if}}{{if $detail.check_status neq 0}} disabled="disabled"{{/if}} /><font color="gray">无效</font></td>
	</tr>
	<tr>
	  <td>添加备注</td>
	  <td><textarea cols="60" rows="7" name="remarks" id="remarks"></textarea></td>
	</tr>
    <tr>
      <td></td>
      <td>{{if $islock eq 1}}{{if $detail.clear_status neq 2}}{{if $detail.check_status eq 0}}<input type="submit" value="确认">&nbsp;&nbsp;{{/if}}{{/if}}{{/if}}<input type="button" value="返回" onclick="javascript:history.back();" /></td>
    </tr>
  </tbody>
</table>
</form><br />
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
	//结款总额
	var shop=$('clear_amount').value.trim();
	if(shop==''){alert('应结款总额');return false;}
	//审核状态
	var sh=document.getElementsByName('check_status');
	var flag=0;
	for(var i=0;i<sh.length;i++){
		if(sh[i].checked){flag=1;break;}
	}
	if(flag==0){alert('请选择审核状态');return false;}
	//备注
	var remark=$('remarks').value.trim();
	if(remark==''){alert('请填写备注');return false;}
}
</script>