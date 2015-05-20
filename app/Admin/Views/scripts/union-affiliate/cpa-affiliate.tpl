<div class="title">分成信息: {{$union.user_name}} [{{if $union.union_member_id}}个人推广联盟{{elseif $union.union_normal_id}}推广联盟{{elseif $union.union_content_id}}内容联盟{{/if}}]</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('/admin/union-affiliate/clist')">返回CPA可打款列表</a> ]
    </div>
<form name="myForm" id="myForm" action="/admin/union-affiliate/cpa-affiliate" method="post">
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
<tr>
<td width="10%"><b>打款到</b></td>
<td colspan="3">
{{if $affiliateMoney >= $payLimit}}
    银行帐号<input type="radio" name="aff_type" value="1" checked /><span style="padding: 0 10px"></span>虚拟账户<input type="radio" name="aff_type" value="2" />
{{else}}
    虚拟账户<input type="radio" name="aff_type" value="2" checked />
{{/if}}
</td>
</tr>
</tbody>
</table>
</div>
<br />
<div id="show_tab_page_2" style="display: {{if $affiliateMoney >= $payLimit}}{{else}}none{{/if}}">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>领款人姓名</b></td>
<td><input type="text" name="payee" value="{{$union.payee}}" msg="请填写领款人姓名" class="required" size="24" maxlength="30" /> *</td>
</tr>
<tr>
<td width="10%"><b>领款人联系电话</b></td>
<td><input type="text" name="telephone" value="{{$union.telephone}}" msg="请填写领款人联系电话" class="required" size="24" maxlength="30" /> *</td>
</tr>
<tr>
<td width="10%"><b>身份证号码</b></td>
<td><input type="text" name="id_card_no" value="{{$union.id_card_no}}" msg="请填写身份证号码" class="required" size="24" maxlength="30" /> *</td>
</tr>
<tr>
<td width="10%"><b>身份证照片</b></td>
<td><img src="{{$imgBaseUrl}}/{{$union.id_card_img}}" /></td>
</tr>
<tr>
<td width="10%"><b>开户银行</b></td>
<td><input type="text" name="bank_name" value="{{$union.bank_name}}" msg="请填写开户银行" class="required" size="24" maxlength="30" /> *</td>
</tr>
<tr>
<td width="10%"><b>开户银行全称</b></td>
<td><input type="text" name="bank_full_name" value="{{$union.bank_full_name}}" msg="请填写开户银行全称" class="required" size="24" maxlength="60" /> *</td>
</tr>
<tr>
<td width="10%"><b>银行帐号</b></td>
<td><input type="text" name="bank_account" value="{{$union.bank_account}}" msg="请填写银行帐号" class="required" size="24" maxlength="30" /> *</td>
</tr>
</tbody>
</table>
</div>
<div id="show_tab_page_3" style="display: {{if $affiliateMoney >= $payLimit}}none{{else}}{{/if}}">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>虚拟账户用户名</b></td>
<td><input type="text" name="user_name" value="" msg="请填写虚拟账户用户名" class="required" size="24" maxlength="30" /> *</td>
</tr>
</tbody>
</table>
</div>
<br />
<div id="show_tab_page_4">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>有效订单数</b></td>
<td><font color="#FF0000">{{$orderNum}}个</font></td>
</tr>
<tr>
<td width="10%"><b>分成金额</b></td>
<td> <input type="text" name="amount" value="{{$affiliateMoney}}" /></td>
</tr>
</tbody>
</table>
</div>
<div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%"><b>管理员留言</b></td>
<td><textarea style="width: 400px;height: 100px" name="admin_note"></textarea></td>
</tr>
<tr>
<td width="10%">
<input type="hidden" name="user_id" value="{{$union.user_id}}" />
<input type="hidden" name="user_name" value="{{$union.user_name}}" />
<input type="hidden" name="id_card_img" value="{{$union.id_card_img}}" />
<input type="hidden" name="get_money_type" value="{{$getMoneyType}}" />
</td>
<td>
<input type="submit" name="dosubmit" id="dosubmit" value="确定分成" />
</td>
</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>