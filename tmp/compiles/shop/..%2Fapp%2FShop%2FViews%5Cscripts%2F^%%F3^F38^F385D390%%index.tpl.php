<?php /* Smarty version 2.6.19, created on 2014-10-30 11:17:25
         compiled from member/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'member/index.tpl', 3, false),array('modifier', 'replace', 'member/index.tpl', 31, false),array('modifier', 'cn_truncate', 'member/index.tpl', 32, false),)), $this); ?>
<div class="member"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
    <div class="welcome"> <span class="last_login_time">最后登录：<?php echo ((is_array($_tmp=$this->_tpl_vars['member']['last_login'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</span><?php if ($this->_tpl_vars['member']['nick_name']): ?><?php echo $this->_tpl_vars['member']['nick_name']; ?>
<?php else: ?><?php echo $this->_tpl_vars['member']['user_name']; ?>
<?php endif; ?> 欢迎回来 </div>
    <!--我的信息-->
    <div class="member_summary fleft">
      <p class="summary_title"> 我的信息 </p>
      <div class="summary_content">
        <ul>
          <li class="dotline"> 积分：<a class="countnumber" href="/member/point"><?php echo $this->_tpl_vars['member']['point']; ?>
</a> </li>
          <li class="dotline"> 账户余额：<a class="countnumber" href="/member/money"><?php echo $this->_tpl_vars['member']['money']; ?>
</a> </li>
          <li> 可用优惠券：<a class="countnumber" href="/member/coupon"><?php echo $this->_tpl_vars['coupons']; ?>
</a> </li>
        </ul>
      </div>
    </div>
    <!--最近的订单-->
    <div class="member_summary fleft mleft14">
      <p class="summary_title"> 最近订单 </p>
      <div class="summary_content">
        <ul>
          <li class="dotline"> 成功订单：<a class="countnumber" href="/member/order/ordertype/7"><?php echo $this->_tpl_vars['okOrder']; ?>
</a> 笔 </li>
          <li class="dotline"> 需付款订单：<a class="countnumber" href="/member/order/ordertype/4"><?php echo $this->_tpl_vars['feeOrder']; ?>
</a> 笔 </li>
          <li> 取消订单：<a class="countnumber" href="/member/order/ordertype/3"><?php echo $this->_tpl_vars['cancelOrder']; ?>
</a> 笔 </li>
        </ul>
      </div>
    </div>
    <!--商品收藏-->
    <div class="member_summary fleft">
      <p class="summary_title"> 商品收藏 </p>
      <div class="summary_content"> <?php $_from = $this->_tpl_vars['fav']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['favlist']):
?>
        <div class="summary_goods fleft">
          <p class="goods_img" style="height: 100px;border: 1px solid #eee;"> <a href="/goods-<?php echo $this->_tpl_vars['favlist']['goods_id']; ?>
.html" target="_blank"><img width="90" height="90"  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['favlist']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" title="<?php echo $this->_tpl_vars['favlist']['goods_name']; ?>
"/></a> </p>
          <p class="goods_name" style="padding: 5px 0px;"> <a href="/goods-<?php echo $this->_tpl_vars['favlist']['goods_id']; ?>
.html" target="_blank" title="<?php echo $this->_tpl_vars['favlist']['goods_name']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['favlist']['goods_name'])) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 6, "...") : smarty_modifier_cn_truncate($_tmp, 6, "...")); ?>
</a> </p>
        </div>
        <?php endforeach; endif; unset($_from); ?> </div>
    </div>
    <!--最近浏览记录-->
    <div class="member_summary fleft mleft14">
      <p class="summary_title" style="height:16px;"> <span style="float: left;">最近浏览记录</span> <span style="float: right;"><a onclick="clearCook('/clearhistory.html',this);" href="javascript:void(0);">清空</a></span> </p>
      <div class="summary_content"> <?php $_from = $this->_tpl_vars['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['historylist']):
?>
        <div class="summary_goods fleft">
          <p class="goods_img" style="height: 100px;border: 1px solid #eee;"> <a href="/goods-<?php echo $this->_tpl_vars['historylist']['goods_id']; ?>
.html" target="_blank"><img width="90" height="90" src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['historylist']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
" border="0" title="<?php echo $this->_tpl_vars['historylist']['goods_name']; ?>
"/></a> </p>
          <p class="goods_name" style="padding: 5px 0px;"> <a href="/goods-<?php echo $this->_tpl_vars['historylist']['goods_id']; ?>
.html" target="_blank" title="<?php echo $this->_tpl_vars['historylist']['goods_name']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['historylist']['goods_name'])) ? $this->_run_mod_handler('cn_truncate', true, $_tmp, 6, "...") : smarty_modifier_cn_truncate($_tmp, 6, "...")); ?>
</a> </p>
        </div>
        <?php endforeach; endif; unset($_from); ?> </div>
    </div>
    <div class="clear"></div>
    <!----> 
  </div>
  <div style="clear: both;"></div>
</div>
<script>
  	//清空浏览记录
		function clearCook(url,elt) {
			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				success : function(msg) {
					$(elt).parent().parent().parent().find('.summary_content').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
				}
			});
		}
</script> 