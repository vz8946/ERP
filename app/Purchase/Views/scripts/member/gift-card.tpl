<div class="member">

{{include file="member/menu.tpl"}}
  <div class="memberright">
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_gift.png"></div>
	<style type="text/css">
    .ordertype{ margin-top:10px; padding:13px; background: #f0f0f0;}
    .ordertypea{ mmargin-left:10px;}
    .ordertypea a{ display:inline-block; margin:10px 10px 0 10px;}
    .sel{ border: 1px solid #d3e4ef;background: #eff8fe;padding: 0 5px;}
    </style>
	<div class="ordertype">
        <div class="ordertypea">
        <a href="/member/gift-card" class="sel" >我的现金券</a>
        <a href="/member/gift-card-log" >现金券使用记录</a>
        <a href="/member/active-card" >激活现金券</a>
        </div>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr align="center">
                        <th >卡号</th>
                        <th >密码</th>
                        <th >价值</th>
                        <th>剩余金额</th>
				        <th >生成时间</th>
                        <th >结束日期</th>
                        <th>使用时间</th>
						<th>状态</th>		
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$info item=data}}
                    <tr>
					    <td>{{$data.card_sn}}</td>
						<td>{{$data.card_pwd}}</td>
						<td>{{$data.card_price}}</td>
						<td>{{$data.card_real_price}}</td>
                        <td>{{$data.add_time|date_format:"%Y-%m-%d"}}</td>
                        <td>{{$data.end_date}}</td>
                        <td>{{$data.using_time|date_format:"%Y-%m-%d %H:%M:%S"|default:'&nbsp;'}}</td>
                        <td>
						{{if $data.card_real_price eq 0.00}} 无效  {{else}}
						{{if $curtime > $data.end_date}} <font color="#FF0000">已过期</font> {{else}}{{if $data.status eq 0}} <font color="#009900">有效</font>{{elseif $data.status eq 1}} 无效{{/if}}{{/if}}{{/if}}
						</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>