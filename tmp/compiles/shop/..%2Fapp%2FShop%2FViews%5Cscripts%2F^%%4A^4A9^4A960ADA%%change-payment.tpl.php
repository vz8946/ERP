<?php /* Smarty version 2.6.19, created on 2014-11-19 17:07:50
         compiled from member/change-payment.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if ($this->_tpl_vars['page_title']): ?> <?php echo $this->_tpl_vars['page_title']; ?>
 - 垦丰商城  <?php else: ?> 垦丰电商 -专业的种子商城 <?php endif; ?></title> 
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="Keywords" content="<?php if ($this->_tpl_vars['page_keyword']): ?><?php echo $this->_tpl_vars['page_keyword']; ?>
<?php else: ?>垦丰,种子,玉米,小麦,大豆,甜菜,大麦<?php endif; ?>" />
<meta name="Description" content="<?php if ($this->_tpl_vars['page_description']): ?><?php echo $this->_tpl_vars['page_description']; ?>
<?php else: ?>垦丰电商 -专业的种子商城<?php endif; ?>" />
<link type="image/x-icon" href="<?php echo $this->_tpl_vars['_static_']; ?>
/images/home.ico" rel="Shortcut Icon">
<link type="text/css" href="<?php echo $this->_tpl_vars['_static_']; ?>
/css/css.php?t=css&f=base.css,cart.css&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.css" rel="stylesheet" />
<script>var site_url='<?php echo $this->_tpl_vars['_static_']; ?>
'; var jumpurl= '<?php echo $this->_tpl_vars['url']; ?>
';</script>
<script src="<?php echo $this->_tpl_vars['_static_']; ?>
/js/js.php?t=js&f=jquery.js,common.js&v=<?php echo $this->_tpl_vars['sys_version']; ?>
.js" ></script>
</head>
<body>
<div class="content">
	<div class="flow_step">
    	<span class="logo"><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/logo.jpg" width="225" height="101" /></span>
        <div class="pay_state"><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/pay_state01.jpg" width="508" height="43" /></div>
    </div>
  	<div class="title_cart">
   	  <h2>选择支付方式</h2>
    </div>
 
  <div class="order_info  pay_other" >
    <!--选择支付方式 begin---->
        <div class="pay_method" id="otherpay_box">
      
   <form action="/member/change-payment/" id="frmUpdata" method="post"> 

    	<h2>支付方式</h2>
       	<div class="pay_bank">
       	  <h3>在线支付&nbsp;&nbsp;&nbsp;<span>（即时到帐，支持大多数银行卡，付款成功后将立即安排发货）</span></h3>
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
	 <input type="hidden" name="batch_sn" value="<?php echo $this->_tpl_vars['order']['batch_sn']; ?>
"  />	
	 <input type="hidden" name="submitted" value="change_payment"  />
    <a href="javascript:;" onclick="setPayment()"><img width="71" height="31" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/btn_sure.jpg"></a>
 </div>           
  
  <!--选择支付方式 end---->
   </form>   </div>
  </div>
  
<div id="payed-box" style="display:none"> 
</div>

</div> 
<script>
function setPayment()
{
   var pay_len = $(":radio[name='pay_type'][checked]").length; //支付方式
   if(!pay_len)
   {
	  alert("请选择支付方式");
	  return  false;
   } 
   
   var params = $("#frmUpdata").serializeArray();
	$.post($("#frmUpdata").attr('action'),params,function(data){
		       if(data.status==1)
		    	  {		    	  
		    	    $("#otherpay_box").html("修改支付方式成功，正在调整支付页面，请稍后……");
		    	    $("#payed-box").html(data.pay_info); 		    	  
		    	    $("#payed-box form").removeAttr('target');
		    	    $("#payed-box form").submit();
		    	  }else{
		    	    alert(data.msg);
		         }
	 },'json');		
	return true;
   
}
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body></html>