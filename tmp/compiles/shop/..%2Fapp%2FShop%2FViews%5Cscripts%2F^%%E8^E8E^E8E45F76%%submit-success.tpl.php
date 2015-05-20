<?php /* Smarty version 2.6.19, created on 2014-10-30 11:13:39
         compiled from flow/submit-success.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'flow/submit-success.tpl', 28, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div class="content">
	<div class="flow_step">
    	<span class="logo"><a href="/" title="回到首页"><img width="225" height="101" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/logo.jpg"></a></span>
        <ul>
        	<li><img width="183" height="43" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step01.jpg"></li>
            <li><img width="183" height="43" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step02.jpg"></li>
            <li><img width="183" height="43" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step03_current.jpg"></li>
        </ul>
    </div>
  	<div class="pay_success">
    	<table  cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="right" rowspan="5"><img width="41" height="38" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/icon_success02.jpg"></td>
            <td><b>
             <?php if ($this->_tpl_vars['order']['orderPay'] > 0): ?>
              <?php if ($this->_tpl_vars['pay_info']): ?>订单已提交，请您尽快付款，以便订单尽快处理！<?php else: ?>订单已提交，您选择了货到付款，我们将尽快为您发货！<?php endif; ?> 
            <?php else: ?>            
                                   支付完成， 我们将尽快为您发货 ！
            <?php endif; ?>
            </b></td>
          </tr>
          <tr>
            <td><span>订单号：<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
 |  应付总额：<em><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['orderPay'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</em> 元</span>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <?php if ($this->_tpl_vars['auth']): ?>
          <tr>
            <td><a class="link" href="/member/order-detail/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">查看订单详情 </a> </td>
          </tr>
            <tr>
            <td><a class="link" href="/">再看看其他商品</a ></td>
          </tr>
          <?php else: ?>
           <tr>
            <td>友情提醒：</td>
          </tr>
          <tr>
            <td><i>以会员身份下单将获得积分奖励，并且能随时查看订单信息，如果您还没有注册成为我们的会员，请</i><a href="/reg.html" target="_blank">注册</a>。</td>
          </tr>          
          <?php endif; ?>
          
        
        </tbody></table>
    </div>
  <?php if ($this->_tpl_vars['order']['orderPay'] > 0 && $this->_tpl_vars['pay_info'] != ''): ?>
    <div class="pay_online">
   	  <h2>在线支付</h2>
      <div><span class="fl">您选择的支付方式：<img  src="<?php echo $this->_tpl_vars['payment']['img_url']; ?>
" /></span> <span class="fl ml10"><?php echo $this->_tpl_vars['pay_info']; ?>
</span></div>
       <div style="clear:both"></div>
      <?php if ($this->_tpl_vars['auth']): ?>     
        <p class="other" ><a href="/member/change-payment/batch_sn/<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
">选择其他在线支付方式</a></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>	
</div>

<script type="text/javascript">
_ozprm='orderid=<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
&ordertotal=<?php echo $this->_tpl_vars['order']['orderPay']; ?>
';
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body></html>