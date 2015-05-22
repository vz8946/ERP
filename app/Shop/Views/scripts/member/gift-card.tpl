<div class="member">
{{include file="member/menu.tpl"}}
  <div class="memberright">
  
  
  <div class="mycard">
    	<div class="title"><i></i><h2>我的垦丰卡</h2></div>
        <div class="menu">
        	<ul>            	
     	<li class="current"> <a href="/member/gift-card">我的垦丰卡</a> </li>       
      <li>  <a href="/member/gift-card-log" >垦丰卡消费明细</a> </li>    
        <li> <a href="/member/gift-buy" > 我买的垦丰卡</a>  </li>    
        <li> <a href="/member/active-card" >绑定垦丰卡</a> </li>    
            </ul>
        </div>
        <div class="mycard_list">
        	<p>
            	<span>我的垦丰卡：绑定我的账号且我能消费的垦丰卡。</span>
                <select name="type" onchange="location.href='/member/gift-card/type/'+this.value">
                	<option {{if $type eq 0}}selected{{/if}}value="0">全部</option>
                	<option {{if $type eq 1}}selected{{/if}} value="1">正常</option>
                	<option {{if $type eq 2}}selected{{/if}} value="2">作废</option>
                	<option {{if $type eq 3}}selected{{/if}} value="3">过期</option>
                </select>
            </p>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <th>卡号</th>
                <th>密码</th>
                <th>余额 </th>
                <th>到期时间 </th>
                <th>状态</th>
              </tr>
                {{foreach from=$info item=data}}
                    <tr>
					    <td>{{$data.card_sn}}</td>
						<td>{{$data.card_pwd}}</td>
						<td>{{$data.card_real_price}}</td>
                        <td>{{$data.end_date}}</td>                     
                        <td>
						{{if $data.card_real_price eq 0.00}} 作废  {{else}}
						{{if $curtime > $data.end_date}} <font color="#FF0000">已过期</font> {{else}}{{if $data.status eq 0}} <font color="#009900">正常</font>{{elseif $data.status eq 1}}作废{{/if}}{{/if}}{{/if}}
						</td>
                    </tr>
                    {{/foreach}}
            </tbody></table>
              <div class="pagesize">{{$pageNav}}</div>
	 </div>
      <div class="useflow">
       	<h3>垦丰卡使用流程</h3>
          <img width="676" height="50" src="{{$_static_}}/images/cart/card_flow.jpg"> 
          <p>说明：1.账户中已有绑定的垦丰卡，可在结算信息中，勾选已绑定的垦丰卡，卡余额将会抵扣订单金额；<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.账户中未有绑定的垦丰卡，可在结算信息中，输入垦丰卡卡号和密码，卡余额将会抵扣订单金额。</p>
        </div>
          <div class="userule">
          	<h3>垦丰卡使用规则</h3>
            <p>1、垦丰卡可与垦丰商城会员账户进行绑定，使用时无须再输入卡号和密码，直接选择即可。已绑定的垦丰卡只能被当前账号使用<br>，不能跨账号使用，且不支持解除绑定功能；<br>
2、垦丰卡可用于购买垦丰商城销售的所有商品，可与其他优惠一起使用；<br>
3、下单时，每笔订单可用多张垦丰卡支付，不足部分以现金补足或在线支付；<br>
4、垦丰卡有效期自销售之日起3年，有效期内可重复使用，延期将自动失效。垦丰卡暂不支持充值功能；<br>
5、垦丰卡不记名、不挂失、不兑换现金，请妥善保管卡号密码；<br>
6、垦丰卡销售时若开具过发票给顾客，之后购买商品时垦丰卡支付金额部分将不再开具发票；<br>
7、发生拒收或退货时，垦丰卡支付金额部分将自动退回卡内，有效期不变；<br>
8、顾客可登录垦丰商城“我的垦丰”-“我的垦丰卡”页面查询垦丰卡的卡号、余额、有效期等使用情况；<br>
9、垦丰商城拥有垦丰卡的最终解释权。</p>
          </div>
    </div>
  
  
  
 </div></div>