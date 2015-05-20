<?php /* Smarty version 2.6.19, created on 2014-10-30 13:43:07
         compiled from member/coupon.tpl */ ?>
<div class="member">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
    <div class="memberddbg">
	 <p>
	   <?php if ($this->_tpl_vars['type'] == 1): ?>
	     <?php if ($this->_tpl_vars['total'] > 0): ?>您有 <span class="highlight"><?php echo $this->_tpl_vars['total']; ?>
</span> 张抵用券可以使用<?php else: ?>您没有抵用券<?php endif; ?>
	   <?php else: ?>
	     <?php if ($this->_tpl_vars['total'] > 0): ?>您有 <span class="highlight"><?php echo $this->_tpl_vars['total']; ?>
</span> 张无效抵用券<?php else: ?>您没有无效抵用券<?php endif; ?>
	   <?php endif; ?>
	 </p>
	</div>
<img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_coupon.png">

	<div class="coupontype ">
	<div class="coupontypea">
    <a href="/member/coupon/type/1" <?php if ($this->_tpl_vars['type'] == 1): ?> class="sel" <?php endif; ?>>可使用</a>
    <a href="/member/coupon/type/2" <?php if ($this->_tpl_vars['type'] == 2): ?> class="sel" <?php endif; ?>>已无效</a>
    <a href="/member/active-coupon" >激活优惠券</a>
    </div>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr>
                        <th>券号</th>
                        <th>密码</th>
                        <th>类型</th>
                        <th>金额</th>
			<th>使用条件</th>
                        <th>使用状态</th>
			<th>开始时间</th>
                        <th>有效期至</th>
                    </tr>
                </thead>
                <tbody class="text-mid">
                    <?php $_from = $this->_tpl_vars['coupons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['coupon']):
?>
                    <tr>
                        <td><?php echo $this->_tpl_vars['coupon']['card_sn']; ?>
</td>
                        <td><?php echo $this->_tpl_vars['coupon']['card_pwd']; ?>
</td>
                        <td>
                          <?php if ($this->_tpl_vars['coupon']['card_type'] == 0): ?>
                            订单金额抵扣
                          <?php elseif ($this->_tpl_vars['coupon']['card_type'] == 1): ?>
                            订单金额抵扣
                          <?php elseif ($this->_tpl_vars['coupon']['card_type'] == 2): ?>
                            <?php if ($this->_tpl_vars['coupon']['goods_name']): ?>
                              <a title="<?php echo $this->_tpl_vars['coupon']['goods_name']; ?>
">特定商品抵扣</a>
                            <?php else: ?>
                              特定商品抵扣
                            <?php endif; ?>
                          <?php elseif ($this->_tpl_vars['coupon']['card_type'] == 3 || $this->_tpl_vars['coupon']['card_type'] == 5): ?>
                            <?php if ($this->_tpl_vars['coupon']['coupon_price'] > 0): ?>
                              <?php if ($this->_tpl_vars['coupon']['goods_name']): ?>
                                <a title="<?php echo $this->_tpl_vars['coupon']['goods_name']; ?>
">特定商品抵扣</a>
                              <?php else: ?>
                                特定商品抵扣
                              <?php endif; ?>
                            <?php else: ?>
                              <?php if ($this->_tpl_vars['coupon']['goods_name']): ?>
                                <a title="<?php echo $this->_tpl_vars['coupon']['goods_name']; ?>
">特定商品全额抵扣</a>
                              <?php else: ?>
                                特定商品全额抵扣
                              <?php endif; ?>
                            <?php endif; ?>
                          <?php elseif ($this->_tpl_vars['coupon']['card_type'] == 4): ?>
                            订单金额折扣
                          <?php endif; ?>
                        </td>
                        <td>
                        <?php if ($this->_tpl_vars['coupon']['card_type'] == 3 || $this->_tpl_vars['coupon']['card_type'] == 5): ?>
                          <?php if ($this->_tpl_vars['coupon']['coupon_price'] > 0): ?>
                            ￥<?php echo $this->_tpl_vars['coupon']['coupon_price']; ?>

                          <?php else: ?>
                            *
                          <?php endif; ?>
                        <?php elseif ($this->_tpl_vars['coupon']['card_type'] == 4): ?>
                          <?php echo $this->_tpl_vars['coupon']['coupon_price']; ?>
折
                        <?php else: ?>
                          ￥<?php echo $this->_tpl_vars['coupon']['coupon_price']; ?>

                        <?php endif; ?>
                        </td>
						<td>
						  <?php if ($this->_tpl_vars['coupon']['min_amount'] > 0): ?>
						  满<?php echo $this->_tpl_vars['coupon']['min_amount']; ?>
可使用 
						  <?php else: ?>
						  订单金额无限制
						  <?php endif; ?>
						  <?php if ($this->_tpl_vars['coupon']['card_type'] == 0 || $this->_tpl_vars['coupon']['card_type'] == 1 || $this->_tpl_vars['coupon']['card_type'] == 4): ?>
						    <?php if ($this->_tpl_vars['coupon']['goods_info']['allGoods'] || $this->_tpl_vars['coupon']['goods_info']['allGroupGoods']): ?>
						    <br>购买指定商品
						    <?php endif; ?>
						  <?php endif; ?>
						</td>
                        <td><?php if ($this->_tpl_vars['curtime'] > $this->_tpl_vars['coupon']['end_date']): ?> <font color="#FF6600"> 已经过期 </font><?php else: ?> 
						  <?php if ($this->_tpl_vars['coupon']['status'] == 0): ?><span class="highlight">可使用</span><?php else: ?>已使用/无效<?php endif; ?> <?php endif; ?>   
						 </td>
			<td><?php echo $this->_tpl_vars['coupon']['start_date']; ?>
</td>
                        <td><?php echo $this->_tpl_vars['coupon']['end_date']; ?>
</td>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?>
                </tbody>
            </table>
    <div class="page_nav"><?php echo $this->_tpl_vars['pageNav']; ?>
</div>
  </div>
</div>