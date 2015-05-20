<?php /* Smarty version 2.6.19, created on 2014-10-22 22:31:13
         compiled from category/getcat.tpl */ ?>
<?php if (! empty ( $this->_tpl_vars['cats'] )): ?>
<select name="sel_cat_id" onchange="if(this.value&gt;0){ajax_get_cat('<?php echo $this -> callViewHelper('url', array(array('action'=>'getcat',)));?>',this.value);this.disabled=true;}">
      <?php if ($this->_tpl_vars['pid'] == 0): ?>
      <option value="0" selected>设为大类</option>
      <?php else: ?>
      <option value="-1" selected>请选择</option>
      <?php endif; ?>
      <?php $_from = $this->_tpl_vars['cats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?>
      <?php if ($this->_tpl_vars['cat']['cat_id'] != $this->_tpl_vars['cat_id']): ?>
      <option value="<?php echo $this->_tpl_vars['cat']['cat_id']; ?>
"><?php echo $this->_tpl_vars['cat']['cat_name']; ?>
</option>
      <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
</select>
<?php endif; ?>