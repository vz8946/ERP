<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<div class="title">{{$title}}  </div>
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">账户信息</li>
	</ul>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('{{url param.action=index}}')">推广联盟列表</a> ]
    {{if $action eq 'edit'}}[ <a href="javascript:fGo()" onclick="G('{{url param.action=view param.id=$pUnion.user_id}}')">查看信息</a> ]{{/if}}
</div>
<div id="show_tab_page_1" style="display:block">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">登录名 * </td>
<td width="40%"><input type="text" name="user_name" id="user_name" msg="请填写登录名" class="required limitlen" min="3" max="30" size="20" maxlength="30" value="{{$pUnion.user_name}}" {{if $action eq 'edit'}}readonly{{else}} onkeyup="if(/[\u4E00-\u9FA5]/i.test(this.value)){this.value='';}" onblur="ajax_check('{{url param.action=check}}','user_name')"{{/if}} /><span id="tip_user_name" class="errorMessage">请输入3-30个字符</span></td>
<td width="10%">联盟名称 * </td>
<td width="40%"><input type="text" name="cname" id="cname" msg="请填写联盟名称" class="required limitlen" min="3" max="50" size="20" maxlength="100" value="{{$pUnion.cname}}" /><span id="tip_cname" class="errorMessage">请输入3-50个字符</span></td>
</tr>
<tr>
<td>密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="password" size="20" maxlength="20" {{if $action eq 'add'}}msg="请填写会员密码" class="required"{{/if}} />{{if $action eq 'edit'}} {{$changePassword}}{{/if}}</td>
<td>重复密码{{if $action eq 'add'}} * {{/if}}</td>
<td><input type="password" name="confirm_password" size="20" maxlength="20" {{if $action eq 'add'}}msg="请填写重复密码" class="required equal" to="password"{{/if}} />{{if $action eq 'edit'}} {{$changePassword}}{{/if}}</td>
</tr>
<tr>
<td width="10%">真实姓名</td>
<td width="40%"><input type="text" name="real_name" id="real_name" size="20" maxlength="60" value="{{$pUnion.real_name}}" /></td>
<td width="10%">性别</td>
<td width="40%">{{html_radios name="sex" options=$sexRadios checked=$pUnion.sex separator=""}}</td>
</tr>
<tr>
<td width="10%">分成比率</td>
<td width="40%">
<input type="hidden" name="ori_calculate_type" value="{{$pUnion.calculate_type}}" />
<select style="float:left" name="calculate_type" onchange="showHide(this.value)">
<option value="2" {{if $pUnion.calculate_type eq 2}}selected=selected{{/if}}>商品比例分成</option>
<option value="1" {{if $pUnion.calculate_type eq 1}}selected=selected{{/if}}>固定比例分成</option>
</select><span id="spann" style="display:{{if $pUnion.calculate_type eq 1}}block{{else}}none{{/if}}; float:left;">&nbsp;&nbsp;<input type="text" name="proportion" id="proportion" msg="请填写分成比率" class="required" size="4" maxlength="2" value="{{$pUnion.proportion}}" /> (0-40)</span>
</td>
<td width="10%">分成类型</td>
<td width="40%">{{html_radios name="affiliate_type" options=$affiliateTypeRadios checked=$pUnion.affiliate_type separator=""}}</td>
</tr>
<tr>
<td width="10%">分成规则</td>
<td width="40%" ><input type="text"  readonly="readonly" name="proportion_rule" id="proportion_rule" msg="请填写分成规则" class="required" size="4" maxlength="2" value="0" /><span id="tip_cname" class="errorMessage">注：填写类型为数字，如果是按照会员级别递减分成的填写1，默认是0。</span></td>
<td> 所属站点</td>
<td> </td>
</tr>
<tr>
<td width="10%">结算方式</td>
<td width="40%">{{html_radios name="get_money_type" options=$getMoneyTypeRadios checked=$pUnion.get_money_type separator=""}}</td>
<td width="10%">Email</td>
<td width="40%"><input type="text" name="email" id="email" size="20" maxlength="60" value="{{$pUnion.email}}" /></td>
</tr>
<tr>
<td width="10%">MSN</td>
<td width="40%"><input type="text" name="msn" id="msn" size="20" maxlength="60" value="{{$pUnion.msn}}" /></td>
<td width="10%">QQ</td>
<td width="40%"><input type="text" name="qq" id="qq" size="20" maxlength="20" value="{{$pUnion.qq}}" /></td>
</tr>
<tr>
<td width="10%">电话</td>
<td width="40%"><input type="text" name="phone" id="phone" size="20" maxlength="40" value="{{$pUnion.phone}}" /></td>
<td width="10%">手机</td>
<td width="40%"><input type="text" name="mobile" id="mobile" size="20" maxlength="20" value="{{$pUnion.mobile}}" /></td>
</tr>

<tr>
<td width="10%">推广类别</td>
<td width="40%"><label>
  <select  name="un_type">
    <option value="0"  >CPS--按订单金额分成  </option>
    <option value="1"  {{if $pUnion.un_type eq '1'}} selected="selected" {{/if}} >CPA--按订单数量分成  </option>
  </select>
</label></td>
<td width="10%"></td>
<td width="40%"></td>
</tr>

</tbody>
</table>
</div>
<div id="show_tab_page_2" style="display:none">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="table_form" class="table_form">
<tbody>
<tr>
<td width="10%">领款人姓名</td>
<td><input type="text" name="payee" id="payee" size="20" maxlength="60" value="{{$pUnion.payee}}" /></td>
</tr>
<tr>
<td width="10%">领款人联系电话</td>
<td><input type="text" name="telephone" id="telephone" size="20" maxlength="40" value="{{$pUnion.telephone}}" /></td>
</tr>
<tr>
<td width="10%">身份证号码</td>
<td><input type="text" name="id_card_no" id="id_card_no" size="30" maxlength="30" value="{{$pUnion.id_card_no}}" /></td>
</tr>
<tr>
<td width="10%">身份证照片</td>
<td><input type="file" name="id_card_img" id="id_card_img" size="30" />{{if $pUnion.id_card_img}}<a href="{{$imgBaseUrl}}/{{$pUnion.id_card_img}}" target="_blank"><img src="/images/admin/picflag.gif" border="0" title="点击查看图片"></a> <input type="checkbox" name="delete_id_card_img" value="1" alt="删除" /> 删除图片{{/if}}</td>
</tr>
<tr>
<td width="10%">开户银行</td>
<td><input type="text" name="bank_name" id="bank_name" size="30" maxlength="50" value="{{$pUnion.bank_name}}" /></td>
</tr>
<tr>
<td width="10%">开户银行全称</td>
<td><input type="text" name="bank_full_name" id="bank_full_name" size="30" maxlength="100" value="{{$pUnion.bank_full_name}}" /></td>
</tr>
<tr>
<td width="10%">银行帐号</td>
<td><input type="text" name="bank_account" id="bank_account" size="30" maxlength="30" value="{{$pUnion.bank_account}}" /></td>
</tr>
</tbody>
</table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
<script type="text/javascript">
function showHide(value){
	v=parseInt(value);
	span=document.getElementById('spann');
	if(v==1){
		span.style.display = "block";
	}else{
		span.style.display = "none";
	}
}
</script>