<?php /* Smarty version 2.6.19, created on 2014-10-30 12:37:12
         compiled from member/favorite.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'member/favorite.tpl', 20, false),)), $this); ?>
<div class="member">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
	<div style="margin-top:11px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_favorite.png"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr>
                        <th colspan="2">商 品</th>
                        <th >放入时间</th>
                        <th >单价</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $_from = $this->_tpl_vars['info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
                    <tr>
					    <td>
						<a href="/goods/show/id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">
						<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" width="65" height="65">
						</a></td>
						<td align="left"><a href="/goods/show/id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
" style="color:#707070;"><?php echo $this->_tpl_vars['data']['goods_name']; ?>
</a></td>
						<td><?php echo $this->_tpl_vars['data']['add_time']; ?>
</td>
						<td><?php echo $this->_tpl_vars['data']['price']; ?>
</td>
                        <td>
						<a href="/goods/show/id/<?php echo $this->_tpl_vars['data']['goods_id']; ?>
">购买</a>
						<a href="/goods/del-favorite/favorite_id/<?php echo $this->_tpl_vars['data']['favorite_id']; ?>
">删除</a>	
						</td>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?>
            </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
  </div>
</div>