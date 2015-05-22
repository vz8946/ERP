<?php /* Smarty version 2.6.19, created on 2014-12-02 17:53:43
         compiled from flow/delivery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'flow/delivery.tpl', 10, false),array('modifier', 'default', 'flow/delivery.tpl', 41, false),)), $this); ?>
<form method="post" id="deliveryFrom" action="/flow/set-delivery/" >  
<h2><b>配送与发票</b><span class="step-action" id="invoice_edit_action"> <a href="javascript:;" onclick="return  setDelivery();">保存配送与发票信息</a></span></h2>
<div class="invoiceInfo">
    	<p><b>配送方式 : </b>    <input type="radio" value="0" <?php if ($this->_tpl_vars['delivery']['warehouse_id'] == 0): ?>checked<?php endif; ?> name="warehouse_type"> 物流配送 &nbsp;&nbsp; 
        
          <input value="1" name="warehouse_type"  <?php if ($this->_tpl_vars['delivery']['warehouse_id'] > 0): ?>checked<?php endif; ?> type="radio"> 顾客自提</p>
          
        <p id="warehouse_box" <?php if ($this->_tpl_vars['delivery']['warehouse_id'] == 0): ?>style="display:none"<?php endif; ?>>自提点：<select name="warehouse_id">
		<option value="">--请选择--</option>
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['warehouse'],'selected' => $this->_tpl_vars['delivery']['warehouse_id']), $this);?>

	</select></p>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        <p><b>发票信息</b>    &nbsp;选择开票，发票将会与商品一同寄出。</p>
        <div class="invoice_detail">
       	    <!--<p><input type="radio" <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 0): ?>checked<?php endif; ?>  value="0" name="invoice_type">&nbsp;不开发票</p>-->
            <p><input name="invoice_type"  <?php if ($this->_tpl_vars['delivery']['invoice_type'] != 2): ?>checked<?php endif; ?>  type="radio"  value="1" />&nbsp;个人&nbsp;<input name="invoice[1]" id="invoice_person" type="text" class="txtBox  txtBox01" value="姓名" />
            <span id="licence" <?php if ($this->_tpl_vars['delivery']['invoice_type'] != 1): ?>style="display:none"<?php endif; ?>> 证件号码：
            <input type="text" name="licence" id="licence_name" value="<?php echo $this->_tpl_vars['delivery']['licence']; ?>
" class="txtBox" /></span></p>
            <p><input name="invoice_type"  <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 2): ?>checked<?php endif; ?> type="radio" value="2" />&nbsp;单位&nbsp;<input  name="invoice[2]" id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span id="Tariff" <?php if ($this->_tpl_vars['delivery']['invoice_type'] != 2): ?>style="display:none"<?php endif; ?>> 税号：<input type="text" name="Tariff" id="Tariff_name" value="<?php echo $this->_tpl_vars['delivery']['Tariff']; ?>
" class="txtBox" /></span>  </p>  
            <p id="invoice_con_box" <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 0): ?>style="display:none"<?php endif; ?>>发票内容：<input name="invoice_content" type="radio" value="农产品">&nbsp;农产品  <input name="invoice_content" type="radio" value="种子">&nbsp;种子   <input name="invoice_content" type="radio" value="商品明细 ">&nbsp;商品明细 </p>
        </div>
        <a class="btn_save" href="javascript:;" onclick="return  setDelivery();">保存发票信息</a> 
   </div>
   <script type="text/javascript">
    $(function(){    
    	var invoice_name = '<?php echo $this->_tpl_vars['delivery']['invoice_name']; ?>
';
    	var invoce_content = '<?php echo ((is_array($_tmp=@$this->_tpl_vars['delivery']['invoice_content'])) ? $this->_run_mod_handler('default', true, $_tmp, '农产品') : smarty_modifier_default($_tmp, '农产品')); ?>
'; 
    	
    	$(":radio[name='invoice_type']").click(function(){
    		if(this.value>0)
    			{
    			  $("#invoice_con_box").show();
    			}else{
    			  $("#invoice_con_box").hide();
    			}
		  if(this.value == 1){
			$('#licence').show();
		  }else{
			$('#licence').hide();
		  }
		  if(this.value==2){
			$('#Tariff').show();
		  }else{
			$('#Tariff').hide();
		  }

    	})
		
		$(":radio[name='warehouse_type']").click(function(){
    		if(this.value>0)
    			{
    			  $("#warehouse_box").show();
    			}else{
    				 $("#warehouse_box").hide();
    			}
    	})
    	
    	$(":radio[name='invoice_content']").each(function(){
    		if(this.value == invoce_content)
    			{
    			  this.checked=true;
    			  return;
    			 }
    	});
    	
        <?php if ($this->_tpl_vars['delivery']['invoice_type'] == 1): ?>
            $('#invoice_company').iClear({enter: $(':submit')});
            $('#invoice_person').val(invoice_name);
        <?php elseif ($this->_tpl_vars['delivery']['invoice_type'] == 2): ?>
            $('#invoice_person').iClear({enter: $(':submit')});
            $('#invoice_company').val(invoice_name);
        <?php else: ?>
          $('#invoice_company').iClear({enter: $(':submit')});
          $('#invoice_person').iClear({enter: $(':submit')});
        <?php endif; ?>
   });
 </script>   
</form>