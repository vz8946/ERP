<?php /* Smarty version 2.6.19, created on 2014-10-30 11:13:17
         compiled from flow/payment.tpl */ ?>
<h2><b>支付方式 </b>   <span class="step-action" id="payment_edit_action"> <a href="javascript:;"  onclick="return setPayment();">保存支付方式</a></span></h2> 
<div class="pay_bank">
       	  <h3>在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
       	  <em>友情提示：推荐使用支付宝、财付通或交通银行支付，除交通银行进入自身网银支付外，其他银行均需登录支付宝完成支付。</em>
       	  <?php if ($this->_tpl_vars['payment']['list']): ?>  
          <ul>
          <?php $_from = $this->_tpl_vars['payment']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['bank_list']):
?>
                        <li>
							<input <?php echo $this->_tpl_vars['bank_list']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['bank_list']['pay_type']; ?>
" 
                            <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['bank_list']['pay_type']): ?>checked="checked"<?php endif; ?>/>
							<img src="<?php echo $this->_tpl_vars['bank_list']['img_url']; ?>
"  /> 
							</li>   
						<?php endforeach; endif; unset($_from); ?>  
          </ul>
          <?php endif; ?>
       	  
       	  
       	   <?php if ($this->_tpl_vars['payment']['bank_list']): ?>  
          <ul>
          <?php $_from = $this->_tpl_vars['payment']['bank_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['bank_list']):
?>
                        <li>
							<input <?php echo $this->_tpl_vars['bank_list']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['bank_list']['pay_type']; ?>
" 
                            <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['bank_list']['pay_type']): ?>checked="checked"<?php endif; ?>/>
							<img  src="<?php echo $this->_tpl_vars['bank_list']['img_url']; ?>
" /> 
							</li>						
		<?php endforeach; endif; unset($_from); ?>                          
          </ul>
         <?php endif; ?> 
            
          </div>
 <div class="daofu">
     <?php if ($this->_tpl_vars['payment']['cod']): ?> 
     	<h3><input <?php echo $this->_tpl_vars['payment']['cod']['js']; ?>
 type="radio" name="pay_type" value="<?php echo $this->_tpl_vars['payment']['cod']['pay_type']; ?>
" <?php if ($this->_tpl_vars['payType'] == $this->_tpl_vars['payment']['cod']['pay_type']): ?>checked="checked"<?php endif; ?>/>			
	<?php echo $this->_tpl_vars['payment']['cod']['name']; ?>
  <span>（<?php echo $this->_tpl_vars['payment']['cod']['dsc']; ?>
）</span></h3>
	 <?php endif; ?>		
     	<a class="btn_save" href="javascript:;"  onclick="return setPayment();" >保存支付方式</a>  
 </div>  