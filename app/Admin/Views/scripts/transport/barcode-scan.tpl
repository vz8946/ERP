<style type="text/css">
.input-text-focus{
	background-color:#FFFC00 !important;
}
input[type="text"]:focus{
	background-color:#FFFC00;
}
</style>
<div class="title">配送管理 -&gt; 出库产品扫描</div>
<div class="content">
<form id="myform">
<div style="border-bottom:1px solid #CCC;">
<table width="100%" style="border-right:1px solid #CCC;">
<tr bgcolor="#F0F1F2">
  <th width="15%" height="30">单据条码：</th>
  <td width="35%" height="30">
    <input type="text" name="bill_no" id="bill_no" size="25" onkeydown="inputBillNo()">
  </td>
  <th width="15%" height="30">产品条码：</th>
  <td height="30">
    <input type="text" name="barcode" id="barcode" size="20" onkeydown="inputBarCode()">
  </td>
</tr>
<tr>
  <th height="30">出库类型：</th>
  <td height="30" id="bill_type"></td>
  <th height="30">运输单号：</th>
  <td height="30">
    <input type="text" name="logistic_no" id="logistic_no" size="20" onkeydown="inputLogisticNo()">
    <span id="order_logistic_no"></span>
  </td>
</tr>
<tr bgcolor="#F0F1F2">
  <th height="30">制单日期：</th>
  <td height="30" id="add_time" colspan="3"></td>
</tr>
</table>
<br><br>

<table cellpadding="0" cellspacing="0" border="0" class="table">
<thead>
  <tr>
    <td><b>产品名称</b></td>
    <td><b>产品规格</b></td>
    <td><b>产品编号</b></td>
    <td><b>产品条码</b></td>
    <td><b>产品数量</b></td>
    <td><b>扫描数量</b></td>
  </tr>
</thead>
<tbody id="list"></tbody>
</table>
</div>
</div>
<div style="width:1px" id="fo" onkeydown="closeDiv()"></div>
<div style="text-align:center">
<input type="button" value="清空" onclick="emptyBill(2)">
</div>

<script>
function inputBillNo()
{
    var e = getEvent();
    var value = $('bill_no').value;
    if (e.keyCode == 13 && value != '') {
        $('order_logistic_no').value = '';
        new Request({
            url:'/admin/transport/get-scan-bill/bill_no/' + value,
    	    onSuccess:function(msg){
                if (msg.substring(0, 5) == 'error') {
                    hint(msg, $('bill_no'));
                    emptyBill(2);
                    return false;
                }
                createBillInfo(msg);
    		},
    		onError:function() {
    			alert("网络繁忙，请稍后重试");
    		}
	    }).send();
    }
}

function inputBarCode()
{
    var e = getEvent();
    var value = $('barcode').value;
    if (e.keyCode == 13 && value != '') {
        $('barcode').value = '';
        var obj = $('number_' + value);
        if (obj == null) {
            hint('error barcode', $('barcode'));
            return false;
        }
        
        if (parseInt(obj.value) >= parseInt($('init_number_' + value).value)) {
            hint('error more number', $('barcode'));
        }
        else {
            obj.value = parseInt(obj.value) + 1;
        }
        
        obj.readOnly = false;
        
        isFinishProductInput();
    }
}

function inputLogisticNo()
{
    var e = getEvent();
    var value = $('logistic_no').value;
    if (e.keyCode == 13 && value != '') {
        $('logistic_no').value = '';
        if (value != $('order_logistic_no').innerHTML) {
            hint('error wrong logistic no', $('logistic_no'));
            return false;
        }
        
        doScan();
    }
}

function isFinishProductInput()
{
    for (var i = 0; i < bill.detail.length; i++) {
        var detail = bill.detail[i];
        if (parseInt($('number_' + detail.ean_barcode).value) < parseInt($('init_number_' + detail.ean_barcode).value)) {
            return false;
        }
    }
    
    if ($('order_logistic_no').innerHTML != '') {
        $('logistic_no').focus();
    }
    else {
        doScan();
    }
}

function hint(msg, obj)
{
    if (msg == 'error empty logistic no') {
        msg = '销售单的运输单号为空!';
    }
    else if (msg == 'error bill no') {
        msg = '单据编号不存在!';
    }
    else if (msg == 'error send') {
        msg = '发货失败!';
    }
    else if (msg == 'error return money') {
        msg = '销售单发生退款!';
    }
    else if (msg == 'error change status') {
        msg = '销售单状态改变!';
    }
    else if (msg == 'error barcode') {
        msg = '产品条码不存在!';
    }
    else if (msg == 'error more number') {
        msg = '产品数量超出!';
    }
    else if (msg == 'error wrong logistic no') {
        msg = '运输单号不匹配!';
    }
    else if (msg == 'error scan status1') {
        msg = '该单据已通过产品扫描!';
    }
    else if (msg == 'error scan status2') {
        msg = '该单据未通过产品扫描!';
    }
    
    focusObject = obj;
    $('fo').focus();
    
    window.top.alertBox.init('msg="' + msg + '",url="",ms="","";');
}

function createBillInfo(data)
{
    emptyBill(1);
    
    var data = JSON.decode(data);
    bill = data;
    
    $('bill_type').innerHTML = data.data.bill_type + ' ' + data.data.shop_name;
    $('order_logistic_no').innerHTML = data.data.logistic_no;
    $('add_time').innerHTML = data.data.add_time;
    
    var obj = $('list');
    
    for (var i = 0; i < data.detail.length; i++) {
        var tr = obj.insertRow(0);
        var detail = data.detail[i];
        tr.id = 'sid' + detail.id;
    	for (var j = 0; j <= 5; j++) {
            tr.insertCell(j);
        }
        if (detail.ean_barcode == '' || detail.ean_barcode == null) {
            detail.ean_barcode = detail.product_sn;
        }
        tr.cells[0].innerHTML = detail.product_name;
        tr.cells[1].innerHTML = detail.goods_style;
        tr.cells[2].innerHTML = detail.product_sn;
        tr.cells[3].innerHTML = detail.ean_barcode;
        tr.cells[4].innerHTML = detail.number + '<input type="text" id="init_number_' + detail.ean_barcode  + '" value="' + detail.number + '" style="display:none">';
        tr.cells[5].innerHTML = '<input type="text" id="number_' + detail.ean_barcode  + '" value="0" size="3" readonly onblur="isFinishProductInput()">';
    }
    
    $('barcode').value = '';
    $('barcode').focus();
}

function doScan()
{
    new Request({
        url:'/admin/transport/scan-bill-status/bill_no/' + $('bill_no').value,
        onSuccess:function(msg){
            if (msg.substring(0, 5) == 'error') {
                hint(msg, null);
                return false;
            }
            else {
                emptyBill(2);
                $('bill_no').focus();
            }
        },
        onError:function() {
    	    alert("网络繁忙，请稍后重试");
        }
    }).send();
}

function emptyBill(flag)
{
    if (flag == 2) {
        $('bill_no').value = '';
    }
    $('bill_type').innerHTML = '';
    $('add_time').innerHTML = '';
    $('order_logistic_no').innerHTML = '';
    $('logistic_no').value = '';
    
    var obj = $('list');
    var length = obj.rows.length;
    for (i = 0; i < length; i++) {
        obj.deleteRow(0);
    }
}

function getEvent()
{  
    if (document.all)   return window.event;    
    func = getEvent.caller;
    while(func != null) {
        var arg0 = func.arguments[0]; 
        if (arg0) { 
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent) || (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {  
                return arg0; 
            } 
        } 
        func = func.caller; 
    }
    
    return null; 
}

function closeDiv()
{
    var e = getEvent();
    if (e.keyCode == 32) {
        window.top.alertBox.closeDiv();
        if (focusObject != null) {
            focusObject.focus();
        }
    }
    e.returnValue = false;
}


$('bill_no').focus();

var bill = null;

var focusObject = null;

</script>