<?php /* Smarty version 2.6.19, created on 2014-10-22 22:15:19
         compiled from msg/listsite.tpl */ ?>
<?php if (! $this->_tpl_vars['param']['do']): ?>
<form name="searchForm" id="searchForm">
<div class="search">
<div class="line">
<select name="type">
<option value="">选择留言类型</option>
<option value="0"   <?php if ($this->_tpl_vars['param']['type'] == '0'): ?>selected<?php endif; ?> >留言</option>
<option value="1" <?php if ($this->_tpl_vars['param']['type'] == '1'): ?>selected<?php endif; ?> >投诉</option>
<option value="2" <?php if ($this->_tpl_vars['param']['type'] == '2'): ?>selected<?php endif; ?> >询问</option>
<option value="3" <?php if ($this->_tpl_vars['param']['type'] == '3'): ?>selected<?php endif; ?> > 售后</option>
<option value="4" <?php if ($this->_tpl_vars['param']['type'] == '4'): ?>selected<?php endif; ?> >求购</option>
<option value="5" <?php if ($this->_tpl_vars['param']['type'] == '5'): ?>selected<?php endif; ?> >留言板</option>
<option value="7" <?php if ($this->_tpl_vars['param']['type'] == '7'): ?>selected<?php endif; ?> >专家问答</option>
</select>
<input type="button" name="dosearch" value="查询" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/>
</div>
</div>
</form>
<?php endif; ?>

<div id="ajax_search">
<div class="title">站点留言管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>留言类型</td>
            <td>用户</td>
            <td>留言内容</td>
            <td>留言时间</td>
            <td>是否热门</td>
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
        <td><?php echo $this->_tpl_vars['item']['type']; ?>
</td>
        <td><a href="/admin/member/view/id/<?php echo $this->_tpl_vars['item']['user_id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['user_name']; ?>
</a><br>联系：<?php echo $this->_tpl_vars['item']['contact']; ?>
<br>Email:<?php echo $this->_tpl_vars['item']['email']; ?>
<br><?php if ($this->_tpl_vars['item']['order_count']): ?><a href="/admin/order/list/dosearch/search/user_name/<?php echo $this->_tpl_vars['item']['user_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['order_count']; ?>
个订单</a><?php endif; ?></td>
        <td><textarea rows="5" cols="39" style="width:300px; height:80px;"><?php echo $this->_tpl_vars['item']['content']; ?>
</textarea></td>
        <td><?php echo $this->_tpl_vars['item']['add_time']; ?>
<br>IP:<?php echo $this->_tpl_vars['item']['ip']; ?>
</td>
        <td><?php if ($this->_tpl_vars['item']['is_hot']): ?><b style="color:#F00">热门</b><?php else: ?><b>否</b><?php endif; ?></td>
        <td><?php if ($this->_tpl_vars['item']['status'] == 1): ?>已通过<?php elseif ($this->_tpl_vars['item']['status'] == 2): ?>已拒绝<?php else: ?><font color="red">未审核</font><?php endif; ?><br><?php if (! empty ( $this->_tpl_vars['item']['reply'] )): ?>已回复<?php else: ?><font color="red">未回复</font><?php endif; ?></td>
       <td>
		<a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'sitereplyform','id'=>$this->_tpl_vars['item']['msg_id'],)));?>')">审核回复</a> | 
		<a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delsite',)));?>','<?php echo $this->_tpl_vars['item']['msg_id']; ?>
','<?php echo $this -> callViewHelper('url', array(array('action'=>'listsite',)));?>')">删除</a>
	</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>