<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/index_focus/default.tpl */ ?>
<div id="slideadv" class="slideadv fl">
	<?php $_from = $this->_tpl_vars['link1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<?php if ($this->_tpl_vars['k'] == 0): ?>
	<div style="display: block;" class="adv">
	<?php else: ?>	
	<div style="display: none;" class="adv">
	<?php endif; ?>
		<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
			<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/loading.gif" _src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
"/>
		<?php else: ?>
			<a <?php if ($this->_tpl_vars['v']['is_new_win'] == 'Y'): ?> target="_blank" <?php endif; ?> name="<?php echo $this->_tpl_vars['v']['memo']; ?>
" <?php if ($this->_tpl_vars['v']['is_new_win'] == 'Y'): ?>target="_blank"<?php endif; ?> href="<?php echo $this->_tpl_vars['v']['url']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/loading.gif" _src="<?php echo $this->_tpl_vars['v']['img']; ?>
"/></a>
		<?php endif; ?>
	</div>
	<?php endforeach; endif; unset($_from); ?>
	<div class="num">
		<ul>
			<?php $_from = $this->_tpl_vars['link1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
			<?php if ($this->_tpl_vars['k'] == 0): ?>
			<li class="cur">
			<?php else: ?>	
			<li>
			<?php endif; ?>
			</li>
			<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
</div>

<div style="float: left;width: 288px;height: 358px;margin-left: 10px;overflow: hidden;border: 1px solid #eee;">
	<?php $_from = $this->_tpl_vars['link2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
	<?php if ($this->_tpl_vars['k'] == 0): ?>
	<div style="height: 120px;overflow: hidden;">
	<?php else: ?>
	<div style="height: 119px;overflow: hidden;padding-top: 1px;">
	<?php endif; ?>
		
		<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
			<img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/loading.gif" _src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
"/>
		<?php else: ?>
			<a <?php if ($this->_tpl_vars['v']['is_new_win'] == 'Y'): ?> target="_blank" <?php endif; ?> name="<?php echo $this->_tpl_vars['v']['memo']; ?>
" href="<?php echo $this->_tpl_vars['v']['url']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/loading.gif" _src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
"/></a>
		<?php endif; ?>
		
	</div>
	<?php endforeach; endif; unset($_from); ?>
</div>

<script>$(function(){
	$('#slideadv').find('.adv').find('.i').mouseenter(function(){
		$('#slideadv').css({background:'#000'});
		$(this).siblings().find('img').css({opacity:0.7});
	}).mouseout(function(){
		$(this).siblings().find('img').css({opacity:1});
	});
});</script>