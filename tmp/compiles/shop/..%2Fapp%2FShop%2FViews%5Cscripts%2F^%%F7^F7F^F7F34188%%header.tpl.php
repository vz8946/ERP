<?php /* Smarty version 2.6.19, created on 2014-10-30 10:40:46
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'widget', 'header.tpl', 22, false),)), $this); ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">	
		<title><?php if ($this->_tpl_vars['page_title']): ?><?php echo $this->_tpl_vars['page_title']; ?>
<?php else: ?>垦丰-电商管理平台<?php endif; ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<meta name="baidu-site-verification" content="7tdg1Kfqym" />
		<meta name="Keywords" content="<?php if ($this->_tpl_vars['page_keyword']): ?><?php echo $this->_tpl_vars['page_keyword']; ?>
<?php else: ?>垦丰-电商管理平台<?php endif; ?>" />
		<meta name="Description" content="<?php if ($this->_tpl_vars['page_description']): ?><?php echo $this->_tpl_vars['page_description']; ?>
<?php else: ?>垦丰-电商管理平台<?php endif; ?>" />		
		<link type="image/x-icon" href="<?php echo $this->_tpl_vars['_static_']; ?>
/images/home.ico" rel="Shortcut Icon">	
		<link type="text/css" href="<?php echo $this->_tpl_vars['_static_']; ?>
/css/css.php?t=css&f=base.css,header.css,jcl/skins/default/skin.css<?php echo $this->_tpl_vars['css_more']; ?>
&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.css" rel="stylesheet" />
		<script>var static_url='<?php echo $this->_tpl_vars['_static_']; ?>
',img_url='<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
',cur_time= '<?php echo time(); ?>
';</script>
		<script src="<?php echo $this->_tpl_vars['_static_']; ?>
/js/js.php?t=js&f=jquery.js,jcl/jquery.jcarousel.min.js,JSmart.js,common.js,header.js<?php echo $this->_tpl_vars['js_more']; ?>
&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.js" type="text/jscript"></script>
	</head>
		<!--顶部导航-->
		<div class="topnav" id="topnav">
			<div class="<?php if ($this->_tpl_vars['is_index_page']): ?>wbox_1200<?php else: ?>wbox990<?php endif; ?>">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/header_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>
		</div>
		<?php if ($this->_tpl_vars['cur_position'] != 'member' && $this->_tpl_vars['cur_position'] != 'help'): ?>	
	      <?php echo smarty_function_widget(array('class' => 'AdvertWidget','id' => '1'), $this);?>

	    <?php endif; ?>
		<!-- 页头-->
		<div class="<?php if ($this->_tpl_vars['is_index_page']): ?>wbox_1200<?php else: ?>wbox990<?php endif; ?> header">
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/header_middle.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		
		<div class="mainnavbox">
		  <div class="<?php if ($this->_tpl_vars['is_index_page']): ?>wbox_1200<?php else: ?>wbox990<?php endif; ?>">
		     <div class="mallCategory">
		          <div class="mallSort"><a class="sortLink s_hover"><s></s></a>
		            <!--所有商品分类-->
		            <div class="sort" <?php if ($this->_tpl_vars['is_index_page']): ?>id="index-cat-menu" <?php else: ?> id="cat-menu"  style="display:none;"<?php endif; ?>>	            
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/catnav.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
		            <!--end 所有商品分类-->
		         </div>
		     </div>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_library/header_nav.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		    <!-- <div class="rightnav fr">
		         <ul>
		           <li><a title="优品" href="/zt/detail-65.html" target="_blank">精品优品</a></li>
		           <li><a title="" href="/zpbz.html" target="_blank">测试专题</a></li>
		         </ul>
		      </div>-->
		  </div>
		</div>
		<!--end 页头-->