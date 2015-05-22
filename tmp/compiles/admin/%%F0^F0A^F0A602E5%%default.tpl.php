<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/index_goods_list1/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'wdgtpl/index_goods_list1/default.tpl', 12, false),array('modifier', 'default', 'wdgtpl/index_goods_list1/default.tpl', 12, false),)), $this); ?>
<ul class="index-goods-list1">
	<?php $_from = $this->_tpl_vars['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<?php if ($this->_tpl_vars['k'] == 0): ?>
	<li style="width: 221px;">
	<?php elseif ($this->_tpl_vars['k'] == 3): ?>
	<li style="width: 222px;margin-right: 0px;">
	<?php else: ?>
	<li style="width: 224px;">
	<?php endif; ?>
		<div class="pi">
			<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
				<?php echo smarty_function_html(array('alt' => ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])),'type' => 'img','w' => '175','h' => '175','height' => '175','src' => $this->_tpl_vars['v']['img'],'lazy' => 'Y'), $this);?>

			<?php else: ?>
			<a href="<?php if ($this->_tpl_vars['v']['url']): ?><?php echo $this->_tpl_vars['v']['url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['v']['goods_url']; ?>
<?php endif; ?>" target="_blank">
				<?php echo smarty_function_html(array('alt' => ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])),'type' => 'img','w' => '175','h' => '175','height' => '175','src' => $this->_tpl_vars['v']['img'],'lazy' => 'Y'), $this);?>

			</a>
			<?php endif; ?>
		</div>
		<div class="pa fs1 fb c1">
			<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])); ?>

		</div>
		<div class="pt">
			<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
				<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>

			<?php else: ?>
				<a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>
</a>
			<?php endif; ?>
		</div>
		<div class="pp">
			<span class="pmp fl">￥<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['market_price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['market_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['market_price'])); ?>
</span>
			<span class="prp fr c2 fs3">￥<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['price'])); ?>
</span>
		</div>
	</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>