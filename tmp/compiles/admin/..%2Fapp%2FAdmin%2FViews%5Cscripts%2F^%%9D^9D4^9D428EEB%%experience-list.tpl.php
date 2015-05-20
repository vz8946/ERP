<?php /* Smarty version 2.6.19, created on 2014-11-12 16:45:02
         compiled from member/experience-list.tpl */ ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
		<form name="searchForm" id="searchForm">
		<span style="float:left;line-height:18px;">开始日期：</span><span style="float:left;width:150px;line-height:18px;"><input type="text" name="start_ts" id="start_ts" size="15" value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">截止日期：</span><span style="float:left;width:150px;line-height:18px;"><input  type="text" name="end_ts" id="end_ts" size="15" value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>	
			<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="nick_name" value="<?php echo $this->_tpl_vars['params']['nick_name']; ?>
" size="15" />
			<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>"experience-list",'do'=>'search',)));?>','ajax_search')"/>
		</form>
</div>
<?php endif; ?>

<div id="ajax_search">
<div class="title">积分变动历史</div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户昵称</td>
            <td>经验值变动时间</td>
            <td>经验值</td>
			<td>经验值变动</td>
            <td>变动原因</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['member_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['info']['nick_name']; ?>
</td>
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