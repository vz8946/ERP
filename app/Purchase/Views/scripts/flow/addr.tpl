 <div class="info_consignee current">
 <div class="arrive_addr">
 <h2><b>收货人信息</b> <span class="step-action" id="consignee_edit_action">保存收货地址</span></h2> 
        <div style="clear:both;" class="mb10"></div>        
         
           {{if $addressList}} 
            <ul>
              	{{foreach from=$addressList key=key item=data}}
            	<li {{if  $address_id eq $data.address_id}}class="active"{{/if}} id="add_item_{{$data.address_id}}" address="{{$data.address_id}}" onclick="">
                	<div class="wrap_addr">
                        <h4><b>{{$data.consignee}}（收）</b>{{if $data.mobile}}{{$data.mobile}} {{else}} {{$data.phone}}{{/if}} </h4>
                        <p> {{$data.province_name}} {{$data.city_name}} {{$data.area_name}} {{$data.address}}   </p>
                    </div>
                    <div class="op_addr">
                    	<a href="javascript:;" onclick="editAddressInfo({{$data.address_id}});">修改</a>
        	           <a href="javascript:;" onclick="delAdress({{$data.address_id}});">删除</a>
                    </div>
                </li>
               {{/foreach}}
            </ul>
            {{/if}}  
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
<h3>{{if $address_id && $type eq 'edit'}}修改地址{{else}}<a href="javascript:;" onclick="editAddressInfo()">创建新地址</a>{{/if}}</h3>
<div id="address_form_box" style="{{if $type eq 'edit'}}display:block{{else}}display:none{{/if}}">
<form  action="/flow/edit-add-addr/" method="post" id="addressFrom">
  <table width="100%" border="0">
               <tbody><tr>
                 <td width="11%" align="right"><em>*</em> 收件人姓名</td>
                 <td width="89%"><input type="text" value="{{$address.consignee}}"  class="txt_name" id="consignee" name="consignee"></td>
               </tr>
               <tr>
                 <td align="right"><em>*</em> 配送区域</td>
                 <td>
                 <select onchange="getArea(this)" name="province_id" id="province">
                 <option value="">请选择省份...</option>
                  {{foreach from=$province item=p}}
					<option value="{{$p.area_id}}" {{if $p.area_id==$address.province_id}}selected{{/if}}>{{$p.area_name}}</option>
				{{/foreach}}     
                 </select>
                  <select onchange="getArea(this)" name="city_id" id="city">
                  <option value="">请选择城市...</option>
                 {{if $province}}
					{{foreach from=$city item=c}}
				<option value="{{$c.area_id}}" {{if $c.area_id==$address.city_id}}selected{{/if}}>{{$c.area_name}}</option>
				{{/foreach}}            
			   {{/if}}
                </select>
                <select onchange="$('#phone_code').val(this.options[this.selectedIndex].getAttribute('class')?this.options[this.selectedIndex].getAttribute('class'):this.options[this.selectedIndex].getAttribute('title'));" name="area_id" id="area">
                <option value="">请选择地区...</option>
               {{if $city}}
				{{foreach from=$area item=a}}
				 <option value="{{$a.area_id}}" {{if $a.area_id==$address.area_id}}selected{{/if}}>{{$a.area_name}}</option>
				{{/foreach}}
				{{/if}}
              </select></td>
            </tr>
            <tr>
              <td align="right"><em>*</em> 详细地址</td>
              <td><input type="text" class="txt_addr"  name="address" id="address" value="{{$address.address}}" >
              (请填写详细地址)</td>
            </tr>
         
            <tr>
              <td align="right"><em>*</em> 手机</td>
              <td><label for="textfield"></label>
              <input type="text" class="txt_mobile"  id="mobile" name="mobile" value="{{$address.mobile}}" >
              (电话和手机至少填一项)</td>
            </tr>
               <tr>
              <td align="right">固话</td>
              <td><input type="text" class="txt_tel01" name="phone_code" id="phone_code" value="{{$address.phone_code}}" >                
                 <input type="text" class="txt_tel02" name="phone" id="phone" value="{{$address.phone}}"> 
                 <input type="text" class="txt_tel01" name="phone_ext" id="phone_ext" value="{{$address.phone_ext}}" > 
               区号+电话号码+分机号，如021-33555777-8888</td>
            </tr>
          </tbody></table>         
      
          <input type="hidden" name="address_id" value="{{$address_id}}">
          <a class="btn_save" href="javascript:;"  onclick="return check_post_address();" >保存配送信息</a>
          </form>
  </div>     
</div>
</div>