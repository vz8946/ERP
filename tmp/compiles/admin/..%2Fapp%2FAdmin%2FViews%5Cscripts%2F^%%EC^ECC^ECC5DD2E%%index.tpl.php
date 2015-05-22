<?php /* Smarty version 2.6.19, created on 2014-10-28 14:09:45
         compiled from member-rank/index.tpl */ ?>
<div class="title">会员等级管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'add',)));?>')">添加会员等级</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>会员等级名称</td>
            <td>积分下限</td>
            <td>积分上限</td>
            <td>普通折扣率</td>
            <td>特殊会员等级</td>
            <td>显示价格</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['rankList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rank']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['rank']['rank_id']; ?>
">
            <td><?php echo $this->_tpl_vars['rank']['rank_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['rank']['rank_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['rank']['min_point']; ?>
</td>
            <td><?php echo $this->_tpl_vars['rank']['max_point']; ?>
</td>
            <td><?php echo $this->_tpl_vars['rank']['discount']; ?>
</td>
            <td><?php echo $this->_tpl_vars['rank']['is_special']; ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['rank']['rank_id']; ?>
"><?php echo $this->_tpl_vars['rank']['show_price']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['rank']['rank_id'],)));?>')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>','<?php echo $this->_tpl_vars['rank']['rank_id']; ?>
')">删除</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>