<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/special_floor/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'wdgtpl/special_floor/default.tpl', 8, false),array('modifier', 'default', 'wdgtpl/special_floor/default.tpl', 15, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
<div class="topic-goods-item" <?php if (( $this->_tpl_vars['k']+1 ) % 4 == 0): ?>style="margin-right: 0px;"<?php endif; ?>>
	<div  class="imgs">
		<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
			<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" height="175" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" />
		<?php else: ?>
		<a href="<?php if ($this->_tpl_vars['v']['url']): ?><?php echo $this->_tpl_vars['v']['url']; ?>
<?php else: ?><?php echo $this->_tpl_vars['v']['goods_url']; ?>
<?php endif; ?>" target="_blank">
			<?php echo smarty_function_html(array('type' => 'img','src' => $this->_tpl_vars['v']['img'],'alt' => $this->_tpl_vars['v']['title'],'w' => 175,'h' => 175), $this);?>

		</a>
		<?php endif; ?>
	</div>
	
	<div class="info">
		
		<div class="sp"><span class="c1 fs1 fb"><?php if ($this->_tpl_vars['certain_price']['0']['title']): ?><?php echo $this->_tpl_vars['certain_price']['0']['title']; ?>
<?php else: ?><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['price'])); ?>
 <?php endif; ?></span> ￥</div>
		<div class="ga c1" style="height: 18px;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])); ?>
</div>
		<div class="op c3">原价：<span class="fl1"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['market_price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['market_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['market_price'])); ?>
￥</span>&nbsp;&nbsp;&nbsp;&nbsp;节省：<span class="c2"><?php echo $this->_tpl_vars['v']['disprice']; ?>
</span>￥</div>
		
		<div class="gt">
			<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
				<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>

			<?php else: ?>
				<a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>
</a>
			<?php endif; ?>
		</div>
		
		<div class="opt"><a class="btn" href="javascript:void(0);"
			<?php if ($this->_tpl_vars['v']['url'] != '#'): ?>onclick="addCart('<?php echo $this->_tpl_vars['v']['goods']['goods_sn']; ?>
')"<?php endif; ?>
		>立即购买</a></div>
		
	</div>
</div>
<?php endforeach; endif; unset($_from); ?>

<div style="clear: both;"></div>
