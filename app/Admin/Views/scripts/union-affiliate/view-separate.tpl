<div class="title">查看分成信息</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/index')">返回已分成列表</a> ]
    </div>
<div id="print_div">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>会员名称</b></td>
<td>{{$pay.user_name}}</td>
</tr>
<tr>
<td width="10%"><b>领款人姓名</b></td>
<td>{{$pay.payee}}</td>
</tr>
<tr>
<td width="10%"><b>领款人联系电话</b></td>
<td>{{$pay.telephone}}</td>
</tr>
<tr>
<td width="10%"><b>身份证号码</b></td>
<td>{{$pay.id_card_no}}</td>
</tr>
<tr>
<td width="10%"><b>身份证照片</b></td>
<td>{{if $pay.id_card_img}}<img src="{{$imgBaseUrl}}/{{$pay.id_card_img}}" />{{/if}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行</b></td>
<td>{{$pay.bank_name}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行全称</b></td>
<td>{{$pay.bank_full_name}}</td>
</tr>
<tr>
<td width="10%"><b>银行帐号</b></td>
<td>{{$pay.bank_account}}</td>
</tr>
<tr>
<td width="10%"><b>虚拟账户用户名</b></td>
<td>{{$pay.account_user_name}}</td>
</tr>
<tr>
<td width="10%"><b>分成金额</b></td>
<td>{{$pay.amount}}</td>
</tr>
<tr>
<td width="10%"><b>领款方式</b></td>
<td>{{$pay.get_money_type}}</td>
</tr>
<tr>
<td width="10%"><b>处理管理员</b></td>
<td>{{$pay.admin_name}}</td>
</tr>
<tr>
<td width="10%"><b>处理时间</b></td>
<td>{{$pay.add_time}}</td>
</tr>
<tr>
<td width="10%"><b>管理员备注</b></td>
<td>{{$pay.admin_note}}</td>
</tr>
</tbody>
</table>
</div>
<div>
<input type="button" name="print" id="print" value="打印" onclick="print()" />
</div>
</div>
<script>
function print()
{
    var pr = window.open();
    pr.document.write($('print_div').innerHTML);
    pr.window.focus();     
    pr.window.print();
}
</script>