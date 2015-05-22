<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:47
         compiled from wdgtpl/news_focus1/default.tpl */ ?>
<div id="slides1">
	<?php $_from = $this->_tpl_vars['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
    <div class="slide_wrap"><a href="<?php echo $this->_tpl_vars['v']['url']; ?>
"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
" alt="<?php echo $this->_tpl_vars['v']['title']; ?>
" width="399" height="300" /></a></div>
    <?php endforeach; endif; unset($_from); ?>
</div>
<div id="myController1">
    <span class="jPrev">&lt;</span>
	<?php $_from = $this->_tpl_vars['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
    <span class="jFlowControl1"><?php echo $this->_tpl_vars['k']+1; ?>
</span>
    <?php endforeach; endif; unset($_from); ?>
    <span class="jNext">&gt;</span>
</div>