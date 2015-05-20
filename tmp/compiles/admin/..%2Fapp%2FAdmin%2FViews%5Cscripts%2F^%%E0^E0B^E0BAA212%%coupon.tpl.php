<?php /* Smarty version 2.6.19, created on 2014-10-24 15:00:26
         compiled from member/coupon.tpl */ ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/coupon">
<span style="margin-left:5px; vertical-align:top">会员名或昵称: </span><input type="text" name="user_name" value="<?php echo $this->_tpl_vars['param']['user_name']; ?>
" size="15" />
<select name="card_type" onchange="searchForm.submit()">
<option value="">按礼券类型查询</option>
<option value="0" <?php if ($this->_tpl_vars['param']['card_type'] == '0'): ?>selected<?php endif; ?>>常规卡</option>
<option value="1" <?php if ($this->_tpl_vars['param']['card_type'] == '1'): ?>selected<?php endif; ?>>非常规卡</option>
<option value="2" <?php if ($this->_tpl_vars['param']['card_type'] == '2'): ?>selected<?php endif; ?>>绑定商品卡</option>
<option value="3" <?php if ($this->_tpl_vars['param']['card_type'] == '3'): ?>selected<?php endif; ?>>商品抵扣卡</option>
<option value="4" <?php if ($this->_tpl_vars['param']['card_type'] == '4'): ?>selected<?php endif; ?>>订单金额抵扣卡</option>
</select>
&nbsp;&nbsp;
<select name="status" onchange="searchForm.submit()">
<option value="">按使用状态</option>
<option value="1" <?php if ($this->_tpl_vars['param']['status'] == '1'): ?>selected<?php endif; ?>>可使用</option>
<option value="2" <?php if ($this->_tpl_vars['param']['status'] == '2'): ?>selected<?php endif; ?>>已使用/无效</option>
<option value="3" <?php if ($this->_tpl_vars['param']['status'] == '3'): ?>selected<?php endif; ?>>已经过期</option>
</select>
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>'coupon','do'=>'search',)));?>','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">会员礼金券信息查询 </div>
<div class="content">
     <table cellpadding="0" cellspacing="0" border="0" class="table" id="table">
        <thead>
        <tr>
            <td>用户ID</td>
			<td>用户名</td>
			<td>优惠券类型</td>
			<td>卡号</td>
            <td>金额</td>
            <td>使用状态</td>
			<td>有效期至</td>
           
        </tr>
        </thead>
        <tbody>
        <?php $_from = $this->_tpl_vars['coupon_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['list']):
        $this->_foreach['list']['iteration']++;
?>
        <tr id="ajax_list<?php echo $this->_tpl_vars['member']['user_id']; ?>
">
            <td><?php echo $this->_tpl_vars['list']['user_id']; ?>
</td>
			<td><?php echo $this->_tpl_vars['list']['user_name']; ?>
</td>
			<td>
			<?php if ($this->_tpl_vars['list']['card_type'] == 0): ?>常规卡
			<?php elseif ($this->_tpl_vars['list']['card_type'] == 1): ?>非常规卡
			<?php elseif ($this->_tpl_vars['list']['card_type'] == 2): ?>绑定商品卡
			<?php elseif ($this->_tpl_vars['list']['card_type'] == 3): ?>商品抵扣卡
			<?php elseif ($this->_tpl_vars['list']['card_type'] == 4): ?>订单金额抵扣卡
			<?php endif; ?>
			</td>
			<td><a href="/admin/coupon/view-log/id/<?php echo $this->_tpl_vars['list']['log_id']; ?>
"><?php echo $this->_tpl_vars['list']['card_sn']; ?>
</a></td>
			<td><?php echo $this->_tpl_vars['list']['card_price']; ?>
</td>
            <td>  
              <?php if ($this->_tpl_vars['list']['status'] == 1): ?>已使用/无效
              <?php elseif ($this->_tpl_vars['curtime'] > $this->_tpl_vars['list']['end_date']): ?><font color="#FF6600"> 已经过期 </font>
              <?php else: ?><span class="highlight">可使用</span>
              <?php endif; ?>
            </td>
            <td><?php echo $this->_tpl_vars['list']['end_date']; ?>
</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </tbody>
    </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
</div>

<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
</div>
<script>
function multiDelete()
{
    checked = multiCheck($('table'),'ids',$('doDelete'));
    if (checked != '') {
        reallydelete('<?php echo $this -> callViewHelper('url', array(array('action'=>'delete',)));?>', checked);
    }
}
</script>
<?php endif; ?>