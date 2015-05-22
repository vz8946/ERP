<?php /* Smarty version 2.6.19, created on 2014-11-14 13:12:42
         compiled from flow_top.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'flow_top.tpl', 7, false),)), $this); ?>
<div class="topnav">
<div class="wbox clearfix">
  <p style="padding-left: 0px;" class="login_info fl">
	<span style="padding-left: 0px;" class="l welcome" id="user_login_span">
	<span class="fs13">
	<span class="l">
	<?php $this->assign('h', ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%H') : smarty_modifier_date_format($_tmp, '%H'))); ?>
	<?php if ($this->_tpl_vars['h'] < 9): ?>早上好<?php elseif ($this->_tpl_vars['h'] < 12): ?>上午好 <?php elseif ($this->_tpl_vars['h'] < 13): ?>中午好 <?php elseif ($this->_tpl_vars['h'] < 17): ?>下午好<?php else: ?>晚上好<?php endif; ?>,欢迎来到垦丰商城 ! 
	</span>
	
	<?php if ($this->_tpl_vars['auth']): ?>
	<a href="/member" style="padding:0 4px;" id="glob-user"><?php echo $this->_tpl_vars['auth']['user_name']; ?>
</a>  
	<a class="blue" href="/logout.html">[退出]</a> <!--<a href="/member">我的帐户</a>-->
	<?php else: ?>
    <a id="glob-user" style="padding:0 4px;" class="blue" href="/login.html">[请登录]</a>
	<a style="padding:0 4px;" class="orange" href="/reg.html">[免费注册]</a>
   <?php endif; ?>	
	</span>	
	</span>
</p>
<div class="topmenu fr">
	<ul>
	    <li class="">
			<a rel="nofollow" target="_blank" href="/help">帮助中心&nbsp;</a>
		</li>
	</ul>
</div>
</div>
</div>