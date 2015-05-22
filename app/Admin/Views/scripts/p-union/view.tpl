<div class="title">{{$title}}</div>
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">账户信息</li>
	</ul>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">联盟列表</a> ]
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$pUnion.user_id}}')">编辑信息</a> ]
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">联盟登录名</td>
<td width="40%">{{$pUnion.user_name}}</td>
<td width="10%">联盟名称</td>
<td width="40%">{{$pUnion.cname}}</td>
</tr>
<tr>
<td width="10%">真实姓名</td>
<td width="40%">{{$pUnion.real_name}}</td>
<td width="10%">性别</td>
<td width="40%">{{$pUnion.sex}}</td>
</tr>
<tr>
<td width="10%">分成比率</td>
<td width="40%">{{$pUnion.proportion}}</td>
<td width="10%">分成类型</td>
<td width="40%">{{$pUnion.affiliate_type}}</td>
</tr>
<tr>
<td width="10%">结算方式</td>
<td width="40%">{{$pUnion.get_money_type}}</td>
<td width="10%">Email</td>
<td width="40%">{{$pUnion.email}}</td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%">{{$pUnion.msn}}</td>
<td width="10%">QQ</td>
<td width="40%">{{$pUnion.qq}}</td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%">{{$pUnion.phone}}</td>
<td width="10%">手机</td>
<td width="40%">{{$pUnion.mobile}}</td>
</tr>
</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="table_form" class="table_form">
<tbody>
<tr>
<td width="10%"><b>领款人姓名</b></td>
<td>{{$pUnion.payee}}</td>
</tr>
<tr>
<td width="10%"><b>领款人联系电话</b></td>
<td>{{$pUnion.telephone}}</td>
</tr>
<tr>
<td width="10%"><b>身份证号码</b></td>
<td>{{$pUnion.id_card_no}}</td>
</tr>
<tr>
<td width="10%"><b>身份证照片</b></td>
<td>{{if $pUnion.id_card_img}}<img src="{{$imgBaseUrl}}/{{$pUnion.id_card_img}}" />{{/if}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行</b></td>
<td>{{$pUnion.bank_name}}</td>
</tr>
<tr>
<td width="10%"><b>开户银行全称</b></td>
<td>{{$pUnion.bank_full_name}}</td>
</tr>
<tr>
<td width="10%"><b>银行帐号</b></td>
<td>{{$pUnion.bank_account}}</td>
</tr>
</tbody>
</table>
</div>
</div>