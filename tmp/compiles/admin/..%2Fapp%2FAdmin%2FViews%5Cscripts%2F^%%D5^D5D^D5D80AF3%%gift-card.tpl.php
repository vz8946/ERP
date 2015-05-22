<?php /* Smarty version 2.6.19, created on 2014-11-01 14:16:05
         compiled from member/gift-card.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/gift-card.tpl', 39, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
" size="15" />
			
			卡号 <input type="text" name="card_sn" value="<?php echo $this->_tpl_vars['param']['card_sn']; ?>
" size="15" />
			
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>"gift-card",'do'=>'search',)));?>','ajax_search')"/>
		</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">会员礼品卡信息查询  </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
		    <td>用户ID</td>
			<td>用户名</td>
			<td>卡号</td>
			<td>面值</td>
			<td>剩余金额</td>
			<td>生成时间</td>
			<td>结束日期</td>
			<td>使用时间</td>
			<td>是否过期</td>
			<td>状态</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['gift_card_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['list']):
        $this->_foreach['list']['iteration']++;
?>
        <tr >
            <td><?php echo $this->_tpl_vars['list']['user_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['list']['user_name']; ?>
</td>
			<td><?php echo $this->_tpl_vars['list']['card_sn']; ?>
</td>
			<td><?php echo $this->_tpl_vars['list']['card_price']; ?>
</td>
			<td><?php echo $this->_tpl_vars['list']['card_real_price']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
            <td><?php echo $this->_tpl_vars['list']['end_date']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['using_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
            <td><?php if ($this->_tpl_vars['curtime'] > $this->_tpl_vars['list']['end_date']): ?><font color="#FF0000">已过期</font><?php else: ?><font color="#009900">未过期</font><?php endif; ?></td>
            <td>
              <?php if ($this->_tpl_vars['list']['status'] == 0): ?>
                <font color="#009900">有效</font>
              <?php elseif ($this->_tpl_vars['list']['status'] == 1): ?>
                <font color="#FF0000">无效</font>
              <?php elseif ($this->_tpl_vars['list']['status'] == 2): ?>
                <font color="#FF0000">未激活</font>
              <?php endif; ?>
			 </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>

<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
</div>
<script>
function multiDelete()
{
    checked = multiCheck($('table'),'ids',$('doDelete'));
    if (checked != '') {
        reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>', checked);
    }
}
</script>
<?php endif; ?>