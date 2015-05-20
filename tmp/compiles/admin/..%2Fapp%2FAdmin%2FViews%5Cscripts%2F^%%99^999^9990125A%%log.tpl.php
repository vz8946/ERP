<?php /* Smarty version 2.6.19, created on 2014-10-23 09:11:28
         compiled from auth/log.tpl */ ?>

<?php if (! $this->_tpl_vars['param']['do']): ?>
<form name="searchForm" id="searchForm">
<div class="search">
管理员：<input type="text" name="admin_name" size="12" maxLength="50" value="<?php echo $this->_tpl_vars['param']['admin_name']; ?>
"/>
ip：<input type="text" name="login_ip" size="12" maxLength="50" value="<?php echo $this->_tpl_vars['param']['login_ip']; ?>
"/>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
</div>
</div>
</form>
<?php endif; ?>



<div class="title">管理员登录日志</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td >ID</td>
            <td >用户名</td>
            <td>登录时间</td>
            <td>登录IP</td>
			<td>登录地点</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['data']['try_id']; ?>
">
			<td><?php echo $this->_tpl_vars['data']['log_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['data']['admin_name']; ?>
</td>
			<td><?php echo $this->_tpl_vars['data']['login_time']; ?>
</td>
		    <td><?php echo $this->_tpl_vars['data']['login_ip']; ?>
</td>
            <td><?php echo $this->_tpl_vars['data']['ip_address']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
   <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>