<?php /* Smarty version 2.6.19, created on 2014-10-30 12:30:30
         compiled from D:%5Cwamp%5Cwww%5Cjiankang%5Clib%5CWidget%5CAdvertWidget%5Chtml%5Cbanner.html */ ?>
<div  class="ad-banner" id="ad_<?php echo $this->_tpl_vars['ad_id']; ?>
" >
<?php if ($this->_tpl_vars['adlist'][0]['url']): ?>
 <a href="<?php echo $this->_tpl_vars['adlist'][0]['url']; ?>
" target="_blank">
   <img aid="<?php echo $this->_tpl_vars['adlist'][0]['id']; ?>
" alt="<?php echo $this->_tpl_vars['adlist'][0]['desc']; ?>
" title="<?php echo $this->_tpl_vars['adlist'][0]['desc']; ?>
" src="<?php echo $this->_tpl_vars['adlist'][0]['content']; ?>
"  height="<?php echo $this->_tpl_vars['board']['height']; ?>
"   width="<?php echo $this->_tpl_vars['board']['width']; ?>
" />
 </a>   
<?php else: ?>
   <img aid="<?php echo $this->_tpl_vars['adlist'][0]['id']; ?>
" alt="<?php echo $this->_tpl_vars['adlist'][0]['desc']; ?>
" title="<?php echo $this->_tpl_vars['adlist'][0]['desc']; ?>
" src="<?php echo $this->_tpl_vars['adlist'][0]['content']; ?>
"  height="<?php echo $this->_tpl_vars['board']['height']; ?>
"   width="<?php echo $this->_tpl_vars['board']['width']; ?>
" />
<?php endif; ?>
</div>