<?php /* Smarty version 2.6.19, created on 2014-11-01 14:16:08
         compiled from member/coupon-query.tpl */ ?>
<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/member/coupon-query">
<span style="margin-left:5px;">卡号: </span><input type="text" name="card_sn" value="<?php echo $this->_tpl_vars['param']['card_sn']; ?>
" size="15" />
<span style="margin-left:5px;">密码: </span><input type="text" name="card_password" value="<?php echo $this->_tpl_vars['param']['card_password']; ?>
" size="15" />
<span style="margin-left:5px"></span><input type="button" name="dosearch" value="搜索" onclick="ajax_search(this.form,'<?php echo $this -> callViewHelper('url', array(array('action'=>"coupon-query",'do'=>'search',)));?>','ajax_search')"/>
</form>
</div>
<div id="ajax_search">
<?php endif; ?>
<div class="title">优惠券信息查询 </div>
<?php if ($this->_tpl_vars['error']): ?>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td align="center">
<font color="red" size="3">
<?php if ($this->_tpl_vars['error'] == 'no_card'): ?>卡号或密码错误！
<?php elseif ($this->_tpl_vars['error'] == 'no_card_log'): ?>找不到开卡信息！
<?php elseif ($this->_tpl_vars['error'] == 'no_card_sn'): ?>卡号必须输入！
<?php endif; ?>
</font>
</td>
</tr>
</table>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['data']): ?>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<tr>
<td width="10%">礼券类型 * </td>
<td width="40%">
<?php if ($this->_tpl_vars['data'] ['card_type'] == 0): ?>
常规卡
<?php elseif ($this->_tpl_vars['data'] ['card_type'] == 1): ?>
非常规卡
<?php elseif ($this->_tpl_vars['data'] ['card_type'] == 2): ?>
绑定商品卡
<?php elseif ($this->_tpl_vars['data'] ['card_type'] == 3): ?>
商品抵扣卡
<?php elseif ($this->_tpl_vars['data'] ['card_type'] == 4): ?>
订单金额折扣卡
<?php endif; ?>
</td>
<td width="10%">是否可重复使用 * </td>
<td width="40%">
<?php if ($this->_tpl_vars['data'] ['is_repeat'] == 0): ?>
否
<?php else: ?>
是
<?php endif; ?>
</td>
</tr>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%" id="type012_1">礼券价格 * </td>
<td width="10%" id="type3_1" style="display:none">抵扣方式 * </td>
<td width="10%" id="type4_1" style="display:none">订单折扣 * </td>
<td id="type012_2"><?php echo $this->_tpl_vars['data']['card_price']; ?>
</td>
<td id="type3_2" style="display:none;">
  <span style="float:left">
  <?php if ($this->_tpl_vars['data']['card_price'] == '0.00'): ?>
  商品全额
  <?php else: ?>
  商品金额抵扣　价格：<?php echo $this->_tpl_vars['data']['card_price']; ?>

  <?php endif; ?>
  运费减免：<?php echo $this->_tpl_vars['data']['freight']; ?>

  </span>
</td>
<td width="10%">生成数量 * </td>
<td width="40%"><?php echo $this->_tpl_vars['data']['number']; ?>
</td>
</tr>
<tr>
<td>截止日期 * </td>
<td><span style="float:left;width:150px;"><?php echo $this->_tpl_vars['data']['end_date']; ?>
</span></td>
<td id="type012_3">绑定ID</td>
<td id="type3_3" style="display:none">绑定商品</td>
<td id="type012_4"><?php echo $this->_tpl_vars['data']['parent_id']; ?>
&nbsp;&nbsp;是否按照券分成：<?php if ($this->_tpl_vars['data']['is_affiliate'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
<td id="type3_4" style="display:none">
<div id="numArea">
  <?php $_from = $this->_tpl_vars['goods_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['goods_sn'] => $this->_tpl_vars['number']):
?> 
  <div>
    SN：<?php echo $this->_tpl_vars['goods_sn']; ?>

    名称：<?php echo $this->_tpl_vars['goods_name'][$this->_tpl_vars['goods_sn']]; ?>

    数量：<?php echo $this->_tpl_vars['number']; ?>

  </div>
  <?php endforeach; endif; unset($_from); ?>
</div>
</td>
</tr>
<tr id="amount_tr" style="display:none">
<td>最低订单价格</td>
<td colspan="3"><?php echo $this->_tpl_vars['data']['min_amount']; ?>
</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">备注</td>
<td colspan="3"><?php echo $this->_tpl_vars['data']['note']; ?>
</td>
</tr>
</tbody>
</table>  
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['couponMember']): ?>
<div class="title">会员信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">会员名称</td>
<td width="40%"><?php echo $this->_tpl_vars['couponMember']['user_name']; ?>
</td>
<td width="10%">会员ID</td>
<td><?php echo $this->_tpl_vars['couponMember']['user_id']; ?>
</td>
</tr>
<tr>
<td>绑定时间</td>
<td><?php echo $this->_tpl_vars['couponMember']['add_time']; ?>
</td>
<td>状态</td>
<td><?php if ($this->_tpl_vars['couponMember']['status']): ?>已使用<?php else: ?>未使用     <a href="javascript:fGo()" onclick="setCoupon('<?php echo $this->_tpl_vars['couponMember']['card_sn']; ?>
')">设置为无效</a>    <?php endif; ?></td>
</table>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['orderInfo']): ?>
<div class="title">订单信息 </div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
<td width="10%">订单号</td>
<td width="40%"><a href="/admin/order/info/batch_sn/<?php echo $this->_tpl_vars['orderInfo']['batch_sn']; ?>
"><?php echo $this->_tpl_vars['orderInfo']['batch_sn']; ?>
</a></td>
<td width="10%">订单金额</td>
<td><?php echo $this->_tpl_vars['orderInfo']['price_pay']; ?>
</td>
</tr>
<tr>
<td>订单状态</td>
<td><?php if ($this->_tpl_vars['orderInfo']['status'] == 0): ?>正常单<?php elseif ($this->_tpl_vars['orderInfo']['status'] == 1): ?>取消单<?php elseif ($this->_tpl_vars['orderInfo']['status'] == 2): ?>无效单<?php endif; ?></td>
<td>联系方式</td>
<td><?php echo $this->_tpl_vars['orderInfo']['addr_consignee']; ?>
 <?php echo $this->_tpl_vars['orderInfo']['addr_mobile']; ?>
</td>
</table>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['param']['do'] != 'search' && $this->_tpl_vars['param']['do'] != 'splitPage'): ?>
</div>
<?php endif; ?>

<script>
function showBlock() {
    var value = '<?php echo $this->_tpl_vars['data']['card_type']; ?>
';
    if (value == '3') {
        $('type3_1').style.display='';
        $('type3_2').style.display='';
        $('type012_1').style.display='none';
        $('type4_1').style.display='none';
        $('type012_2').style.display='none';
        $('type012_3').style.display='none';
        $('type012_4').style.display='none';
        
        $('type3_3').style.display='';
        $('type3_4').style.display='';
        $('amount_tr').style.display='';
        $('type3_2').float = 'right';
    }
    else {
        if ((value == '1') || (value == '4')) {
            $('amount_tr').style.display='';
        }
        else {
            $('amount_tr').style.display='none';
        }
        $('type012_1').style.display='';
        $('type012_2').style.display='';
        $('type012_3').style.display='';
        $('type012_4').style.display='';
        $('type3_1').style.display='none';
        $('type3_2').style.display='none';
        $('type3_3').style.display='none';
        $('type3_4').style.display='none';
        
        if (value == '4') {
            $('type012_1').style.display='none';
            $('type4_1').style.display='';
        }
        else {
            $('type012_1').style.display='';
            $('type4_1').style.display='none';
        }
    }
}
function showDeductionPrice()
{
    if ($('deductionType').value == 2) {
        $('deduction_price_area').style.display = 'block';
    }
    else {
        $('deduction_price_area').style.display = 'none';
        $('card_price').value = '0';
    }
}

<?php if ($this->_tpl_vars['data']): ?>showBlock();<?php endif; ?>
</script>


<script type="text/javascript">
function setCoupon(card_sn){
	if(confirm("确认要对该券号做已经使用操作吗？")){
		new Request({
			url:'/admin/member/set-coupon/card_sn/'+card_sn,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("操作成功");
				}else{
					alert(data+"操作失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>