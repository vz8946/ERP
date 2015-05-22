<div class="title">联盟信息: {{$union.user_name}} [{{if $union.union_member_id}}个人推广联盟{{elseif $union.union_normal_id}}推广联盟{{elseif $union.union_content_id}}内容联盟{{/if}}]</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/index')">可打款列表</a> ]
    </div>
<div id="show_tab_page_1">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
{{if $union.union_member_id}}
<tr>
<td width="10%"><b>会员名称</b></td>
<td width="40%">{{$union.user_name}}</td>
<td width="10%"><b>昵称</b></td>
<td width="40%">{{$union.nick_name}}</td>
</tr>
<tr>
<td width="10%"><b>真实姓名</b></td>
<td width="40%">{{$union.real_name}}</td>
<td width="10%"><b>分成比率</b></td>
<td colspan="3">{{$union.proportion}}</td>
</tr>
<tr>
<td width="10%"><b>生日</b></td>
<td width="40%">{{$union.birthday}}</td>
<td width="10%"><b>性别</b></td>
<td width="40%">{{$union.sex}}</td>
</tr>
<tr>
<td width="10%"><b>Email</b></td>
<td width="40%">{{$union.email}}</td>
<td width="10%"><b>手机</b></td>
<td colspan="3">{{$union.office_phone}}</td>
</tr>
<tr>
<td width="10%"><b>MSN</b></td>
<td width="40%">{{$union.msn}}</td>
<td width="10%"><b>QQ</b></td>
<td width="40%">{{$union.qq}}</td>
</tr>
<tr>
<td width="10%"><b>办公室电话</b></td>
<td width="40%">{{$union.office_phone}}</td>
<td width="10%"><b>住宅电话</b></td>
<td width="40%">{{$union.home_phone}}</td>
</tr>
<tr>
<td width="10%"><b>账户余额</b></td>
<td width="40%">{{$union.money}}</td>
<td width="10%"><b>积分</b></td>
<td width="40%">{{$union.point}}</td>
</tr>
{{elseif $union.union_normal_id}}
<tr>
<td width="10%">联盟登录名</td>
<td width="40%">{{$union.user_name}}</td>
<td width="10%">联盟名称</td>
<td width="40%">{{$union.cname}}</td>
</tr>
<tr>
<td width="10%">真实姓名</td>
<td width="40%">{{$union.real_name}}</td>
<td width="10%">性别</td>
<td width="40%">{{$union.sex}}</td>
</tr>
<tr>
<td width="10%">分成比率</td>
<td width="40%">{{$union.proportion}}</td>
<td width="10%">分成类型</td>
<td width="40%">{{$union.affiliate_type}}</td>
</tr>
<tr>
<td width="10%">结算方式</td>
<td width="40%">{{$union.get_money_type}}</td>
<td width="10%">Email</td>
<td width="40%">{{$union.email}}</td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%">{{$union.msn}}</td>
<td width="10%">QQ</td>
<td width="40%">{{$union.qq}}</td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%">{{$union.phone}}</td>
<td width="10%">手机</td>
<td width="40%">{{$union.mobile}}</td>
</tr>
{{elseif $union.union_content_id}}
<tr>
<td width="10%"><b>网站名称</b></td>
<td>{{$union.web_name}}</td>
</tr>
<tr>
<td width="10%"><b>频道名称</b></td>
<td>{{$union.channel_name}}</td>
</tr>
<tr>
<td width="10%"><b>频道地址</b></td>
<td>{{$union.channel_url}}</td>
</tr>
{{/if}}
</tbody>
</table>
</div>
<br />
<div id="show_tab_page_2">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>领款人姓名</b></td>
<td>{{$union.payee}}</td>
</tr>
<tr>
<td width="10%"><b>领款人联系电话</b></td>
<td>{{$union.telephone}}</td>
</tr>
<tr>
<td width="10%"><b>身份证号码</b></td>
<td>{{$union.id_card_no}}</td>
</tr>
<tr>
<td width="10%"><b>身份证照片</b></td>
<td>{{if $union.id_card_img}}<img src="{{$imgBaseUrl}}/{{$union.id_card_img}}" />{{/if}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行</b></td>
<td>{{$union.bank_name}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行全称</b></td>
<td>{{$union.bank_full_name}}</td>
</tr>
<tr>
<td width="10%"><b>银行帐号</b></td>
<td>{{$union.bank_account}}</td>
</tr>
</tbody>
</table>
</div>
</div>