<?php /* Smarty version 2.6.19, created on 2014-10-24 15:01:22
         compiled from member/pointlist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/pointlist.tpl', 33, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
		<span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">截止日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="todate" id="todate" size="15" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>	
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="<?php echo $this->_tpl_vars['param']['nick_name']; ?>
" size="15" />
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>'pointlist','do'=>'search',)));?>','ajax_search')"/>
		</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title"> [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'pointlist',)));?>')">积分变动历史</a> ]    |        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"point-frequency",)));?>')">积分变动频率查询</a> ] (此查询为近30天内的记录) </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户名</td>
			<td>用户昵称</td>
            <td>积分变动时间</td>
            <td>积分</td>
			<td>积分变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['pointlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['point'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['point']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['point']):
        $this->_foreach['point']['iteration']++;
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['member']['user_id']; ?>
">
            <td><?php echo $this->_tpl_vars['point']['member_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['point']['user_name']; ?>
</td>
			<td><?php echo $this->_tpl_vars['point']['nick_name']; ?>
</td>
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