<?php /* Smarty version 2.6.19, created on 2014-10-23 09:58:20
         compiled from msg/listgoodscomment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'msg/listgoodscomment.tpl', 20, false),)), $this); ?>
<div id="goods_comment">
<div class="title">商品评论列表</div>
<div class="content">

    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>评价</td>
            <td>用户</td>
			<td>热点评论</td>
            <td width="200px">留言内容</td>
            <td>留言时间</td>
			<td>是否审核</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <tr id="ajax_list<?php echo $this->_tpl_vars['item']['msg_id']; ?>
">
        <td>外 观：<?php echo $this->_tpl_vars['item']['cnt1']; ?>
<br>舒适度：<?php echo $this->_tpl_vars['item']['cnt2']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['user_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 12, "...") : smarty_modifier_truncate($_tmp, 12, "...")); ?>
</td>
		       <td ><?php if ($this->_tpl_vars['item']['is_hot'] == 1): ?> <font color="#FF3300">是 </font><?php else: ?>否<?php endif; ?></td>
        <td><textarea rows="3" cols="39" style="width:300px; height:80px;"><?php echo $this->_tpl_vars['item']['content']; ?>
</textarea></td>
        <td><?php echo $this->_tpl_vars['item']['add_time']; ?>
<br>IP:<?php echo $this->_tpl_vars['item']['ip']; ?>
</td>
        <td><?php if ($this->_tpl_vars['item']['status'] == 1): ?>已通过<?php elseif ($this->_tpl_vars['item']['status'] == 2): ?>已拒绝<?php else: ?><font color="red">未审核</font><?php endif; ?><br><?php if (! empty ( $this->_tpl_vars['item']['reply'] )): ?>已回复<?php else: ?><font color="red">未回复</font><?php endif; ?></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
    </table>
</div>

<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>