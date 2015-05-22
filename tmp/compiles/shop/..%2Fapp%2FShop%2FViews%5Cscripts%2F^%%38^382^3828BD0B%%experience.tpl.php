<?php /* Smarty version 2.6.19, created on 2014-10-30 13:25:48
         compiled from member/experience.tpl */ ?>
<div class="member">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
    <div class="memberddbg">
	 <p>	您的当前经验值为：<span class="highlight" style="color:#D52319;"><?php echo $this->_tpl_vars['experience']; ?>
</span>
	 </p>
	</div>
	<div style="margin-top:11px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_experience.png"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				 	<thead>
					<tr>
						<th>变动时间</th>
						<th>经验值</th>
						<th>经验值变动</th>
						<th>备注</th>
					</tr>
					</thead>
					<tbody >
						<?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
						<tr>
							<td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
							<td><?php echo $this->_tpl_vars['info']['experience_total']; ?>
</td>
							<td><?php echo $this->_tpl_vars['info']['experience']; ?>
</td>
							<td><?php echo $this->_tpl_vars['info']['remark']; ?>
</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
				 </tbody>
				</table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
  </div>
</div>