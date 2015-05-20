<?php /* Smarty version 2.6.19, created on 2014-11-01 10:57:28
         compiled from member/moneylist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/moneylist.tpl', 31, false),)), $this); ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
     <span style="float:left;line-height:18px;">开始日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="fromdate" id="fromdate" size="11" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"/></span>
<span style="float:left;line-height:18px;">截止日期：<input  class="Wdate" onClick="WdatePicker()" type="text" name="todate" id="todate" size="11" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"/></span>	
        <span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="<?php echo $this->_tpl_vars['param']['nick_name']; ?>
" size="15" />
        <span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>'moneylist','do'=>'search',)));?>','ajax_search')"/>
    </form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">账户余额变动历史</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户昵称</td>
            <td>变动时间</td>
            <td>余额</td>
			<td>余额变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['moneylist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['money'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['money']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['money']):
        $this->_foreach['money']['iteration']++;
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['member']['user_id']; ?>
">
            <td><?php echo $this->_tpl_vars['money']['member_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['money']['nick_name']; ?>
</td>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['money']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
            <td><?php echo $this->_tpl_vars['money']['money_total']; ?>
</td>
            <td><?php echo $this->_tpl_vars['money']['money']; ?>
</td>
            <td><?php echo $this->_tpl_vars['money']['note']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>
</div>