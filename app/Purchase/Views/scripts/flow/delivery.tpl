<form method="post" id="deliveryFrom" action="/flow/set-delivery/" >  
<h2><b>配送与发票</b><span class="step-action" id="invoice_edit_action"> <a href="javascript:;" onclick="return  setDelivery();">保存配送与发票信息</a></span></h2>
<div class="invoiceInfo">
    	<p><b>配送方式</b>    &nbsp;快递配送，我们会以最快的速度为您发货，但因交通、气候等原因订单到达时间可能会有误差请谅解！</p>
        <p><b>发票信息</b>    &nbsp;选择开票，发票将会与商品一同寄出。</p>
        <div class="invoice_detail">
       	    <p><input type="radio" {{if $delivery.invoice_type eq 0}}checked{{/if}}  value="0" name="invoice_type">&nbsp;不开发票</p>
            <p><input name="invoice_type"  {{if $delivery.invoice_type eq 1}}checked{{/if}}  type="radio"  value="1" />&nbsp;个人&nbsp;<input name="invoice[1]" id="invoice_person" type="text" class="txtBox  txtBox01" value="姓名" /></p>
            <p><input name="invoice_type"  {{if $delivery.invoice_type eq 2}}checked{{/if}} type="radio" value="2" />&nbsp;单位&nbsp;<input  name="invoice[2]" id="invoice_company" type="text" class="txtBox txtBox02" value="输入单位全称" /><span>（请务必输入完整单位名称）</span></p>
            <p>发票内容：保健品</p>
        </div>
        <a class="btn_save" href="javascript:;" onclick="return  setDelivery();">保存发票信息</a> 
   </div>
   <script type="text/javascript">
    $(function(){    
    	var invoice_name = '{{$delivery.invoice_name}}';
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