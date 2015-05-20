<?php /* Smarty version 2.6.19, created on 2014-10-30 11:02:08
         compiled from help/left.tpl */ ?>
<div class="help-left">
    	<div class="help-title">帮助中心</div>
    	<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    	<div class="help-parent-menu"><?php echo $this->_tpl_vars['item']['cat_name']; ?>
</div>
    	<div class="help-child-menu">
	    	<ul>
	    		<?php $_from = $this->_tpl_vars['item']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
		    	<li>
		    		<span class="help-ico"></span><a title="<?php echo $this->_tpl_vars['vo']['title']; ?>
" href="/help/detail-<?php echo $this->_tpl_vars['vo']['article_id']; ?>
.html"><?php echo $this->_tpl_vars['vo']['title']; ?>
</a>
		    	</li>
		    	<?php endforeach; endif; unset($_from); ?>
		    </ul>
	    </div>
	    <?php endforeach; endif; unset($_from); ?>
</div>