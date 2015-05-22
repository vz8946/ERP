<?php /* Smarty version 2.6.19, created on 2014-10-30 12:36:37
         compiled from member/point.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/point.tpl', 24, false),)), $this); ?>
<div class="member">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
    <div class="memberddbg">
	 <p>	您现在的等级为：<?php echo $this->_tpl_vars['member']['rank_name']; ?>
，您的可用积分为：<span class="highlight" style="color:#D52319;"><?php echo $this->_tpl_vars['point']; ?>
</span>
				<form action="/member/update-rank"  method="post" id="form1">
					<input type="hidden" value="" name="torank" id="torank">
				</form>
	 </p>
	</div>
	<div style="margin-top:11px;"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_point.png"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
				 	<thead>
					<tr>
						<th>变动时间</th>
						<th>积分</th>
						<th>积分变动</th>
						<th>备注</th>
					</tr>
					</thead>
					<tbody >
						<?php $_from = $this->_tpl_vars['pointInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['point']):
?>
						<tr>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['point']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
							<td><?php echo $this->_tpl_vars['point']['point_total']; ?>
</td>
							<td><?php echo $this->_tpl_vars['point']['point']; ?>
</td>
							<td><?php echo $this->_tpl_vars['point']['note']; ?>
</td>
						</tr>
						<?php endforeach; endif; unset($_from); ?>
				 </tbody>
				</table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
  </div>
</div>
<script>
function updaterank(title, point, rank){
	$('#torank').val(rank);
    if(confirm('确认升级为['+title+'会员]吗，您将被扣除'+point+'积分')){
        $('#form1').submit();
    }
}
</script>