<?php /* Smarty version 2.6.19, created on 2014-10-22 22:15:15
         compiled from msg/listgoods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'msg/listgoods.tpl', 55, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="title">商品留言管理</div>
<div class="content">
<div class="search">
    <form id="searchForm">
<div style="clear:both; padding-top:5px">
    <span style="float:left;line-height:18px;">开始日期：</span>
    <span style="float:left;width:100px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="12" value="<?php echo $this->_tpl_vars['param']['fromdate']; ?>
"  class="Wdate" onClick="WdatePicker()"/></span>
    <span style="float:left;line-height:18px;">结束日期：</span>
    <span style="float:left;width:100px;line-height:18px;"><input  type="text" name="todate" id="todate" size="12" value="<?php echo $this->_tpl_vars['param']['todate']; ?>
"  class="Wdate"  onClick="WdatePicker()"/></span>
    审核状态：
    <select name="status">
	  <option value="">请选择</option>
	  <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>未审核</option>
	  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>已审核</option>
	  <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已拒绝</option>
	</select>
	热点评论：
	<select name="is_hot">
	  <option value="">请选择</option>
	  <option value="1" <?php if ($this->_tpl_vars['param']['is_hot'] == '1'): ?>selected<?php endif; ?>>是</option>
	  <option value="0" <?php if ($this->_tpl_vars['param']['is_hot'] == '0'): ?>selected<?php endif; ?>>否</option>
	</select>
	商品名称：<input type="text" name="goods_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['goods_name']; ?>
"/>
    <input type="submit" name="dosearch" value=" 搜 索 "/>
    </div>	
    </form>
</div>
<form name="myForm" id="myForm">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td width="30">全选</td>
            <?php if ($this->_tpl_vars['type'] == 1): ?>
            <td>评价</td>
            <?php endif; ?>
            <td>用户</td>
            <td>商品名称</td>
            <?php if ($this->_tpl_vars['type'] == 1): ?>
			<td>热点</td>
            <?php endif; ?>
            <td width="200px">留言内容</td>
            <td>留言时间</td>
			<td>是否审核</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['msg_id']; ?>
">
        <td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['item']['goods_msg_id']; ?>
"/></td>
        <?php if ($this->_tpl_vars['type'] == 1): ?>
        <td>外 观：<?php echo $this->_tpl_vars['item']['cnt1']; ?>
<br>口感：<?php echo $this->_tpl_vars['item']['cnt2']; ?>
</td>
        <?php endif; ?>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 16, "...") : smarty_modifier_truncate($_tmp, 16, "...")); ?>
</td>
        <td><a href="javascript:fGo()" onclick="window.open('http://www.1jiankang.com/goods/show/id/<?php echo $this->_tpl_vars['item']['goods_id']; ?>
')"><?php echo $this->_tpl_vars['item']['goods_name']; ?>
</a></td>
		 <?php if ($this->_tpl_vars['type'] == 1): ?> <td ><?php if ($this->_tpl_vars['item']['is_hot'] == 1): ?> <font color="#FF3300">是 </font><?php else: ?>否<?php endif; ?></td>  <?php endif; ?>
        <td><textarea rows="3" cols="23" style="width:200px; height:80px;"><?php echo $this->_tpl_vars['item']['content']; ?>
</textarea></td>
        <td><?php echo $this->_tpl_vars['item']['add_time']; ?>
<br>IP:<?php echo $this->_tpl_vars['item']['ip']; ?>
</td>
        <td><?php if ($this->_tpl_vars['item']['status'] == 1): ?>已通过<?php elseif ($this->_tpl_vars['item']['status'] == 2): ?>已拒绝<?php else: ?><font color="red">未审核</font><?php endif; ?><br><?php if (! empty ( $this->_tpl_vars['item']['reply'] )): ?>已回复<?php else: ?><font color="red">未回复</font><?php endif; ?></td>
       <td>
		<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'goodsreplyform','id'=>$this->_tpl_vars['item']['goods_msg_id'],)));?>')">审核回复</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delgoods',)));?>','<?php echo $this->_tpl_vars['item']['goods_msg_id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'listgoods',)));?>')">删除</a>
	</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div style="padding:0 5px;"><input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall($('myForm'),'ids',this)"/>
<input type="button" value="已审核" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"check-goods-msg",)));?>/val/1')">
<input type="button" value="已拒绝" onclick="ajax_submit(this.form, '<?php echo $this -> callViewHelper('url', array(array('action'=>"check-goods-msg",)));?>/val/2')">
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</form>