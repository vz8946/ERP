<div class="member">

{{include file="member/menu.tpl"}}
<div class="memberright">
	<div style="margin-top:10px;"><img src="{{$imgBaseUrl}}/images/shop/member_profile.png"></div>
      <form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" onsubmit="return profileSubmit()" target="ifrmSubmit">
       <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
            <tbody>
                <tr>
                    <th height="30" align="right">会员名称：</th>
                    <td height="30" colspan="3">{{$member.user_name}}</td>
                </tr>
                <tr>
                    <th height="30" align="right">昵称：</th>
                    <td height="30"><input type="text" name="nick_name"  maxlength="30" onblur="checkNickName(this.value,'{{$member.nick_name}}')" size="20" value="{{$member.nick_name}}" class="istyle"/><br/>
                    <span id="nick_name_notice"></span></td>
                    <th height="30" align="right">真实姓名：</th>
                    <td height="30"><input type="text" name="real_name" size="20" maxlength="40" value="{{$member.real_name}}" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">生日：</th>
                    <td height="30">{{if $birthdayAble}}  {{html_select_date field_array=birthday time=0000-00-00 month_format=%m field_order=YMD start_year=-70 reverse_years=true year_empty="请选择" month_empty="请选择" day_empty="请选择"}}  {{else}} {{$member.birthday}} {{/if}}</td>
                    <th height="30" align="right">性别：</th>
                    <td height="30">{{html_radios name="sex" options=$sexRadios checked=$member.sex separator=""}}</td>
                </tr>
                <tr>
                    <th height="30" align="right">Email：</th>
                  <td height="30">
                  {{if $member.ischecked}}
                   <input type="text"  readonly="true" name="email" size="20" maxlength="50" value="{{$member.email}}" class="istyle"/> 已验证
                  {{else}}
                   <input type="text"  name="email" size="20" maxlength="50" value="{{$member.email}}" class="istyle"/>
                   <input type="button" value="验证" onclick="verify_email();" class="buttons">
                  {{/if}}
                  
                  </td>
                    <th height="30" align="right">手机：</th>
                    <td height="30" colspan="3"><input type="text" name="mobile" size="20" maxlength="40" value="{{$member.mobile}}" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">MSN：</th>
                  <td height="30"><input type="text" name="msn" size="20" maxlength="50" value="{{$member.msn}}" class="istyle"/></td>
                    <th height="30" align="right">QQ：</th>
                    <td height="30"><input type="text" name="qq" size="20" maxlength="20" value="{{$member.qq}}" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">办公室电话：</th>
                  <td height="30"><input type="text" name="office_phone" size="20" maxlength="40" value="{{$member.office_phone}}" class="istyle"/></td>
                    <th height="30" align="right">住宅电话：</th>
                    <td height="30"><input type="text" name="home_phone" size="20" maxlength="40" value="{{$member.home_phone}}" class="istyle"/></td>
                </tr>
            </tbody>
        </table>
        <div style="padding-top: 10px; text-align:center"><input type="submit" name="dosubmit" id="dosubmit" value="确 定" class="buttons"/>
          &nbsp;
        <input type="reset" name="reset" value="重 置" onclick="resetDom();" class="buttons"/>
        <br /><br /></div>
      </form>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
  
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script language="javascript" type="text/javascript" src="{{$imgBaseUrl}}/scripts/check.js"></script>
<script>
var tempNickName = '';

function profileSubmit(){
	var msg = '';
	if($.trim($("#myForm input[name='email']").val())!='' && !Check.isEmail($.trim($("#myForm input[name='email']").val())) ){
		msg += '请输入正确的Email地址!\n';
	}
	if($.trim($("#myForm input[name='mobile']").val())!='' && !Check.isMobile($.trim($("#myForm input[name='mobile']").val())) ){
		msg += '请输入正确的手机号码!\n';
	}
	if($.trim($("#myForm input[name='msn']").val())!='' && !Check.isEmail($.trim($("#myForm input[name='msn']").val())) ){
		msg += '请输入正确的MSN!\n';
	}
	if($.trim($("#myForm input[name='qq']").val())!='' && !Check.isQq($.trim($("#myForm input[name='qq']").val())) ){
		msg += '请输入正确的QQ号码!\n';
	}
	if($.trim($("#myForm input[name='office_phone']").val())!='' && !Check.isTel($.trim($("#myForm input[name='office_phone']").val())) ){
		msg += '请输入正确的办公室电话!\n';
	}
	if($.trim($("#myForm input[name='home_phone']").val())!='' && !Check.isTel($.trim($("#myForm input[name='home_phone']").val())) ){
		msg += '请输入正确的住宅电话!\n';
	}
	if (msg.length > 0) {
        alert(msg);
        return false;
    } else {
        $('#dosubmit').attr('value','提交中..');
		$('#dosubmit').attr('disabled',true);
		return true;
    }
}

//检测用户昵称是否被占用
function checkNickName(nickname,bak_nickname){
	var nickname = $.trim(nickname);
    var bak_nickname = $.trim(bak_nickname);
	if (nickname != '' && nickname != bak_nickname && nickname != tempNickName){
		$.ajax({
			url:'/auth/check',
			data:{nick_name:nickname},
			success:function(msg){
				if(msg=='ok'){
					$('#nick_name_notice').html('&nbsp;<font color="green">可以使用!</font>');
					$('#dosubmit').attr('disabled',false);
				}else{
					$('#nick_name_notice').html('&nbsp;<font color="red">已经被使用!</font>');
					$('#dosubmit').attr('disabled',true);
				}
				tempNickName = nickname;
			}
		})
	}else if(nickname == bak_nickname){
		resetDom();
	}
}
function verify_email()
{
	var email =  $.trim($("#myForm input[name='email']").val());
	if(email!='' && !Check.isEmail(email) ){
		alert('请输入正确的Email地址!');
		return false;
	}
	
	$.post("/member/verifyemail/email/"+email,
			 function(data) {
			   alert(data.msg);
			 }, 
		    "json"
	);
}
//数据复位
function resetDom(){
	$('#nick_name_notice').html('');
	$('#dosubmit').attr('disabled',false);
}
</script>