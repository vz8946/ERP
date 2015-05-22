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
        <a href="/member/gift-card">我的现金券</a>
        <a href="/member/gift-card-log" class="sel" >现金券使用记录</a>
        <a href="/member/active-card">激活现金券</a>
        </div>
    </div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr align="center">
						<th>卡号</th>
                        <th>类型</th>
                        <th width="165px">使用时间</th>
                        <th width="120px">金额</th>
                        <th width="120px">用户</th>
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$logInfo item=data}}
                    <tr>
						<td>{{$data.card_sn}}</td>
                        <td>{{if $data.card_type eq 1 }}出售的礼品卡 {{elseif $data.card_type eq 2}} 赠送的礼品卡 {{elseif $data.card_type eq 3}} 兑换的礼品卡{{else}} 未知{{/if}}</td>
                        <td>{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
                        <td>{{$data.price}}</td>
                        <td>{{$data.user_name}}</td>
                    </tr>
                    {{/foreach}}
                </tbody>
            </table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>