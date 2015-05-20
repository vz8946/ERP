<?php /* Smarty version 2.6.19, created on 2014-10-24 18:36:11
         compiled from offers/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'offers/index.tpl', 44, false),array('modifier', 'date_format', 'offers/index.tpl', 74, false),)), $this); ?>
<div class="title">活动管理</div>
<form name="searchForm" id="searchForm" action="/admin/offers">
<div class="search">


活动类型：
<select name="search_offers_type" onchange="searchForm.submit()">
  <option value="">按活动类型查询</option>
  <?php $_from = $this->_tpl_vars['offersTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['offer_type'] => $this->_tpl_vars['offer_name']):
?>
  <option value="<?php echo $this->_tpl_vars['offer_type']; ?>
" <?php if ($this->_tpl_vars['param']['search_offers_type'] == $this->_tpl_vars['offer_type']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['offer_name']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
</select>
&nbsp;&nbsp;
<select name="status" onchange="searchForm.submit()">
  <option value="">状态</option>
  <option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>正常</option>
  <option value="0" <?php if ($this->_tpl_vars['param']['status'] == '0'): ?>selected<?php endif; ?>>冻结</option>
</select>
&nbsp;&nbsp;
活动名称：<input type="text" name="offers_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['offers_name']; ?>
"/>
&nbsp;&nbsp;

&nbsp;&nbsp;
活动别名：<input type="text" name="as_name" size="20" maxLength="50" value="<?php echo $this->_tpl_vars['param']['as_name']; ?>
"/>
&nbsp;&nbsp;

<input type="checkbox" name="search_time_type[]" value="0" <?php if ($this->_tpl_vars['param']['search_time_type']['0']): ?>checked<?php endif; ?>>进行中
<input type="checkbox" name="search_time_type[]" value="1" <?php if ($this->_tpl_vars['param']['search_time_type']['1']): ?>checked<?php endif; ?>>未开始
<input type="checkbox" name="search_time_type[]" value="2" <?php if ($this->_tpl_vars['param']['search_time_type']['2']): ?>checked<?php endif; ?>>已结束
&nbsp;&nbsp;
<!--
<select name="search_uid">
<option value="0">按联盟ID</option>
<option value="13" <?php if ($this->_tpl_vars['param']['search_uid'] == 13): ?>selected<?php endif; ?>>[13] 51返利</option>
<option value="11" <?php if ($this->_tpl_vars['param']['search_uid'] == 11): ?>selected<?php endif; ?>>[11] 139返利</option>
</select>
-->
&nbsp;&nbsp;
<input type="submit" name="dosearch" id="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <div class="sub_title">
        [ 添加活动 <select name="offers_type" onchange="if (this.value!='') G('/admin/offers/add/type/' + this.value )"><option value="">请选择活动</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['offersTypes']), $this);?>
</select> ] <b><font color="#FF0000">注：执行顺序按照权重从大到小执行</font></b>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table" >
        <thead>
        <tr>
            <td>ID</td>
            <td>活动名称</td>
			<td>活动别名</td>
            <td>活动类型</td>
            <td>开始时间</td>
            <td>截止时间</td>
            <td>权重</td>
            <td>管理员</td>
            <td>添加日期</td>
            <td>状态</td>
            <td>操作</td>
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
            <td><?php echo $this->_tpl_vars['offers']['offers_name']; ?>
</td>
			<td><?php echo $this->_tpl_vars['offers']['as_name']; ?>
</td>
            <td><?php echo $this->_tpl_vars['offers']['offers_type']; ?>
</td>
            <td><?php echo $this->_tpl_vars['offers']['from_date']; ?>
</td>
            <td><?php echo $this->_tpl_vars['offers']['to_date']; ?>
</td>
            <td><input type="text" size="4" maxlength="4" value="<?php echo $this->_tpl_vars['offers']['order']; ?>
" onchange="changeOrder('<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
',this.value)" /></td>
            <!--<td id="ajax_coupon<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
"><?php echo $this->_tpl_vars['offers']['use_coupon']; ?>
</td>-->
            <td><?php echo $this->_tpl_vars['offers']['admin_name']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['offers']['add_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
            <td id="ajax_status<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
"><?php echo $this->_tpl_vars['offers']['status']; ?>
</td>
            <td>
                <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'edit','id'=>$this->_tpl_vars['offers']['offers_id'],)));?>')">编辑</a>
                <a href="javascript:fGo()" onclick="reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>','<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
')">删除</a>
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