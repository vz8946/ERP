<form method="post" id="deliveryFrom" action="/flow/set-delivery/" >  
<h2><b>配送与发票</b><span class="step-action" id="invoice_edit_action"> <a href="javascript:;" onclick="return  setDelivery();">保存配送与发票信息</a></span></h2>
<div class="invoiceInfo">
    	<p><b>配送方式 : </b>    <input type="radio" value="0" {{if $delivery.warehouse_id eq 0}}checked{{/if}} name="warehouse_type"> 物流配送 &nbsp;&nbsp; 
        
          <input value="1" name="warehouse_type"  {{if $delivery.warehouse_id gt 0}}checked{{/if}} type="radio"> 顾客自提</p>
          
        <p id="warehouse_box" {{if $delivery.warehouse_id eq 0}}style="display:none"{{/if}}>自提点：<select name="warehouse_id">
		<option value="">--请选择--</option>
		{{html_options options=$warehouse selected=$delivery.warehouse_id}}
	</select></p>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        <p><b>发票信息</b>    &nbsp;选择开票，发票将会与商品一同寄出。</p>
        <div class="invoice_detail">
       	    <!--<p><input type="radio" {{if $delivery.invoice_type eq 0}}checked{{/if}}  value="0" name="invoice_type">&nbsp;不开发票</p>-->
            <p><input name="invoice_type"  {{if $delivery.invoice_type neq 2}}checked{{/if}}  type="radio"  value="1" />&nbsp;个人&nbsp;<input name="invoice[1]" id="invoice_person" type="text" class="txtBox  txtBox01" value="姓名" />
            <span id="licence" {{if $delivery.invoice_type neq 1}}style="display:none"{{/if}}> 证件号码：
            <input type="text" name="licence" id="licence_name" value="{{$delivery.licence}}" class="txtBox" /></span></p>
            <p><input name="invoice_type"  {{if $delivery.invoice_type eq 2}}checked{{/if}} type="radio" value="2" />&nbsp;单位&nbsp;<input  name="invoice[2]" id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span id="Tariff" {{if $delivery.invoice_type neq 2}}style="display:none"{{/if}}> 税号：<input type="text" name="Tariff" id="Tariff_name" value="{{$delivery.Tariff}}" class="txtBox" /></span>  </p>  
            <p id="invoice_con_box" {{if $delivery.invoice_type eq 0}}style="display:none"{{/if}}>发票内容：<input name="invoice_content" type="radio" value="农产品">&nbsp;农产品  <input name="invoice_content" type="radio" value="种子">&nbsp;种子   <input name="invoice_content" type="radio" value="商品明细 ">&nbsp;商品明细 </p>
        </div>
        <a class="btn_save" href="javascript:;" onclick="return  setDelivery();">保存发票信息</a> 
   </div>
   <script type="text/javascript">
    $(function(){    
    	var invoice_name = '{{$delivery.invoice_name}}';
    	var invoce_content = '{{$delivery.invoice_content|default:'农产品'}}'; 
    	
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
    	
        {{if $delivery.invoice_type eq 1}}
            $('#invoice_company').iClear({enter: $(':submit')});
            $('#invoice_person').val(invoice_name);
        {{elseif $delivery.invoice_type eq 2}}
            $('#invoice_person').iClear({enter: $(':submit')});
            $('#invoice_company').val(invoice_name);
        {{else}}
          $('#invoice_company').iClear({enter: $(':submit')});
          $('#invoice_person').iClear({enter: $(':submit')});
        {{/if}}
   });
 </script>   
</form>