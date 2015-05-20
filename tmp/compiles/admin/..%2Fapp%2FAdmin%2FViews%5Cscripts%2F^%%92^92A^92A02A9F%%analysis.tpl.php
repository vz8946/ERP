<?php /* Smarty version 2.6.19, created on 2014-10-24 18:36:35
         compiled from offers/analysis.tpl */ ?>
<div class="title">活动情况查询</div>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<form name="searchForm" id="searchForm" action="/admin/offers/analysis">
<input type="hidden" name="dosearch" value="1">
<div class="search">
<div>
<span style="float:left; margin-left:0px">订单开始日期：<input type="text" name="from_date" id="using_time_from" size="11" value="<?php echo $this->_tpl_vars['param']['from_date']; ?>
" class="Wdate"   onClick="WdatePicker()"/></span>
<span style="float:left; margin-left:10px">截止日期：<input type="text" name="to_date" id="using_time_end" size="11" value="<?php echo $this->_tpl_vars['param']['to_date']; ?>
" class="Wdate"   onClick="WdatePicker()"/></span>
&nbsp;&nbsp;

<select name="order_status">
  <option value="">订单状态</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['order_status'] == '0'): ?>selected<?php endif; ?>>有效单</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['order_status'] == '1'): ?>selected<?php endif; ?>>取消单</option>
  <option value="2" <?php if ($this->_tpl_vars['param']['order_status'] == '2'): ?>selected<?php endif; ?>>无效单</option>
</select>
</div>

<div style="clear:both; padding-top:5px">
活动类型：
<select name="search_offers_type">
  <option value="">活动类型</option>
  <?php $_from = $this->_tpl_vars['offersTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['offer_type'] => $this->_tpl_vars['offer_name']):
?>
  <option value="<?php echo $this->_tpl_vars['offer_type']; ?>
" <?php if ($this->_tpl_vars['param']['search_offers_type'] == $this->_tpl_vars['offer_type']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['offer_name']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
</select>
&nbsp;&nbsp;
活动ID：<input type="text" name="offers_id" size="2" maxLength="5" value="<?php echo $this->_tpl_vars['param']['offers_id']; ?>
"/>
&nbsp;&nbsp;
活动名称：<input type="text" name="offers_name" size="15" maxLength="50" value="<?php echo $this->_tpl_vars['param']['offers_name']; ?>
"/>
&nbsp;&nbsp;
<select name="status">
  <option value="">活动状态</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>正常</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>冻结</option>
</select>
&nbsp;&nbsp;
<input type="submit" name="do_search" id="do_search" value="查询"/>
</div>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>活动名称</td>
            <td>活动类型</td>
            <td>开始时间</td>
            <td>截止时间</td>
            <td>活动订单数</td>
            <td>状态</td>
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['offersList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['offers']):
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
">
            <td><?php echo $this->_tpl_vars['offers']['offers_id']; ?>
</td>
            <td><a href="/admin/offers/edit/id/<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
"><?php echo $this->_tpl_vars['offers']['offers_name']; ?>
</a></td>
            <td><?php echo $this->_tpl_vars['offers']['offers_type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['offers']['from_date']; ?>
</td>
            <td><?php echo $this->_tpl_vars['offers']['to_date']; ?>
</td>
            <td><b><?php echo $this->_tpl_vars['offers']['order_num']; ?>
</b></td>
            <td><?php echo $this->_tpl_vars['offers']['status']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
</div>
<div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
<script>
function changeOrder(id, order)
{
    if (!/^\d{0,4}$/.test(order)) {
        alert('请输入四位以内的数字!');
        return;
    }
    
    if (id!='' && order!='') {
        new Request({
            url: '/admin/offers/change-order/id/' + id + '/order/' + order,
            onRequest: loading,
            onSuccess: loadSucess,
            onFailure: function(){
        	    alert('error');
            }
        }).send();
    }
}
</script>