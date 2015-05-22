<div class="member">
    {{include file="member/menu.tpl"}}
  <div class="memberright">
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_password.png"></div>
            <form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" onsubmit="return passwordSubmit()" target="ifrmSubmit">
            <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
                <tbody>
                    {{if !$member.setPwd}}
                    <tr >
                        <th width="14%" height="35" align="right"><span class="red">*</span>原密码&nbsp;&nbsp;&nbsp; </th>
                      <td width="86%" height="35"><input type="password" name="old_password" size="20" maxlength="20" class="istyle" /></td>
                    </tr>
                    {{/if}}
                    <tr>
                        <th height="35" align="right"><span class="red">*</span>新密码&nbsp;&nbsp;&nbsp; </th>
                      <td height="35"><input type="password" name="password" size="20" maxlength="20" class="istyle"/></td>
                    </tr>
                    <tr>
                        <th height="35" align="right"><span class="red">*</span>确认密码&nbsp;&nbsp;&nbsp; </th>
                      <td height="35"><input type="password" name="confirm_password" size="20" maxlength="20" class="istyle" /></td>
                    </tr>
                    <tr ><td height="35" align="right">&nbsp;</td><td height="35"> <input type="submit" name="dosubmit" id="dosubmit" value="确 定" class="buttons"/></td></tr>
                </tbody>
            </table>
          </form>
		 {{if $member.setPwd}} 
			     <p class="text-mid"><font color="#FF9933"> 您现在的登录用户名是: {{$member.user_name}} 修改新密码以后可直接从 www.1jiankang.com登录！</font></p>
        {{/if}}
  </div>
</div>

<iframe src="about:blank" style="width:0px;height:0px;" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script>
function passwordSubmit(){
	{{if !$member.setPwd}}
	if($.trim($("#myForm input[name='old_password']").val())==''){
		alert('请输入原密码!');return false;
	}
	{{/if}}
	if($.trim($("#myForm input[name='password']").val())=='' || !/^[a-zA-Z0-9]{6,20}$/.test($.trim($("#myForm input[name='password']").val())) ){
		alert('密码必须为6-20位的字母和数字的组合!');return false;
	}
	if($.trim($("#myForm input[name='password']").val())!=$.trim($("#myForm input[name='confirm_password']").val())){
		alert('新密码和确认密码不一致!');return false;
	}
	$('#dosubmit').attr('value','提交中..');
	$('#dosubmit').attr('disabled',true);
	return true;
}
</script>