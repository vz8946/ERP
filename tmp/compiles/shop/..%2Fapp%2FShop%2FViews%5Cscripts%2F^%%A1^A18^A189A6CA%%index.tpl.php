<?php /* Smarty version 2.6.19, created on 2014-10-30 10:42:37
         compiled from flow/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'flow/index.tpl', 35, false),array('modifier', 'number_format', 'flow/index.tpl', 60, false),)), $this); ?>
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
    	<span class="logo"><a href="/" title="回到首页"><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/logo.jpg" width="225" height="101" /></a></span>
        <ul>
        	<li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step01_current.jpg" width="183" height="43" /></li>
            <li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step02.jpg" width="183" height="43" /></li>
            <li><img src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/step03.jpg" width="183" height="43" /></li>
        </ul>
    </div>
  	<div class="title_cart">
   	  <h2>我的购物车</h2>
   	 <?php if (! $this->_tpl_vars['auth']): ?>
       <span>现在 <a href="javascript:;" onClick="checkBuyWay();">登录</a>&nbsp;您购物车中的商品将被永久保存</span>
     <?php endif; ?>
    </div>       
        
	<form id="formCart" name="formCart" method="post" onSubmit="return false;">
	  <table  border="0" width="100%" id="cart_list" class="cart_detail">
	  	<tbody>
		<tr>
            <th class="t_pro_title" colspan="2">商品</th>
            <th>单价</th>
	        <th>购买数量</th>
	        <th>赠送积分</th>
	        <th>小计</th>
	        <th>操作</th>
          </tr>	
		<?php $_from = $this->_tpl_vars['products']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
		<tr <?php if ($this->_tpl_vars['data']['goods_gift']): ?> class="haveinside" <?php endif; ?>>
		 <td colspan="2" class="t_pro">
			<input type="hidden" name="ids[]" id="ids_<?php echo $this->_tpl_vars['data']['product_id']; ?>
" value="<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
			<a href="<?php if ($this->_tpl_vars['data']['tuan_id']): ?>/tuan/view/id/<?php echo $this->_tpl_vars['data']['tuan_id']; ?>
<?php else: ?>/goods-<?php echo $this->_tpl_vars['data']['goods_id']; ?>
.html<?php endif; ?>" target="_blank"><img  style="border:1px solid #d0d0d0;"src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_60_60.') : smarty_modifier_replace($_tmp, '.', '_60_60.')); ?>
" border="0" width="60" height="60"></a>
			
			<a style="color:#3366cc;" href="<?php if ($this->_tpl_vars['data']['tuan_id']): ?>/tuan/view/id/<?php echo $this->_tpl_vars['data']['tuan_id']; ?>
<?php else: ?>/goods-<?php echo $this->_tpl_vars['data']['goods_id']; ?>
.html<?php endif; ?>"  target="_blank"><?php echo $this->_tpl_vars['data']['goods_name']; ?>
<?php if ($this->_tpl_vars['data']['needLogin']): ?><br><font color="#999999">(该商品有活动优惠价，请先<a href="/auth/login/goto/L2Zsb3c=">登录</a>以获得最新价格)</font><?php endif; ?></a>
			<p style="color:red;" id="outofstock_<?php echo $this->_tpl_vars['data']['product_id']; ?>
">
			<?php if ($this->_tpl_vars['data']['outofstock']): ?>（此商品暂时缺货）<?php endif; ?>
			<?php if ($this->_tpl_vars['data']['onsale'] == '1'): ?>（此商品已经下架）<?php endif; ?>
			</p>
			</td>
			<td>
			<div><?php if ($this->_tpl_vars['data']['price_before_discount'] || $this->_tpl_vars['data']['tuan_id']): ?>原价<span><?php echo $this->_tpl_vars['data']['price_before_discount']; ?>
<?php endif; ?></span></div>
			<div><?php if ($this->_tpl_vars['data']['tuan_id']): ?>团购价<?php elseif ($this->_tpl_vars['data']['price_before_discount']): ?><?php echo $this->_tpl_vars['data']['remark']; ?>
折扣价<?php endif; ?><span id="base_price_<?php echo $this->_tpl_vars['data']['product_id']; ?>
"><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo $this->_tpl_vars['data']['org_price']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['price']; ?>
<?php endif; ?></span><?php if ($this->_tpl_vars['data']['price_before_discount']): ?>[<?php echo $this->_tpl_vars['data']['member_discount']; ?>
折]<?php endif; ?></div>
			</td>
			<td class="s_num">
            <?php if ($this->_tpl_vars['data']['allow_modify'] == 1): ?>
				<a class="cut" href="javascript:;" onClick="selNumLess('<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['suffix']; ?>
')">-</a>
				<input  type="text" id="buy_number_<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['suffix']; ?>
" value="<?php echo $this->_tpl_vars['data']['number']; ?>
" size="2" onBlur="setGoodsNumber('<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['suffix']; ?>
',this.defaultValue,'<?php echo $this->_tpl_vars['data']['limit_number']; ?>
')" onKeyUp="this.value=this.value.replace(/\D/g,'');" onafterpaste="this.value=this.value.replace(/\D/g,'');"/>
				<a  class="plus" href="javascript:;"  onclick="selNumAdd('<?php echo $this->_tpl_vars['data']['product_id']; ?>
_<?php echo $this->_tpl_vars['data']['suffix']; ?>
', '<?php echo $this->_tpl_vars['data']['limit_number']; ?>
')">+</a>
            <?php else: ?>
           		<?php echo $this->_tpl_vars['data']['number']; ?>
<input type="hidden" id="buy_number_<?php echo $this->_tpl_vars['data']['product_id']; ?>
" value="<?php echo $this->_tpl_vars['data']['number']; ?>
" />
            <?php endif; ?>
            <?php if ($this->_tpl_vars['data']['only_fix_num_messge']): ?>
            <br><<?php echo $this->_tpl_vars['data']['only_fix_num_messge']; ?>
>
            <?php endif; ?>
            </td>
	    <td><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo $this->_tpl_vars['data']['org_price']*$this->_tpl_vars['data']['number']; ?>
<?php else: ?><?php echo $this->_tpl_vars['data']['price']*$this->_tpl_vars['data']['number']; ?>
<?php endif; ?></td>
	    <td  class="xiaoji"  id="change_price_<?php echo $this->_tpl_vars['data']['product_id']; ?>
"><?php if ($this->_tpl_vars['data']['show_org_price']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['org_price']*$this->_tpl_vars['data']['number'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['price']*$this->_tpl_vars['data']['number'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
<?php endif; ?></td>
	    <td>
	      <a  href="javascript:void(0);" onClick="favGoods(this,<?php echo $this->_tpl_vars['data']['goods_id']; ?>
);">收藏</a>&nbsp;|&nbsp;
	     <a  href="/flow/del/product_id/<?php echo $this->_tpl_vars['data']['product_id']; ?>
/number/<?php echo $this->_tpl_vars['data']['number']; ?>
" onClick="return confirm('你确定要删除“<?php echo $this->_tpl_vars['data']['goods_name']; ?>
”吗？');">删除</a>
	    </td>
		</tr>	
		<?php echo $this->_tpl_vars['data']['goods_gift']; ?>
	
		<?php endforeach; endif; unset($_from); ?>	
			
		
			
		<?php if ($this->_tpl_vars['products']['other']): ?>
		    <?php $_from = $this->_tpl_vars['products']['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other']):
?>
		        <?php echo $this->_tpl_vars['other']; ?>
 
		    <?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>			
		
		<?php if ($this->_tpl_vars['data']['other']): ?>
		    <?php $_from = $this->_tpl_vars['data']['other']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['other']):
?>
		        <?php echo $this->_tpl_vars['other']; ?>
  
		    <?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
			
	</tbody>
	</table>
	
	  </form>
	  
	  
	  <div class="cart_total">
    <p>运费说明：全国统一运费10元，全场满199元包邮</p>
    <?php if ($this->_tpl_vars['show_msg_freight']): ?>
	<p style="clear:both;"><font color="red" style="font-weight:500">提示： 再购买<?php echo $this->_tpl_vars['goods_freight_amount']; ?>
元就可以免运费 ！</font></p>
	<?php endif; ?>	
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td align="right" ><em><b id="total"><?php echo $this->_tpl_vars['products']['number']; ?>
</b></em>件商品，总金额：</td>          
          <td align="right" width="100"><b><em id="goods_amount"><?php echo ((is_array($_tmp=$this->_tpl_vars['products']['goods_amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</em></b> 元</td>
        </tr>               
        <?php $_from = $this->_tpl_vars['products']['offers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tmp']):
?>
		<?php $_from = $this->_tpl_vars['tmp']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
		<?php if ($this->_tpl_vars['o']['offers_type'] == 'minus'): ?>
		   <tr>
		<td align="right">活动<?php echo $this->_tpl_vars['o']['offers_name']; ?>
：</td>   
		<td align="right" width="100"><b><em id="change_price_tmp_<?php echo $this->_tpl_vars['o']['offers_id']; ?>
"><?php echo $this->_tpl_vars['o']['price']; ?>
</em></b>元</td>
		<tr>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php endforeach; endif; unset($_from); ?>
        <tr>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><span>总计（不含运费）：</span></td>
          <td align="right" width="100"><b><em ><?php echo ((is_array($_tmp=$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
</em> </b>元</td>
        </tr>
      </tbody></table>
    </div>
	  
	  <div class="op_cart"> 
    	<a class="fl mr10" href="/"><img width="94" height="36" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/btn_continue.jpg"></a>     	
    	<a href="/flow/clear" class="fl mt5"  onclick="return confirm('你确定要清空购物车吗？');"><b>清空购物车</b></a>
    	
    	<a class="fr"  href="javascript:;" onClick="checkBuyWay();"><img width="132" height="31" src="<?php echo $this->_tpl_vars['_static_']; ?>
/images/cart/btn_balance.jpg"></a> 
    </div>
	  
	  


<?php if ($this->_tpl_vars['links']): ?>
<div class="youlove">
   	  <h2>猜你喜欢</h2>
      <div class="loveproList">
          <div class="w_loveList">
              <ul style="width: 2760px;">             
                <?php $_from = $this->_tpl_vars['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['linksgoods']):
?>      
                <li>
                <a href="/goods-<?php echo $this->_tpl_vars['linksgoods']['goods_id']; ?>
.html" title="<?php echo $this->_tpl_vars['linksgoods']['goods_name']; ?>
" target="_blank" class="img"><img src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['linksgoods']['goods_img'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '_180_180.') : smarty_modifier_replace($_tmp, '.', '_180_180.')); ?>
"/></a>
                <b class="c1" title="<?php echo $this->_tpl_vars['linksgoods']['goods_alt']; ?>
"><?php echo $this->_tpl_vars['linksgoods']['goods_alt']; ?>
</b>
                <p><a href="/goods-<?php echo $this->_tpl_vars['linksgoods']['goods_id']; ?>
.html" target="_blank"> <?php echo $this->_tpl_vars['linksgoods']['goods_name']; ?>
</a></p>
                <p class="price"><span>￥<?php echo $this->_tpl_vars['linksgoods']['market_price']; ?>
</span><em>￥<?php echo $this->_tpl_vars['linksgoods']['price']; ?>
</em></p>
                </li>        
               <?php endforeach; endif; unset($_from); ?>
              </ul>
          </div>      
      </div>    
    </div>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "flow/fast-login.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<script type="text/javascript">
countCart();
</script>

</body>
</html>