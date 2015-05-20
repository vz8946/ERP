<div class="member">

    {{include file="member/menu.tpl"}}
  <div class="memberright">
	<div style="margin-top:11px;"><img src="{{$imgBaseUrl}}/images/shop/member_favorite.png"></div>
	<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="re_table">
                <thead>
                    <tr>
                        <th colspan="2">商 品</th>
                        <th >放入时间</th>
                        <th >单价</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    {{foreach from=$info item=data}}
                    <tr>
					    <td>
						<a href="/goods/show/id/{{$data.goods_id}}">
						<img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}" border="0" width="65" height="65">
						</a></td>
						<td align="left"><a href="/goods/show/id/{{$data.goods_id}}" style="color:#707070;">{{$data.goods_name}}</a></td>
						<td>{{$data.add_time}}</td>
						<td>{{$data.price}}</td>
                        <td>
						<a href="/goods/show/id/{{$data.goods_id}}">购买</a>
						<a href="/goods/del-favorite/favorite_id/{{$data.favorite_id}}">删除</a>	
						</td>
                    </tr>
                    {{/foreach}}
            </table>
    <div class="page_nav">{{$pageNav}}</div>
  </div>
</div>