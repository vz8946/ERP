<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/special_qixi_floor/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'wdgtpl/special_qixi_floor/default.tpl', 7, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
<div class="box" <?php if ($this->_tpl_vars['k']%3 == 0): ?>style="margin-left: 0px;"<?php endif; ?>>
	<div width="324" height="247" style="width: 324px;height: 274px;overflow: hidden;text-align: center;">
		<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
			<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" style="display: inline-block;margin: 0px auto;" height="272" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" />
		<?php else: ?>
		<a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank" style="display: inline-block;margin: 0px auto;">
			<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" height="272" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" />
		</a>
		<?php endif; ?>
	</div>
	<div class="text-1">
		<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
			<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>

		<?php else: ?>
			<a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>
</a>
		<?php endif; ?>
	</div>
	<div class="text-1"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])); ?>
</div>
	<div style="padding-left: 10px; height: 39px; line-height: 39px;">
		<span class="text-2">
			<?php if ($this->_tpl_vars['tip_price_name']['0']['title']): ?>
				<?php echo $this->_tpl_vars['tip_price_name']['0']['title']; ?>

			<?php else: ?>
				垦丰价
			<?php endif; ?>
			：¥</span> <span class="text-3"
			<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>style="color:gray;"<?php endif; ?>
			>
				<?php if ($this->_tpl_vars['certain_price']['0']['title']): ?><?php echo $this->_tpl_vars['certain_price']['0']['title']; ?>
<?php else: ?><?php echo $this->_tpl_vars['v']['goods']['price']; ?>
 <?php endif; ?>
			</span> <span
			style="float: right; padding-top: 6px;"><a
			href="javascript:void(0);" 
			<?php if ($this->_tpl_vars['v']['url'] != '#'): ?>onclick="addCart('<?php echo $this->_tpl_vars['v']['goods']['goods_sn']; ?>
')"<?php endif; ?>
			><img
				src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/special/qixi/buy-button.png" width="105" height="27" /></a></span>
	</div>
	<div
		style="width: 324px; height: 54px; background: url(/images/special/qixi/sale-infor.png) no-repeat;"
		class="text-4">
		<ul>
			<li class="sale-infor">￥<?php echo $this->_tpl_vars['v']['goods']['market_price']; ?>
</li>
			<li class="sale-infor"><?php echo $this->_tpl_vars['v']['goods']['saleoff']; ?>
</li>
			<li class="sale-infor">￥<?php echo $this->_tpl_vars['v']['goods']['disprice']; ?>
</li>
		</ul>
	</div>
</div>
<?php endforeach; endif; unset($_from); ?>
<div style="clear: both;"></div>
