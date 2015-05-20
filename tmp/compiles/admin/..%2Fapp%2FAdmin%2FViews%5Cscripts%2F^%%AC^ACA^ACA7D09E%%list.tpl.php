<?php /* Smarty version 2.6.19, created on 2014-10-24 18:36:56
         compiled from gift-card/list.tpl */ ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form name="searchForm" id="searchForm">
    <div>
        <span style="float:left">使用日期从：<input type="text" name="add_time_from" id="add_time_from" size="11" value="<?php echo $this->_tpl_vars['param']['add_time_from']; ?>
"  class="Wdate" onClick="WdatePicker()" /></span>
        <span style="float:left; margin-left:10px">截止到：<input type="text" name="add_time_end" id="add_time_end" size="11" value="<?php echo $this->_tpl_vars['param']['add_time_end']; ?>
" class="Wdate"   onClick="WdatePicker()" /></span>
        <span style="margin-left:5px; vertical-align:top">
        状态: 
        <select name="status">
          <option value="">请选择</option>
          <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>有效</option>
          <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>无效</option>
          <option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>未激活</option>
        </select>
        </span>
        <span style="margin-left:5px; vertical-align:top">价格: <input type="text" name="card_price" value="<?php echo $this->_tpl_vars['param']['card_price']; ?>
" size="6" /></span>
        <span style="margin-left:5px; vertical-align:top">卡号: <input type="text" name="card_sn" value="<?php echo $this->_tpl_vars['param']['card_sn']; ?>
" size="11" /></span>
        <span style="margin-left:5px; vertical-align:top">用户名: <input type="text" name="user_name" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
" size="20" /></span>
        <span style="margin-left:5px; vertical-align:top"><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('do'=>'search',)));?>','ajax_search')"/></span>
    </div>
</form>
</div>
<?php endif; ?>
<div id="ajax_search">
<div class="title">礼品卡列表</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>礼品卡类型</td>
            <td>礼品卡价格</td>
            <td>卡号</td>
            <td>生成时间</td>
            <td>余额</td>
            <td>最后使用用户</td>
            <td>最后使用时间</td>
			<td>状态</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['cardList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['card']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['history']['card_id']; ?>
">
            <td><?php echo $this->_tpl_vars['card']['card_id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['card_type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['card_price']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['card_sn']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['add_time']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['card_real_price']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['user_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['card']['using_time']; ?>
</td>
			  <td><?php if ($this->_tpl_vars['card']['status'] == '0'): ?>有效<?php elseif ($this->_tpl_vars['card']['status'] == '1'): ?>无效<?php elseif ($this->_tpl_vars['card']['status'] == '2'): ?>未激活<?php endif; ?></td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"use-log",'card_sn'=>$this->_tpl_vars['card']['card_sn'],)));?>')">查看使用历史</a>
            </td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>