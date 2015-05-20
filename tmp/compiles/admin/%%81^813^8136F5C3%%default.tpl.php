<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:46
         compiled from wdgtpl/index_floor/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'wdgtpl/index_floor/default.tpl', 26, false),array('modifier', 'default', 'wdgtpl/index_floor/default.tpl', 26, false),array('modifier', 'cn_truncate', 'wdgtpl/index_floor/default.tpl', 34, false),)), $this); ?>
<div class="floor">
	<div class="col-l fl w2">
		<div class="col-l-1 fl" style="width: 222px;overflow: hidden;">
			<h3 style="background: <?php echo $this->_tpl_vars['bg_color']; ?>
;"><span><img alt="<?php echo $this->_tpl_vars['floor_icon']['0']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['floor_icon']['0']['img']; ?>
"/></span><?php echo $this->_tpl_vars['floor_name']; ?>
</h3>
			<div class="adv"><a href="<?php echo $this->_tpl_vars['floor_adv']['0']['url']; ?>
"><img alt="<?php echo $this->_tpl_vars['floor_adv']['0']['title']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['floor_adv']['0']['img']; ?>
" /></a></div>
			<div class="keys">
				<?php $_from = $this->_tpl_vars['floor_keys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
				<a style="background: <?php echo $this->_tpl_vars['bg_color']; ?>
;" href="<?php echo $this->_tpl_vars['v']['url']; ?>
"><?php echo $this->_tpl_vars['v']['title']; ?>
</a>
				<?php endforeach; endif; unset($_from); ?>
			</div>
		</div>
		<div class="col-l-2 fl tab floor-tab" style="width: 668px;padding-left:10px;">
			<ul class="tab-h">
				<?php if ($this->_tpl_vars['cat_name1']): ?><li><a href="#"><?php echo $this->_tpl_vars['cat_name1']; ?>
</a></li><?php endif; ?>
				<?php if ($this->_tpl_vars['cat_name2']): ?><li><a href="#"><?php echo $this->_tpl_vars['cat_name2']; ?>
</a></li><?php endif; ?>
				<?php if ($this->_tpl_vars['cat_name3']): ?><li><a href="#"><?php echo $this->_tpl_vars['cat_name3']; ?>
</a></li><?php endif; ?>
				<?php if ($this->_tpl_vars['cat_name4']): ?><li><a href="#"><?php echo $this->_tpl_vars['cat_name4']; ?>
</a></li><?php endif; ?>
			</ul>
			<div class="tab-c" style="border-color: <?php echo $this->_tpl_vars['bg_color']; ?>
;">
				<div>
					<ul class="floor-goods">
						<?php $_from = $this->_tpl_vars['goods1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li>
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
							<div class="pa fs1 fb c1" title="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])); ?>
">
								<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['goods_alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_alt']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_alt'])))) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 26, '') : smarty_modifier_cn_truncate($_tmp, 26, '')); ?>

							</div>
							<div class="pt">
								<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
									<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>

								<?php else: ?>
									<a class="c3" href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
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
				</div>
				<div>
					<ul class="floor-goods">
						<?php $_from = $this->_tpl_vars['goods2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li>
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
									<a class="c3" href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
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
				</div>
				<div>
					<ul class="floor-goods">
						<?php $_from = $this->_tpl_vars['goods3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li>
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
									<a class="c3" href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
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
				</div>
				<div>
					<ul class="floor-goods">
						<?php $_from = $this->_tpl_vars['goods4']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
						<li>
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
									<a class="c3" href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
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
				</div>
			</div>
		</div>
	</div>
	<div class="col-r fr w3">
		<div class="goods-hot">
			<h3 style="background: <?php echo $this->_tpl_vars['bg_color']; ?>
;"><i></i>本周热销排行</h3>
			<ul>
				<?php $_from = $this->_tpl_vars['goods5']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
				<li>
					<?php if ($this->_tpl_vars['k'] == 0): ?>
					<div class="p-detail">
					<?php else: ?>
					<div class="p-detail" style="display: none;">
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
						<div class="pt">
							<?php if ($this->_tpl_vars['v']['url'] == '#'): ?>
								<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])); ?>

							<?php else: ?>
								<a class="c3" href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods_url'])); ?>
" target="_blank"><?php echo $this->_tpl_vars['v']['goods_name']; ?>
</a>
							<?php endif; ?>
						</div>
						<div class="pp">
							<span class="prp c2 fs3">￥<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['price'])); ?>
</span>
						</div>										
					</div>
					<div class="p-title" <?php if ($this->_tpl_vars['k'] == 0): ?>style="display: none;"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['goods_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['goods']['goods_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['goods']['goods_name'])))) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 50) : smarty_modifier_cn_truncate($_tmp, 50)); ?>
</div>
				</li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
			
			<div style="height: 180px;overflow: hidden;">
				<a href="<?php echo $this->_tpl_vars['floor_adv']['1']['url']; ?>
"><img alt="<?php echo $this->_tpl_vars['floor_adv']['1']['title']; ?>
"  _src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['floor_adv']['1']['img']; ?>
"/></a>
			</div>
			
		</div>
	</div>
</div>
<style>
#wgt-<?php echo $this->_tpl_vars['__sys_wtpl_name']; ?>
 .tab-h-c a{
	background: <?php echo $this->_tpl_vars['bg_color']; ?>
;
}	
</style>