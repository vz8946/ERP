<?php /* Smarty version 2.6.19, created on 2014-11-05 08:57:37
         compiled from flow/addr.tpl */ ?>
 <div class="info_consignee current">
 <div class="arrive_addr">
 <h2><b>收货人信息</b> <span class="step-action" id="consignee_edit_action"></span></h2> 
        <div style="clear:both;" class="mb10"></div>        
         
           <?php if ($this->_tpl_vars['addressList']): ?> 
            <ul>
              	<?php $_from = $this->_tpl_vars['addressList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['data']):
?>
            	<li <?php if ($this->_tpl_vars['address_id'] == $this->_tpl_vars['data']['address_id']): ?>class="active"<?php endif; ?> id="add_item_<?php echo $this->_tpl_vars['data']['address_id']; ?>
" address="<?php echo $this->_tpl_vars['data']['address_id']; ?>
" onclick="">
                	<div class="wrap_addr">
                        <h4><b><?php echo $this->_tpl_vars['data']['consignee']; ?>
（收）</b><?php if ($this->_tpl_vars['data']['mobile']): ?><?php echo $this->_tpl_vars['data']['mobile']; ?>
 <?php else: ?> <?php echo $this->_tpl_vars['data']['phone']; ?>
<?php endif; ?> </h4>
                        <p> <?php echo $this->_tpl_vars['data']['province_name']; ?>
 <?php echo $this->_tpl_vars['data']['city_name']; ?>
 <?php echo $this->_tpl_vars['data']['area_name']; ?>
 <?php echo $this->_tpl_vars['data']['address']; ?>
   </p>
                    </div>
                    <div class="op_addr">
                    	<a href="javascript:;" onclick="editAddressInfo(<?php echo $this->_tpl_vars['data']['address_id']; ?>
);">修改</a>
        	           <a href="javascript:;" onclick="delAdress(<?php echo $this->_tpl_vars['data']['address_id']; ?>
);">删除</a>
                    </div>
                </li>
               <?php endforeach; endif; unset($_from); ?>
            </ul>
            <?php endif; ?>  
        </div>
        <script type="text/javascript">
        	$(function(){
				$(".arrive_addr ul .wrap_addr").click(function(){
					$(this).parent("li").addClass("active").siblings().removeClass("active");
					setAddress($(this).parent("li").attr('address'));
				})
			})
        </script>
        
<div class="addr_add">      
<h3><?php if ($this->_tpl_vars['address_id'] && $this->_tpl_vars['type'] == 'edit'): ?>修改地址<?php else: ?><a href="javascript:;" onclick="editAddressInfo()">创建新地址</a><?php endif; ?></h3>
<div id="address_form_box" style="<?php if ($this->_tpl_vars['type'] == 'edit'): ?>display:block<?php else: ?>display:none<?php endif; ?>">
<form  action="/flow/edit-add-addr/" method="post" id="addressFrom">
  <table width="100%" border="0">
               <tbody><tr>
                 <td width="11%" align="right"><em>*</em> 收件人姓名</td>
                 <td width="89%"><input type="text" value="<?php echo $this->_tpl_vars['address']['consignee']; ?>
"  class="txt_name" id="consignee" name="consignee"></td>
               </tr>
               <tr>
                 <td align="right"><em>*</em> 配送区域</td>
                 <td>
                 <select onchange="getArea(this)" name="province_id" id="province">
                 <option value="">请选择省份...</option>
                  <?php $_from = $this->_tpl_vars['province']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
					<option value="<?php echo $this->_tpl_vars['p']['area_id']; ?>
" <?php if ($this->_tpl_vars['p']['area_id'] == $this->_tpl_vars['address']['province_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['p']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>     
                 </select>
                  <select onchange="getArea(this)" name="city_id" id="city">
                  <option value="">请选择城市...</option>
                 <?php if ($this->_tpl_vars['province']): ?>
					<?php $_from = $this->_tpl_vars['city']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
				<option value="<?php echo $this->_tpl_vars['c']['area_id']; ?>
" <?php if ($this->_tpl_vars['c']['area_id'] == $this->_tpl_vars['address']['city_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['c']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>            
			   <?php endif; ?>
                </select>
                <select onchange="$('#phone_code').val(this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title'));" name="area_id" id="area">
                <option value="">请选择地区...</option>
               <?php if ($this->_tpl_vars['city']): ?>
				<?php $_from = $this->_tpl_vars['area']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['a']):
?>
				 <option value="<?php echo $this->_tpl_vars['a']['area_id']; ?>
" <?php if ($this->_tpl_vars['a']['area_id'] == $this->_tpl_vars['address']['area_id']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['a']['area_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
              </select></td>
            </tr>
            <tr>
              <td align="right"><em>*</em> 详细地址</td>
              <td><input type="text" class="txt_addr"  name="address" id="address" value="<?php echo $this->_tpl_vars['address']['address']; ?>
" >
              (请填写详细地址)</td>
            </tr>
         
            <tr>
              <td align="right"><em>*</em> 手机</td>
              <td><label for="textfield"></label>
              <input type="text" class="txt_mobile"  id="mobile" name="mobile" value="<?php echo $this->_tpl_vars['address']['mobile']; ?>
" >
              (电话和手机至少填一项)</td>
            </tr>
               <tr>
              <td align="right">固话</td>
              <td><input type="text" class="txt_tel01" name="phone_code" id="phone_code" value="<?php echo $this->_tpl_vars['address']['phone_code']; ?>
" >                
                 <input type="text" class="txt_tel02" name="phone" id="phone" value="<?php echo $this->_tpl_vars['address']['phone']; ?>
"> 
                 <input type="text" class="txt_tel01" name="phone_ext" id="phone_ext" value="<?php echo $this->_tpl_vars['address']['phone_ext']; ?>
" > 
               区号+电话号码+分机号，如021-33555777-8888</td>
            </tr>
          </tbody></table>         
      
          <input type="hidden" name="address_id" value="<?php echo $this->_tpl_vars['address_id']; ?>
">
          <a class="btn_save fl" href="javascript:;"  onclick="return check_post_address();" >保存配送信息</a>
          <?php if ($this->_tpl_vars['addressList']): ?> 
          <a class="fl ml10" href="javascript:;" onclick="$('#address_form_box').fadeOut();">取消</a>
          <?php endif; ?>
          </form>
          
  </div>     
</div>
</div>