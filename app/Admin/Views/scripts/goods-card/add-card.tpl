<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=add-card}}" method="post">
<div class="title">生成提货卡</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=log-list}}')">提货卡发放记录</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">选择卡类型 * </td>
<td width="40%">
<select name="card_type_id" id="card_type_id">
{{if $cardTypeList}}
{{foreach from=$cardTypeList item=cardType}}
<option value="{{$cardType.card_type_id}}">{{$cardType.card_name}} ({{$cardType.goods_num}}选1)</option>
{{/foreach}}
{{/if}}
</select>
{{if $cardTypeList}}
 <a href="javascript:fGo()" onclick="window.open('/admin/goods-card/view-type/id/' + document.getElementById('card_type_id').value)">卡详情信息>></a>
{{/if}}
</td>
<td width="10%">生成数量 * </td>
<td width="40%"><input type="text" name="number" id="number" size="6" maxlength="6" msg="请填写生成数量" class="required int"/></td>
</tr>
<tr>
<td>有效日期</td>
<td><input type="text" name="end_date" id="end_date" size="15" class="Wdate" onClick="WdatePicker()"/></td>
<td></td>
<td></td>
</tr>
<tr>
<td width="10%">备注</td>
<td colspan="3"><textarea style="width: 500px;height: 100px" name="note"></textarea></td>
</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" onclick="return check()"/> <input type="reset" name="reset" value="重置" /></div>
</form>

<script>
function check()
{
    if (document.getElementById('card_type_id').value == '') {
        alert('卡类型没有选择!');
        return fales;
    }
    if (document.getElementById('number').value == '') {
        alert('请填写数量!');
        return fales;
    }
    
    return true;
}
</script>