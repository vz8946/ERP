<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/special_floor1/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'wdgtpl/special_floor1/default.tpl', 10, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
<div class="topic-goods-item" <?php if (( $this->_tpl_vars['k']+1 ) % 2 == 0): ?>style="margin-right: 0px;"<?php endif; ?>>
	<div  class="imgs" style="float: left;">
		<table style="width: 100%;height: 100%;">
			<tr>
				<td>
					<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
						<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" height="230" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" />
					<?php else: ?>
					<a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank">
						<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" height="230" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" />
					</a>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="info">
		<div class="sp"><span class="c1 fs1 fb"><?php if ($this->_tpl_vars['certain_price']['0']['title']): ?><?php echo $this->_tpl_vars['certain_price']['0']['title']; ?>
<?php else: ?><?php echo $this->_tpl_vars['v']['goods']['price']; ?>
 <?php endif; ?></span> ￥</div>
		<div style="height: 15px;overflow: hidden;"></div>
		<div class="op c3">原价：<span class="fl1"><?php echo $this->_tpl_vars['v']['goods']['market_price']; ?>
￥</span>&nbsp;&nbsp;&nbsp;&nbsp;节省：<span class="c2"><?php echo $this->_tpl_vars['v']['goods']['disprice']; ?>
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
		<div class="ga c1" style="height: 18px;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])); ?>
</div>		
		<div class="opt"><a class="btn" href="javascript:void(0);"
			<?php if ($this->_tpl_vars['v']['url'] != '#'): ?>onclick="addCart('<?php echo $this->_tpl_vars['v']['goods']['goods_sn']; ?>
')"<?php endif; ?>
		>立即购买</a></div>
		
	</div>
</div>
<?php endforeach; endif; unset($_from); ?>
<div style="clear: both;"></div>