<?php /* Smarty version 2.6.19, created on 2014-10-30 11:18:26
         compiled from member/profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'member/profile.tpl', 22, false),array('function', 'html_radios', 'member/profile.tpl', 24, false),)), $this); ?>
<div class="member">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="memberright">
	<div style="margin-top:10px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_profile.png"></div>
      <form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" onsubmit="return profileSubmit()" target="ifrmSubmit">
       <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
            <tbody>
                <tr>
                    <th height="30" align="right">会员名称：</th>
                    <td height="30" colspan="3"><?php echo $this->_tpl_vars['member']['user_name']; ?>
</td>
                </tr>
                <tr>
                    <th height="30" align="right">昵称：</th>
                    <td height="30"><input type="text" name="nick_name"  maxlength="30" onblur="checkNickName(this.value,'<?php echo $this->_tpl_vars['member']['nick_name']; ?>
')" size="20" value="<?php echo $this->_tpl_vars['member']['nick_name']; ?>
" class="istyle"/><br/>
                    <span id="nick_name_notice"></span></td>
                    <th height="30" align="right">真实姓名：</th>
                    <td height="30"><input type="text" name="real_name" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['real_name']; ?>
" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">生日：</th>
                    <td height="30"><?php if ($this->_tpl_vars['birthdayAble']): ?>  <?php echo smarty_function_html_select_date(array('field_array' => 'birthday','time' => "0000-00-00",'month_format' => "%m",'field_order' => 'YMD','start_year' => -70,'reverse_years' => true,'year_empty' => "请选择",'month_empty' => "请选择",'day_empty' => "请选择"), $this);?>
  <?php else: ?> <?php echo $this->_tpl_vars['member']['birthday']; ?>
 <?php endif; ?></td>
                    <th height="30" align="right">性别：</th>
                    <td height="30"><?php echo smarty_function_html_radios(array('name' => 'sex','options' => $this->_tpl_vars['sexRadios'],'checked' => $this->_tpl_vars['member']['sex'],'separator' => ""), $this);?>
</td>
                </tr>
                <tr>
                    <th height="30" align="right">Email：</th>
                  <td height="30">
                  <?php if ($this->_tpl_vars['member']['ischecked']): ?>
                   <input type="text"  readonly="true" name="email" size="20" maxlength="50" value="<?php echo $this->_tpl_vars['member']['email']; ?>
" class="istyle"/> 已验证
                  <?php else: ?>
                   <input type="text"  name="email" size="20" maxlength="50" value="<?php echo $this->_tpl_vars['member']['email']; ?>
" class="istyle"/>
                   <input type="button" value="验证" onclick="verify_email();" class="buttons">
                  <?php endif; ?>
                  
                  </td>
                    <th height="30" align="right">手机：</th>
                    <td height="30" colspan="3"><input type="text" name="mobile" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['mobile']; ?>
" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">MSN：</th>
                  <td height="30"><input type="text" name="msn" size="20" maxlength="50" value="<?php echo $this->_tpl_vars['member']['msn']; ?>
" class="istyle"/></td>
                    <th height="30" align="right">QQ：</th>
                    <td height="30"><input type="text" name="qq" size="20" maxlength="20" value="<?php echo $this->_tpl_vars['member']['qq']; ?>
" class="istyle"/></td>
                </tr>
                <tr>
                    <th height="30" align="right">办公室电话：</th>
                  <td height="30"><input type="text" name="office_phone" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['office_phone']; ?>
" class="istyle"/></td>
                    <th height="30" align="right">住宅电话：</th>
                    <td height="30"><input type="text" name="home_phone" size="20" maxlength="40" value="<?php echo $this->_tpl_vars['member']['home_phone']; ?>
" class="istyle"/></td>
                </tr>
            </tbody>
        </table>
        <div style="padding-top: 10px; text-align:center"><input type="submit" name="dosubmit" id="dosubmit" value="确 定" class="buttons"/>
          &nbsp;
        <input type="reset" name="reset" value="重 置" onclick="resetDom();" class="buttons"/>
        <br /><br /></div>
      </form>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
  </div>
  
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/scripts/check.js"></script>
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