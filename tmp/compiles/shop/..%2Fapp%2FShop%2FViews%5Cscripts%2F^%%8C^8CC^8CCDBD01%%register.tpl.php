<?php /* Smarty version 2.6.19, created on 2014-10-30 13:52:48
         compiled from auth/register.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="/Public/css/register.css" rel="stylesheet">
<div class="container find_b">
                <div class="regSuccess">
                    <div class="h3_title">
                        <h3 class="t6">注册新用户</h3>
                    </div>
                   <?php if ($this->_tpl_vars['is_success'] == 'YES'): ?>
                   		<h1></h1>
                    <p>• 您的垦丰商城用户名为 <?php echo $this->_tpl_vars['username']; ?>
</p>
                   <?php else: ?>
						<p><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/error_icon.gif" style="margin-left:40px;"/></p>
						<p>•错误信息：<span style="color:blue;"> <?php echo $this->_tpl_vars['message']; ?>
</span></p>
					<?php endif; ?>
                    
					<p>• 页面将在 <span id="totalSecond" style="color:red;font-size:16px;">5</span> 秒钟后跳转至上一次操作页面。</p>


                    <div class="btnBox" style="position:relative;">
                        <a href="/"  ><span  class="btns w78px" style="background: url(/Public/css/../img/register_btn1.png) no-repeat scroll 0 0 transparent;color: #FFFFFF; display: inline-block;font-size: 14px;font-weight: bold;height: 30px;line-height: 30px;padding-top: 0;text-align: center; width:111px;cursor:pointer;">返回首页</span></a>
                        <a href="/member" ><span  class="btns w78px" style="background: url(/Public/css/../img/register_btn1.png) no-repeat scroll 0 0 transparent;color: #FFFFFF; display: inline-block;font-size: 14px;font-weight: bold;height: 30px;line-height: 30px;padding-top: 0;text-align: center; width:111px;cursor:pointer;">进入会员专区</span></a>
                    </div>
                </div>
</div>
<script language="JavaScript" type="text/javascript">
<?php if ($this->_tpl_vars['is_success'] == 'YES'): ?>
delayURL("<?php echo $this->_tpl_vars['goto']; ?>
");
<?php else: ?>
delayURL("<?php echo $this->_tpl_vars['refer']; ?>
");
<?php endif; ?>
function delayURL(url) {
		var delay = document.getElementById("totalSecond").innerHTML;
		if(delay > 0) {
			delay--;
			document.getElementById("totalSecond").innerHTML = delay;
		} else {
			window.top.location.href = url;
		}
		setTimeout("delayURL('" + url + "')", 1000);
}
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>