<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:47
         compiled from wdgtpl/index_brand/default.tpl */ ?>
<script type="text/javascript">
$('#brand-list').ready(function(){
	$('#brand-jcl-<?php echo $this->_tpl_vars['__sys_wtpl_name']; ?>
').jcarousel({
		auto : 5,
		wrap: 'circular',
		scroll : 8
	}); 
});
</script>
<div id="brand-list" class="brand-list">
<ul id="brand-jcl-<?php echo $this->_tpl_vars['__sys_wtpl_name']; ?>
" class="jcarousel-skin-default" style="width: 100%;">
<?php $_from = $this->_tpl_vars['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kl'] => $this->_tpl_vars['v']):
?>
	<li>
		<a href="<?php echo $this->_tpl_vars['v']['url']; ?>
"><img alt="<?php echo $this->_tpl_vars['v']['title']; ?>
"  width="120" height="58" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['v']['img']; ?>
"/></a>
	</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</div>