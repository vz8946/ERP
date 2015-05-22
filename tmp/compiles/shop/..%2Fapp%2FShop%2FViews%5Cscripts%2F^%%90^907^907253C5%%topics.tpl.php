<?php /* Smarty version 2.6.19, created on 2014-10-30 11:02:01
         compiled from page/topics.tpl */ ?>

<div class="discount">
<!-- 主题促销广告-->
<div class="disc_banner">
  <div style="margin: auto; width: 990px">
    <div class="bg-jpzb"> &nbsp;</div>
    <div class="jpzb-pd"> <a id="top" name="top"></a>
	  <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/660048.jpg" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/660048.jpg"></div>
      <div id="jptj-index02"> <img width="990" height="70" alt="" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-304.jpg" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-304.jpg"></div>
      <div id="jptj-index04"> <img width="990" height="70" alt="" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-305.jpg" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-305.jpg"></div>
      <div id="jptj-index05"> <img width="990" height="70" alt="" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-306.jpg" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/jptj-index-306.jpg"></div>
   
      <a id="f1" name="top"></a>
      <div id="jptj-index07"> <img width="990" height="47" alt="" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/670054.jpg" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/670054.jpg"></div>
    </div>
    <div class="mainbox"> <span class="text-1"></span>
	<!--
    <div style="margin-left:10px;"><a href="/zt/detail-36.html" target="_blank"><img width="970" height="172" lazy_src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/Public/img/index/bandcomm/ztadv.jpg"></a></div>
	-->
      <div class="box-content">
      	<?php $_from = $this->_tpl_vars['lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
      		<div class="content-left"><a href="/zt/detail-<?php echo $this->_tpl_vars['vo']['id']; ?>
.html" target="_blank"><img width="480" height="172" _src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['vo']['imgUrl']; ?>
" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['vo']['imgUrl']; ?>
"></a></div>
      	<?php endforeach; endif; unset($_from); ?>
      </div>
    </div>
  </div>
  <!-- end 主题促销广告-->
  <div class="discList clearfix promote"> </div>
</div>
