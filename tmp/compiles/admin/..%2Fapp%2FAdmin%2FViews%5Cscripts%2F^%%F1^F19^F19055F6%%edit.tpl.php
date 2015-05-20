<?php /* Smarty version 2.6.19, created on 2014-10-24 18:39:14
         compiled from offers/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'offers/edit.tpl', 28, false),)), $this); ?>
<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.css');
loadCss('/scripts/dhtmlxSuite/dhtmlxWindows/skins/dhtmlxwindows_dhx_blue.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxWindows/dhtmlxwindows.js', createWin);
var win;
function createWin()
{
    win = new dhtmlXWindows();
    win.setImagePath("/scripts/dhtmlxSuite/dhtmlxWindows/imgs/");
}
</script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" onsubmit="return editSubmit()">
<div class="title"><?php echo $this->_tpl_vars['title']; ?>
</div>
<div class="content">
<div class="sub_title">
    [ <a href="javascript:fGo()" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>'index',)));?>')">返回活动列表</a> ]
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
<input type="hidden" name="offers_type" value="<?php echo $this->_tpl_vars['offersType']; ?>
" />
<tr>
<td width="10%">活动名称 * </td>
<td width="40%"><input type="text" name="offers_name" size="25" maxlength="50" value="<?php echo $this->_tpl_vars['offers']['offers_name']; ?>
" msg="请填写活动名称" />    
     活动别名：<input type="text" name="as_name" size="25" maxlength="50" value="<?php echo $this->_tpl_vars['offers']['as_name']; ?>
" msg="请填写活动别名" /> </td>
<td width="10%">是否可使用礼券</td>
<td width="40%">
<?php echo smarty_function_html_radios(array('name' => 'use_coupon','options' => $this->_tpl_vars['canUseCoupon'],'checked' => $this->_tpl_vars['offers']['use_coupon'],'separator' => "<span style='padding-left:5px'></span>"), $this);?>

</td>
<!--
<td width="10%">适用会员等级</td>
<td width="40%">
</td>
-->
</tr>
<tr>
<td width="10%">开始日期 * </td>
<td width="40%">
  <span style="float:left;width:180px;">
    <input type="text" name="from_date" id="from_date" size="24" value="<?php echo $this->_tpl_vars['offers']['from_date']; ?>
"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
  </span>
</td>
<td width="10%">截至日期</td>
<td width="40%">
  <span style="float:left;width:180px;">
  <input type="text" name="to_date" id="to_date" size="24" value="<?php echo $this->_tpl_vars['offers']['to_date']; ?>
"  class="Wdate" onClick="WdatePicker()" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
  </span>
</td>
</tr>
</tbody>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "offers/".($this->_tpl_vars['offersType']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<div class="submit"><input type="submit" id="dosubmit" value="确定" /> <input type="reset" value="重置" /></div>
</form>
<script>
/**
 * 打开商品设置窗口
 *
 * @param    string    intype         输入类型(input,checkbox)
 * @param    string    discountInput  保存设置的隐藏输入框名称及ID
 * @param    int       show           是否显示分类及全局配置
 * @return   void
 */
function openAllGoodsWin(intype, discountInput)
{
	var goods = win.createWindow("allGoodsWin", 300, 150, 610, 380);
	goods.setText("商品设置");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-all-goods/id/<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
/job/search/intype/" + intype + '/discountinput/' + discountInput + '/offersType/<?php echo $this->_tpl_vars['offersType']; ?>
/discountvalue/' + JSON.encode($(discountInput).value), true);
}

/**
 * 打开组合商品设置窗口
 *
 * @param    string    intype         输入类型(input,checkbox)
 * @param    string    discountInput  保存设置的隐藏输入框名称及ID
 * @param    int       show           是否显示分类及全局配置
 * @return   void
 */
function openAllGroupGoodsWin(intype, discountInput)
{
	var goods = win.createWindow("allGroupGoodsWin", 300, 150, 610, 380);
	goods.setText("组合商品设置");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-all-group-goods/id/<?php echo $this->_tpl_vars['offers']['offers_id']; ?>
/job/search/intype/" + intype + '/discountinput/' + discountInput + '/offersType/<?php echo $this->_tpl_vars['offersType']; ?>
/discountvalue/' + JSON.encode($(discountInput).value), true);
}

function storeDiscount(discountInput)
{
    var msg = '';
    var tab = $('distcountForm');
    if (tab.getElement('input[name=discount]').value != '' && !/^[\d|\.|,]+$/.test(tab.getElement('input[name=discount]').value)) {
        msg += '请输入正确的格式!\n';
    }
    
    var catDiscount = tab.getElements('input[name^=catDiscount]');
    for (var i = 0; i < catDiscount.length; i++)
    {
        if (catDiscount[i].value != '' && !/^[\d|\.|,]+$/.test(catDiscount[i].value)) {
            msg += '请输入正确的格式!\n';
            break;
        }
    }
    
    var goodsDiscount = tab.getElements('input[name^=goodsDiscount]');
    for (var i = 0; i < goodsDiscount.length; i++)
    {
        if (goodsDiscount[i].value != '' && !/^[\d|\.|,]+$/.test(goodsDiscount[i].value)) {
            msg += '请输入正确的格式!\n';
            break;
        }
    }
    
    if (msg.length > 0) {
        alert(msg);
        return false;
    } else {
        
        if ($(discountInput)) {
            $(discountInput).value=$('distcountForm').toQueryString();
            return true;
        }
    }
}

function closeAllGoodsWin()
{
    win.window("allGoodsWin").close();
}

function closeAllGroupGoodsWin()
{
    win.window("allGroupGoodsWin").close();
}
var ob_button = null;
function openGoodsWin(intype, offersType,ob,index)
{
	ob_button = ob;
	var goods = win.createWindow("goodsWin", 300, 150, 610, 500);
	goods.setText("选择商品");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-goods/intype/" + intype + '/offersType/' + offersType+"/index/"+index, true);
}

function openGroupGoodsWin(intype, offersType)
{
	var goods = win.createWindow("goodsWin", 300, 150, 610, 500);
	goods.setText("选择商品");
	goods.button("minmax1").hide();
	goods.button("park").hide();
	goods.denyResize();
	goods.denyPark();
	goods.setModal(true);
	goods.attachURL("/admin/offers/select-group-goods/intype/" + intype + '/offersType/' + offersType, true);
}

function closeGoodsWin()
{
    win.window("goodsWin").close();
}

function addDiscountGoods(intype, goodsId, goodsSn, goodsName, goodPrice, price_limit, offers_type)
{
    var offers_array = new Array('exclusive', 'fixed', 'group-exclusive', 'price-exclusive');
    var goodsExists = $('discountGoods').getElements('input[name^=goodsDiscount]');
    for (var i = 0; i < goodsExists.length; i++)
    {
        if (goodsExists[i].name == 'goodsDiscount[' + goodsId + ']') {
            return;
        }
    }

	var p = document.createElement("p");
	var input;
    var limit_str = '';
	if (intype == 'text') {
        if(isInArray(offers_array, offers_type)) {
            limit_str = parseFloat(price_limit)  == 0 ? '无限价' : price_limit;
            limit_str = '保护价：' + limit_str;
            input = '<input type="text" name="goodsDiscount[' + goodsId + ']" id="' + goodsId + '" value="'+price_limit+'" size="15" onchange="changePrice('+goodsId+', '+price_limit+')" />';
        } else {
	        input = '<input type="text" name="goodsDiscount[' + goodsId + ']" value="0.00" size="15" />';
        }
	} else if (intype == 'checkbox') {
	    input = '<input type="checkbox" name="goodsDiscount[' + goodsId + ']" value="1" checked=true />';
	}

    
    

	p.innerHTML = '<a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/images/admin/delete.png" border="0" /></a><span style="padding-right:8px">' + goodsSn + '</span><span style="padding-right:8px">' + goodsName + '</span><span style="padding-right:8px">' + goodPrice + '</span>' + input + limit_str;
	$('discountGoods').appendChild(p);
	$('select_button_' + goodsId).style.backgroundColor='#ccc';
	$('select_button_' + goodsId).value='已选';
}

function addGift(intype, goodsId, goodsSn, goodsName, goodPrice, price_limit, offers_type,s_index)
{
	     
	    var offers_array = new Array('exclusive', 'fixed', 'group-exclusive', 'price-exclusive');
	    var goodsExists = $(ob_button).getNext('div.gifbox').getElements('input[name^=goods]');
	    for (var i = 0; i < goodsExists.length; i++)
	    {
	        if (goodsExists[i].name == 'goods['+s_index+'][gift]['+ goodsId + ']') {
	        	alert("已选择！");
	            return;
	        }
	    }
	   

		var p = document.createElement("p");
		var input;
	    var limit_str = '';
	    input = '数量：<input type="text" name="goods['+s_index+'][gift][' + goodsId + ']" value="1" size="15" />';	    

		p.innerHTML = '<a href="javascript:fGo()" onclick="this.parentNode.parentNode.removeChild(this.parentNode);" title="删除"><img src="/images/admin/delete.png" border="0" /></a><span style="padding-right:8px">' + goodsSn + '</span><span style="padding-right:8px">' + goodsName + '</span><span style="padding-right:8px">' + goodPrice + '</span>' + input + limit_str;
		$(ob_button).getNext('div.gifbox').appendChild(p);
		$('select_button_' + goodsId).style.backgroundColor='#ccc';
		$('select_button_' + goodsId).value='已选';
}

function isInArray(arr,val){
    var str=','+arr.join(",")+",";
    return str.indexOf(","+val+",")!=-1;
}

function changePrice(obj_str, price_limit)
{
   var obj = document.getElementById(obj_str);
   if (isNaN(obj.value)) {
       alert('价格不正确');
       obj.value = price_limit;
       return false;
   }
   if (parseFloat(price_limit) > 0 && parseFloat(obj.value) < parseFloat(price_limit)) {
        alert('价格不能小于保护价');
        obj.value=price_limit;
        return false;
   }
    
}

function removeDiscountGoods(obj)
{
    $('discountGoods').removeChild(obj.parentNode);
}

function editSubmit()
{
    var frm = $('myForm');
    var msg = '';
    var rank = false;
    //var rankBox = frm.getElements('input[name^=offers_rank]');
    
    if (frm.offers_name.value == '') {
        msg += '请填写活动名称!\n';
    }
    
    /*
    for (var i = 0; i < rankBox.length; i++)
    {
        if (rankBox[i].checked == true) {
            rank = true;
            break;
        }
    }
    
    if (rank == false) {
        msg += '请选择适用会员等级!\n';
    }
    */
    
    if (frm.from_date.value == '') {
        msg += '请选择开始日期!\n';
    }
    
    if (typeof(offersSubmit) == 'function') {
        msg += offersSubmit();
    }
    
    if (msg.length > 0) {
        alert(msg);
        return false;
    } else {
        return true;
    }
}
var cur_start_date = '';
function changeStartToDate(obj) {
    if (!cur_start_date) {
        cur_start_date = $('from_date').value.substr(0,10);
    }
    $('from_date').value = cur_start_date + ' ' + obj.value;
}


var cur_to_date = '';
function changeToDate(obj) {
    if (!cur_to_date) {
        cur_to_date = $('to_date').value.substr(0,10);
    }
    $('to_date').value = cur_to_date + ' ' + obj.value;
}
</script>