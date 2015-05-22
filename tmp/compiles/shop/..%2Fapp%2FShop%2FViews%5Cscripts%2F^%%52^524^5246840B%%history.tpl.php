<?php /* Smarty version 2.6.19, created on 2014-10-30 10:40:47
         compiled from goods/history.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'goods/history.tpl', 8, false),array('modifier', 'replace', 'goods/history.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['history']): ?>
<ul class="hislist">

	<?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<li class="clearfix">
		<div class="img">
			<div class="wh60 verticalPic">
				<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_380_380.') : smarty_modifier_replace($_tmp, '.', '_380_380.')); ?>
" alt="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
" width="60" height="60"></a>
			</div>
		</div>
		<div class="txt">
			<p class="title">
				<a href="/b-<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['as_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'jiankang') : smarty_modifier_default($_tmp, 'jiankang')); ?>
/detail<?php echo $this->_tpl_vars['v']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['v']['goods_name']; ?>
"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
			</p>
			<p class="Sprice">
				￥<span><?php echo $this->_tpl_vars['v']['price']; ?>
</span>
			</p>
		</div>
	</li>
	<?php endforeach; endif; unset($_from); ?>							
</ul>
<?php else: ?>
	<div style="padding: 10px;">暂无浏览记录！</div>
<?php endif; ?>