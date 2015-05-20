<?php /* Smarty version 2.6.19, created on 2014-10-30 12:36:35
         compiled from member/money.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/money.tpl', 26, false),)), $this); ?>
<div class="member">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="memberright">
		<div class="member-box">
			<div class="mb-t"> 账户余额 </div>
			<div class="mb-c">
				<p class="fl">您的账户余额为：<span>￥<font color="#D52319" size="4"><?php echo $this->_tpl_vars['money']; ?>
</font></span></p>
				<!--<a class="fr btn-mem" href="/member/charge">充值</a>-->
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="height: 10px;overflow: hidden;"></div>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/inc-amount-tab.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
			<thead>
				<tr>
					<th>变动时间</th>
					<th>账户余额</th>
					<th>余额变动</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>
				<?php $_from = $this->_tpl_vars['moneyInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['money']):
?>
				<tr>
					<td><?php echo ((is_array($_tmp=$this->_tpl_vars['money']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
					<td><?php echo $this->_tpl_vars['money']['money_total']; ?>
</td>
					<td><?php echo $this->_tpl_vars['money']['money']; ?>
</td>
					<td align="left"><?php echo $this->_tpl_vars['money']['note']; ?>
</td>
				</tr>
				<?php endforeach; endif; unset($_from); ?>
			</tbody>
		</table>
		<div class="page_nav">
			<?php echo $this->_tpl_vars['pageNav']; ?>

		</div>
	</div>
	<div style="clear: both;"></div>
</div>
