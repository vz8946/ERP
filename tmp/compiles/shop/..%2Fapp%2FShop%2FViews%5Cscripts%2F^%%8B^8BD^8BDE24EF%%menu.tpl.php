<?php /* Smarty version 2.6.19, created on 2014-10-30 11:17:25
         compiled from member/menu.tpl */ ?>
<div class="member_left">
	<div class="position">
		<b> <a title="垦丰商城" href="">商城首页</a> </b>
		&gt; 用户中心
	</div>

	<?php if ($this->_tpl_vars['pwdmsg']): ?>
	<div class="member_leftmenu">
		<p class="member_lefttitle1">
			提示
		</p>
		<div class="member_sub">
			<p>
				<a href="/member/password"> <font  color="#3A79EB" ><b>系统检查到您的帐号还没有设置密码，建议你重设密码并牢记。</b></font> </a>
			</p>
		</div>
	</div><!--member_leftmenu end -->
	<?php endif; ?>
	
	
	<dl>
		<dt class="<?php echo $this->_tpl_vars['nav_1_order']; ?>
">
			<p>
				<i class="i_order"></i>我的订单
			</p>
		</dt>
		<dd>
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_order_index']; ?>
" href="/member/order">我的订单</a>
			</p>
		</dd>
		<dt class="<?php echo $this->_tpl_vars['nav_1_account']; ?>
">
			<p>
				<i class="i_account"></i>账户管理
			</p>
		</dt>
		<dd>
			<p>
				<a href="/member/money" class="<?php echo $this->_tpl_vars['cn_money']; ?>
 <?php echo $this->_tpl_vars['nav_2_account_blance']; ?>
">账户余额</a>
			</p>
			<p>
				<a  class="<?php echo $this->_tpl_vars['nav_2_account_point']; ?>
" href="/member/point">会员积分</a>
			</p>
            <p>
				<a  class="<?php echo $this->_tpl_vars['nav_2_account_experience']; ?>
" href="/member/experience">会员经验值</a>
			</p>
			
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_account_youhuiquan']; ?>
" href="/member/coupon">我的优惠券</a>
			</p>
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_account_xianjinquan']; ?>
" href="/member/gift-card">我的垦丰卡</a>
			</p>
			<p class="last">
				<a class="<?php echo $this->_tpl_vars['nav_2_account_addr']; ?>
" href="/member/address">收货地址</a>
			</p>
		</dd>
		<dt class="<?php echo $this->_tpl_vars['nav_1_goods']; ?>
">
			<p>
				<i class="i_product"></i>商品管理
			</p>
		</dt>
		<dd>
			<p class="last">
				<a class="<?php echo $this->_tpl_vars['nav_2_goods_favo']; ?>
" href="/member/favorite">商品收藏</a>
			</p>
		</dd>
		<dt class="<?php echo $this->_tpl_vars['nav_1_note']; ?>
">
			<p>
				<i class="i_note"></i>站点留言
			</p>
		</dt>
		<dd>
			<p class="last">
				<a class="<?php echo $this->_tpl_vars['nav_2_note_list']; ?>
" href="/member/message" >站点留言</a>
			</p>
            <p class="last">
				<a class="<?php echo $this->_tpl_vars['nav_2_note_msg']; ?>
" href="/member/inside-message">站内信</a>
			</p>
		</dd>
		<!--
		<dt class="<?php echo $this->_tpl_vars['nav_1_try']; ?>
">
			<p>
				<i class="i_try"></i>我的试用
			</p>
		</dt>
		<dd>
			<p>
				<a href="/member/tryappl" <?php if ($this->_tpl_vars['action'] == 'tryappl'): ?>style="color:#2781e6;"<?php endif; ?>>我的申请</a>
			</p>
			<p class="last">
				<a href="/member/try-order" <?php if ($this->_tpl_vars['action'] == 'tryorder'): ?>style="color:#2781e6;"<?php endif; ?>>试用订单</a>
			</p>
		</dd>
		-->
		<?php if ($this->_tpl_vars['isView'] == 1): ?>
		<dt class="<?php echo $this->_tpl_vars['nav_1_personal']; ?>
">
			<p>
				<i class="i_info"></i>个人信息
			</p>
		</dt>
		<dd>
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_personal_avtar']; ?>
" href="/member/avatar" >修改头像</a>
			</p>
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_personal_info']; ?>
" href="/member/profile" >修改个人信息</a>
			</p>
			<p>
				<a class="<?php echo $this->_tpl_vars['nav_2_personal_pass']; ?>
" href="/member/password">修改密码</a>
			</p>
			<p class="last">
				<a href="/auth/logout">退出登录</a>
			</p>
		</dd>
		<?php endif; ?>
	</dl>
</div>

<style>
.member_left a.c{
	color: #2781e6;
}	
</style>