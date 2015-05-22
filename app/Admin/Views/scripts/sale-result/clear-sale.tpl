<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<br>
  <table width="100%" cellpadding="0" cellspacing="2"  border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>产品编码</b></td>
          <td>{{$info.product_sn}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>产品名称</b></td>
          <td>{{$info.product_name}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>应收数量</b></td>
          <td>{{$info.number}}</td>
        </tr>
        <tr bgcolor="#F0F1F2">
          <td width="100">　<b>已收数量</b></td>
          <td>{{if $info.deal_number}}{{$info.deal_number}}{{else}}0{{/if}}</td>
        </tr>
  </table>
  <br>
<form id="myform" name="myform">
{{if $info.need_deal_number neq '0'}}
<table width="100%" cellpadding="0" cellspacing="2"  border="0">
  <tr>
    <td width="160">
      　结算数量：<input type="text" name="number" id="number" value="{{$info.need_deal_number}}" size="6">
    </td>
    <td>
      <input type="hidden" name="need_deal_number" id="need_deal_number" value="{{$info.need_deal_number}}" />
      <input type="hidden" name="sale_result_id" value="{{$info.sale_result_id}}" />
      <input type="button" name="submit" value="结算" onclick="recieve()">
    </td> 
  </tr>
  
</table>
{{/if}}
</form>

{{if $log_inf}}
  <table width="100%" cellpadding="0" cellspacing="2"  border="0">
  {{foreach from=$log_inf item=log}}
  <tr><td colspan="2">操作人：{{$log.created_by}}&nbsp;&nbsp;结算数量：{{$log.number}}&nbsp;&nbsp;操作时间：{{$log.created_ts}}</td></tr>
  {{/foreach}}
  </table>
  {{/if}}


<script language="JavaScript">
function recieve()
{
    
    var number = $("number").value;
    if (isNaN(parseInt(number))) {
        $("number").value = '0';
        alert('结算数量不正确');
        return false;
    }
    if (parseInt(number) < 1) {
        $("number").value = '0';
        alert('请输入结算数量');
        return false;
    }
    var need_deal_number = $("need_deal_number").value;

    if (parseInt(number) > parseInt(need_deal_number)) {
        $("number").value = need_deal_number;
        alert('超出未结算数量');
        return false;
    }
    if (!confirm('确认要结算吗？')) {
        return false;
    }
    ajax_submit($('myform'), '{{url}}/number/' + number);
}
</script>