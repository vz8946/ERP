<?php /* Smarty version 2.6.19, created on 2014-10-30 13:25:53
         compiled from member/address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'member/address.tpl', 22, false),)), $this); ?>
<div class="member">

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <div class="memberright">
     <div class="memberddbg">
	 <p>	您最多可填写<span class="highlight">5</span>个收货地址 </p>
	</div>
 <div class="righttitle"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/images/shop/member_address.png"></div>
 <div class="memberjebg" >

         <form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" onsubmit="return addressSubmit()" target="ifrmSubmit">
        	<table width="742" cellpadding="0" cellspacing="1"  class="publictable tborder"  id="address_form" >
                <input type="hidden" name="address_number" id="address_number" value="5" />
                <?php if ($this->_tpl_vars['memberAddress']): ?>
                <?php $_from = $this->_tpl_vars['memberAddress']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['address'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['address']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['address']):
        $this->_foreach['address']['iteration']++;
?>
                <tbody>
                    <tr>
                        <td width="12%" height="30"><strong>配送区域</strong></td>
                        <td width="40%" height="30">
                            <select name="address[province][]" onchange="getArea(this)">
                                <option value="">请选择省</option>
                    	        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['province'],'selected' => $this->_tpl_vars['address']['province_id']), $this);?>

                            </select>
                            <select name="address[city][]" onchange="getArea(this)">
                                <option value="">请选择市</option>
                    	        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['address']['city_option'],'selected' => $this->_tpl_vars['address']['city_id']), $this);?>

                            </select>
                            <select name="address[area][]">
                     			 <option value="">请选择区</option>
                    	         <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['address']['area_option'],'selected' => $this->_tpl_vars['address']['area_id']), $this);?>

                            </select><a style="color: #FF3300;">*</a>
						</td>
                        <td width="12%" height="30"><strong>收货人姓名</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[consignee][]" size="25" maxlength="30" value="<?php echo $this->_tpl_vars['address']['consignee']; ?>
" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    
                    <tr>
                         <td width="12%" height="30"><strong>详细地址</strong></td>
                   	  <td width="40%" height="30"><input type="text" name="address[address][]" size="30" maxlength="100" value="<?php echo $this->_tpl_vars['address']['address']; ?>
" class="istyle"/><a style="color: #FF3300;">*</a></td>
                         <td width="12%" height="30"><strong>手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[mobile][]" size="25" maxlength="20" value="<?php echo $this->_tpl_vars['address']['mobile']; ?>
" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;话</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[phone][]" size="30" maxlength="40" value="<?php echo $this->_tpl_vars['address']['phone']; ?>
" class="istyle"/></td>
                        <td width="12%" height="30">&nbsp;</td>
                        <td width="36%" height="30">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="50" >    
                            <input type="hidden" name="address[address_id][]" value="<?php echo $this->_tpl_vars['address']['address_id']; ?>
" />
                            <input type="button" name="add" value="添加" onclick="addAddress(this)" class="buttons" /></td><td height="50">
                            <input type="button" name="delete" value="删除" onclick="removeAddress(this)" class="buttons"  />
                        </td>   <td height="50">&nbsp; </td> <td height="50">&nbsp; </td>
                    </tr>
                 </tbody>
                <?php endforeach; endif; unset($_from); ?>
                <?php else: ?>
               <tbody>
                    <tr>
                        <td width="12%" height="30"><strong>配送区域</strong></td>
                        <td width="40%" height="30">
                            <select name="address[province][]" onchange="getArea(this)">
                                <option value="">请选择省</option>
                    	        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['province']), $this);?>

                            </select>
                            <select name="address[city][]" onchange="getArea(this)">
                                <option value="">请选择市</option>
                            </select>
                            <select name="address[area][]">
                                <option value="">请选择区</option>
                      </select><a style="color: #FF3300;">*</a>
                      </td>
                        <td width="12%" height="30"><strong>收货人姓名</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[consignee][]" size="25" maxlength="40" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>详细地址</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[address][]" size="30" maxlength="100" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                        <td width="12%" height="30"><strong>手机</strong></td>
                      <td width="36%" height="30"><input type="text" name="address[mobile][]" size="25" maxlength="20" value="" class="istyle"/><a style="color: #FF3300;">*</a></td>
                    </tr>
                    <tr>
                        <td width="12%" height="30"><strong>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</strong></td>
                      <td width="40%" height="30"><input type="text" name="address[phone][]" size="30" maxlength="40" value="" class="istyle"/></td>
                        <td width="12%" height="30">&nbsp;</td>
                        <td width="36%" height="30">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="50" >
                            <input type="hidden" name="address[address_id][]" value="" />
                            <input type="button" name="add" value="添加" onclick="addAddress(this)" class="buttons" /></td><td height="50">
                            <input type="button" name="delete" value="删除" onclick="removeAddress(this)" class="buttons"/>   
					   </td>
					   <td height="50">&nbsp;</td> <td height="50">&nbsp; </td>
                    </tr>
                    </tbody>
                    <?php endif; ?>
                </table> 
			
           <div style="padding-top: 10px; text-align:center"><input type="submit" name="dosubmit" id="dosubmit" value="提交修改" class="buttons"/></div>
            </form>
    </div>
  </div>
</div>

<iframe src="about:blank" style="width:0px;height:0px" frameborder="0" name="ifrmSubmit" id="ifrmSubmit"></iframe>

<script type="text/javascript">
//验证表单
function addressSubmit()
{
	var selectArea = $("#myForm select[name^='address']");
    for (var i = 0; i < selectArea.length; i++)
    {
        if (selectArea[i].value == '' || /\D+/.test(selectArea[i].value)) {
            alert('请选择配送地区！');
            return false;
        }
    }
    
	var inputAddress = $("#myForm input[name^='address']");
    for (var i = 0; i < inputAddress.length; i++)
    {
		if ($(inputAddress[i].name+":contains('consignee')") == true && inputAddress[i].value == '') {
            alert('请填写收货人！');
            return false;
		} else if ($(inputAddress[i].name+":contains('address')") == true && inputAddress[i].value == '') {	
            alert('请填写详细地址！');
            return false;
		} else if ($(inputAddress[i].name+":contains('phone')") == true && inputAddress[i].value == '' && !Check.isTel(inputAddress[i].value)) {
            alert('请填写正确的电话号码！');
            return false;
		} else if ($(inputAddress[i].name+":contains('mobile')") == true && inputAddress[i].value == '' && !Check.isMobile(inputAddress[i].value)) {
            alert('请填写正确的手机号码！');
            return false;
        }
    }
	
	$('#dosubmit').attr('value','提交中..');
	$('#dosubmit').attr('disabled',true);
    return true;
}

//联动
function getArea(id){
	var value=id.value;
	$(id).parent().children('select:last')[0].options.length = 1;
	$(id).next('select')[0].options.length=1;
	$.ajax({
		url:'<?php echo $this -> callViewHelper('url', array(array('action'=>'area',)));?>',
		data:{id:value},
		dataType:'json',
		success:function(msg){
			var htmloption='';
			$.each(msg,function(key,val){
				htmloption+='<option value="'+key+'">'+val+'</option>';
			})
			$(id).next('select').append(htmloption);
		}
	})
}

//添加地址框
function addAddress(node){
	if($('#address_form tbody').length>=$('#address_number').val()){
		alert('您最多只能有' + $('#address_number').val() + '个收货地址!');
        return false;
	}
	$('#address_form').append($('#address_form tbody:last').clone());
	$("#address_form tbody:last input[type='text']").val('');
	$("#address_form tbody:last select").val('');
	$("#address_form tbody:last input[type='hidden']").remove();
	$("#address_form tbody:last select[name^='address[city]']").html('<option value="">请选择市</option>');
	$("#address_form tbody:last select[name^='address[area]']").html('<option value="">请选择市</option>');
}


//从html删除一个地址输入域
function removeAddress(node){
	if($('table tbody').length==1){
		alert("最后一条收货地址不能删除！");
	}
	if($('table tbody').length>1){
		$(node).parent().parent().parent().remove();
	}
}
</script>