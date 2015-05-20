<?php /* Smarty version 2.6.19, created on 2014-10-23 09:46:00
         compiled from member/account-list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/account-list.tpl', 21, false),)), $this); ?>
<div id="account-list">
<div style="width:850px; float:left; background-color:#FFFFFF; border:0px; overflow:auto; padding-left:2px">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table" style="background-color: #fff">
        <thead>
        <tr>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">ID</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">变动时间</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">订单ID</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">变动原因</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x"><?php echo $this->_tpl_vars['accountName']; ?>
</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x"><?php echo $this->_tpl_vars['accountName']; ?>
变动</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">操作管理员</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">是否有效</td>
            <td style="background: url(/images/admin/table_thead.gif) repeat-x">无效的原因</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['accounts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['account'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['account']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['account']):
        $this->_foreach['account']['iteration']++;
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['goods']['goods_id']; ?>
">
            <td><?php echo $this->_tpl_vars['account']['id']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['account']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
            <td><?php echo $this->_tpl_vars['account']['batch_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['account']['note']; ?>
</td>
            <td><?php echo $this->_tpl_vars['account']['totalValue']; ?>
</td>
            <td><?php echo $this->_tpl_vars['account']['value']; ?>
</td>
            <td><?php echo $this->_tpl_vars['account']['admin_name']; ?>
</td>
            <td><?php if ($this->_tpl_vars['account']['disable'] == 0): ?>有效<?php else: ?><font color=red>无效</font><?php endif; ?></td>
            <td><?php echo $this->_tpl_vars['account']['disable_note']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div style="float:left"><input type="button" onclick="closeWin()" value=" 关闭 " /></div><div class="page_nav" style="padding:5px 2px 0px 0px"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
</div>