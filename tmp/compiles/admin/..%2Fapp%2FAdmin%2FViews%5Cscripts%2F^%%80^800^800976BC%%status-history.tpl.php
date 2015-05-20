<?php /* Smarty version 2.6.19, created on 2014-10-27 14:42:42
         compiled from goods/status-history.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'goods/status-history.tpl', 32, false),)), $this); ?>
<style type="text/css">
.dotline {
border-bottom-color:#666666;
border-bottom-style:dotted;
border-bottom-width:1px;
}
</style>
<form id="myform">
<br>
  <table width="100%" border="0">
        <tr bgcolor="#F0F1F2">
          <td width="100">　商品名称：</td>
          <td><?php echo $this->_tpl_vars['goods']['goods_name']; ?>
</td>
        </tr>
  </table>
  <br>
  <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
      <td>操作状态</td>
      <td>备注</td>
      <td>管理员</td>
      <td>操作时间</td>
    </tr>
    </thead>
    <?php if ($this->_tpl_vars['history']): ?>
    <?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <tr>
      <td><?php echo $this->_tpl_vars['item']['status']; ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['remark']; ?>
</td>
      <td><?php echo $this->_tpl_vars['item']['admin_name']; ?>
</td>
      <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['op_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
  </table>
<br>