<?php /* Smarty version 2.6.19, created on 2014-10-30 13:47:53
         compiled from member/password.tpl */ ?>
<div class="member">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
	<div style="margin-top:11px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_password.png"></div>
            <form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" onsubmit="return passwordSubmit()" target="ifrmSubmit">
            <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
                <tbody>
                    <?php if (! $this->_tpl_vars['member']['setPwd']): ?>
                    <tr >
                        <th width="14%" height="35" align="right"><span class="red">*</span>原密码&nbsp;&nbsp;&nbsp; </th>
                      <td width="86%" height="35"><input type="password" name="old_password" size="20" maxlength="20" class="istyle" /></td>
                    </tr>
                    <?php endif; ?>
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
		 <?php if ($this->_tpl_vars['member']['setPwd']): ?> 
			     <p class="text-mid"><font color="#FF9933"> 您现在的登录用户名是: <?php echo $this->_tpl_vars['member']['user_name']; ?>
 修改新密码以后可直接从 www.kenfeng.com登录！</font></p>
        <?php endif; ?>
  </div>
</div>

<iframe src="about:blank" style="width:0px;height:0px;" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script>
function passwordSubmit(){
	<?php if (! $this->_tpl_vars['member']['setPwd']): ?>
	if($.trim($("#myForm input[name='old_password']").val())==''){
		alert('请输入原密码!');return false;
	}
	<?php endif; ?>
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