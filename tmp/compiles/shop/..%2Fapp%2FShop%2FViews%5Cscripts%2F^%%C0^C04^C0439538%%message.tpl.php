<?php /* Smarty version 2.6.19, created on 2014-10-30 12:37:10
         compiled from member/message.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'member/message.tpl', 11, false),array('modifier', 'date_format', 'member/message.tpl', 32, false),)), $this); ?>
<div class="member">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
	<div style="margin-top:10px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_message.png"></div>

            <form action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" name="formMsg" id="formMsg" onsubmit="return messageSubmit()" target="ifrmSubmit">
                <table width="100%" cellspacing="0" cellpadding="0" border="0"  class="table_re">
                    <tr>
                        <th>留言类型</th>
                        <td height="40"><?php echo smarty_function_html_radios(array('name' => 'msg_type','options' => $this->_tpl_vars['msgType'],'checked' => 0,'separator' => ' '), $this);?>
</td>
                    </tr>
                    <tr>
                        <th>留言内容</th>
                        <td height="160"><textarea name="msg_content" cols="50" rows="4" wrap="virtual"></textarea></td>
                    </tr>
                    <tr><td>&nbsp;</td>
                        <td height="50"><input type="submit" name="dosubmit" id="dosubmit" value="提交留言" class="buttons2"/></td>
                    </tr>
                </table>
            </form>
    <div id='message'>
            <?php if ($this->_tpl_vars['messageInfo']): ?>
            <div class="remind-txt text-left"><strong>历史留言</strong></div>
            <table width="754" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php $_from = $this->_tpl_vars['messageInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
                    <tr>
                        <td style="background: #f2f2f2;padding:6px 10px;border-top:1px solid #fff;border-bottom:1px solid #dfdfdf;" align="left"><?php echo $this->_tpl_vars['message']['content']; ?>
</td>
                    </tr>
                    <tr>
                        <td height="20px"  style="background: #f9f9f9;border-top:1px solid #fff;border-bottom:1px solid #ccc;"><?php if ($this->_tpl_vars['type'] == 'shop'): ?><div style="float: left">留言类型：<?php echo $this->_tpl_vars['message']['type']; ?>
</div><?php endif; ?><div style="float: right;color:#ff8400;"><?php echo ((is_array($_tmp=$this->_tpl_vars['message']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
&nbsp;</div></td>
                    </tr>
                    <?php if (! empty ( $this->_tpl_vars['message']['reply'] )): ?>
                    <tr>
                        <td valign="top" style="padding:5px;background:#e9e9e9" align="left"><span style="margin:10px; "><?php echo $this->_tpl_vars['message']['reply']; ?>
</span></td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="border-bottom:#fff solid 3px; background:#e9e9e9"><div style="float: right"><?php echo ((is_array($_tmp=$this->_tpl_vars['message']['reply_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
&nbsp;&nbsp;管理员: <?php echo $this->_tpl_vars['message']['admin']; ?>
&nbsp;&nbsp;回复&nbsp;</div></td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?>
                </tbody>
            </table>
            <div class="page_nav" style="padding-top:10px"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
            <?php endif; ?>
<?php if (! $this->_tpl_vars['inner']): ?>
<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>
<script>
function messageSubmit(){
	var content=$.trim($('#formMsg textarea').val());
	if(content=='' || content.length>255){
		alert('请输入留言内容\n留言内容必须在255个字以内!');
		return false;
	}
	$('#dosubmit').attr('value','提交中..');
	$('#dosubmit').attr('disabled',true);
}
</script>
<?php endif; ?>

  </div>
</div>

</div>











