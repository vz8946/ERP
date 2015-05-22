<?php /* Smarty version 2.6.19, created on 2014-10-28 10:02:35
         compiled from message/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'message/index.tpl', 14, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" onsubmit="return check();" action="/admin/message/index">
<div>
    <span style="float:left">开始日期：
        <input type="text"  value="<?php echo $this->_tpl_vars['params']['start_ts']; ?>
" id="start_ts"  name="start_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">
        截止日期：<input  type="text"  value="<?php echo $this->_tpl_vars['params']['end_ts']; ?>
" id="end_ts"  name="end_ts"   class="Wdate"   onClick="WdatePicker()" >
    </span>
    <span style="margin-left:10px">信息类型：
        <select name="type">
        <option value="">请选择</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['search_option']['message_type'],'selected' => $this->_tpl_vars['params']['type']), $this);?>

        </select>
    </span>
</div>
<input type="submit" name="dosearch" value="查询" />
</div>
</form>
</div>
<div class="title">信息列表</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加站内信</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>信息ID</td>
            <td>信息类型</td>
            <th>发送给谁</th>
            <td>标题</td>
            <td>创建</td>
			<td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['infos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['info']):
?>
        <tr>
            <td><?php echo $this->_tpl_vars['info']['message_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['message_type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['to_whos']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['title']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_by']; ?>
</td>
            <td><?php echo $this->_tpl_vars['info']['created_ts']; ?>
</td>
            <td><a onclick="openDiv('/admin/message/view/message_id/<?php echo $this->_tpl_vars['info']['message_id']; ?>
','ajax','查看站内消息 ',750,350)" href="javascript:fGo()">查看</a></td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>