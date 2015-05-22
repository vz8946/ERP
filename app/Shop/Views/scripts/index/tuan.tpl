<div>
	<div class="fl w2 box">
		<div class="b-t"><span class="b-t-l fl"><i></i>本期特购</span>{{if $is_clock}}<span id="index-clock" class="b-t-r fr" time="{{$totime}}"></span>{{/if}}
	</div>
		<div class="b-c">
			<ul class="tuan-goods">
				{{foreach from=$qg.goods item=v key=k}}
				{{if $v.gt == 'group'}}
				<li>
					<div class="inner">
						<div class="gi"><a target="_blank" href="/groupgoods-{{$v.group_id}}.html">
							{{html type="img" w="282" h="282" src=$v.group_goods_img}}
						</a></div>
						<div class="ga">{{$v.group_goods_alt}}</div>
						<div class="gt"><a target="_blank" href="/groupgoods-{{$v.group_id}}.html">{{$v.group_goods_name}}</a></div>
						<div class="grp"><span style="font-size: 15px;">特价：</span>￥{{$v.qg_price}}</div>
						<div class="gmp">垦丰价：￥{{$v.group_price}}</div>
						<div class="go">
							<div class="wrp-btn"><a target="_blank" href="/groupgoods-{{$v.group_id}}.html">&nbsp;</a></div>
						</div>
					</div>
				</li>				
				{{else}}
				<li>
					<div class="inner">
						<div class="gi"><a target="_blank" href="/goods-{{$v.goods_id}}.html">
							{{html type="img" w="282" h="282" src=$v.goods_arr_img|default:$v.goods_img}}
						</a></div>
						<div class="ga">{{$v.goods_alt}}</div>
						<div class="gt"><a target="_blank" href="/goods-{{$v.goods_id}}.html">{{$v.goods_name}}</a></div>
						<div class="grp"><span style="font-size: 15px;">特价：</span>￥{{$v.qg_price}}</div>
						<div class="gmp">垦丰价：￥{{$v.price}}</div>
						<div class="go">
							<div class="wrp-btn"><a target="_blank" href="/goods-{{$v.goods_id}}.html">&nbsp;</a></div>
						</div>
					</div>
				</li>
				{{/if}}
				{{/foreach}}
			</ul>
		</div>
	</div>
	
	<div class="fr foreshow">
	<div class="title"><img width="290" height="38" src="{{$_static_}}/images/shop/title_foreshow.jpg"></div>
    <div class="foreshow_con">
        <p>{{$tip_tmw_date}} 特购商品：</p>        
        {{foreach from=$tqg.goods item=v key=k}}
		{{if $v.gt == 'group'}}
		    <dl>
            <dt><a href="/groupgoods-{{$v.group_id}}.html" target="_blank"><img width="58" height="58" src="{{$imgBaseUrl}}/{{$v.group_goods_img}}"></a></dt>
            <dd><a href="/groupgoods-{{$v.group_id}}.html"  target="_blank" title="{{$v.group_goods_name}}"><b>{{$v.group_goods_name|cut_str:20:0:''}}</b></a></dd>
            <dd title="{{$v.group_goods_alt}}">{{$v.group_goods_alt|cut_str:58}}</dd>
           </dl>					
		{{else}}		
		   <dl>
            <dt><a href="/goods-{{$v.goods_id}}.html"  target="_blank"><img width="58" height="58" src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_60_60.'}}"></a></dt>
            <dd><a href="/goods-{{$v.goods_id}}.html"  target="_blank" title="{{$v.goods_name}}"><b>{{$v.goods_name|cut_str:20:0:''}}</b></a></dd>
            <dd title="{{$v.goods_alt}}">{{$v.goods_alt|cut_str:58}}</dd>
        </dl>
		{{/if}}
		{{/foreach}}   
		
    <div class="take">
   	<p>订阅更多优惠信息：</p>
     <input type="text" id="dy-email" value="输入您的电子邮箱" name="email">
    <a href="javascript:;" id="btn-dingyue"><img width="71" height="38" src="{{$_static_}}/images/shop/btn_dingyue.jpg"></a>
  </div>
  </div>  
</div>	

</div>

<script>
$('#index-clock').YMCountDown("还剩");
$('#dy-email').iClear();
$('#btn-dingyue').click(function(){
	var email = $('#dy-email').val();
	if(email== '' || email== '输入您的电子邮箱')
	{
		$.dialog.alert("请输入邮箱");
		$('#dy-email').focus();
		return false;
	}
	
	$.post('/index/dingyue',{send_mail:email},function(data){
		if(data.status == 'succ'){
			$('#dy-email').val('');
		}else{
			$('#dy-email').focus();
		}
		$.dialog.alert(data.msg);
	},'json');	
	
});
</script>