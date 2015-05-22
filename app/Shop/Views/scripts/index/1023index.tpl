<!-- 主体-->
<div class="wbox_1200">	
	<!--首焦图-->
	<div id="index-focus" style="padding-left:233px;padding-top:3px;">
	  {{widget class="AdvertWidget" id="2"}}	
	</div>
	<div class="floor1">
		<div class="fl" style="width:900px;">
			<div id="floor1-tab" class="tab">
				<div class="tab-h">
				<ul>
					<li><a href="javascript:void();"><i style="font-weight: normal;font-size: 11px;" class="icon-baokuan">hot</i>人气爆款</a></li>
					<li><a href="javascript:void();"><i style="font-weight: normal;font-size: 11px;" class="icon-new">new</i>新品上架</a></li>
					<li><a href="javascript:void();"><i style="font-weight: normal;font-size: 10px;line-height: 1.2;" class="icon-prmt">应季<br/>推荐</i>应季推荐</a></li>
					<li style="margin-right: 0px;width: 225px;"><a href="javascript:void();"><i style="font-weight: normal;font-size: 11px;" class="icon-taocan">搭配</i>搭配套餐</a></li>
				</ul>
				</div>
				<div class="tab-c">
					<div>{{html type="wdt" id="index_baokuan"}}</div>
					<div>{{html type="wdt" id="index_new"}}</div>
					<div>{{html type="wdt" id="index_prmt"}}</div>
					<div>{{html type="wdt" id="index_dapei"}}</div>
				</div>
			</div>
		
		</div>
		
	<div class="wrap_notice_left fr" id="notice-box">
	<ul class="tab tab-h">
    	<li class="tab-h-c"><a href="/notice" target="_blank">本站公告</a></li>
        <li class="last"><a href="/topics.html" target="_blank">垦丰资讯</a></li>
    </ul>
    <div class="tab_con tab-c">
    	<div class="sales none" style="display: none;">
   	    	{{widget class="AdvertWidget"  id="18"}}
            <ul>   
             {{foreach from=$noticeInfo item=item}}         
            <li><a href="{{if $item.source}}{{$item.source}}{{else}}/notice/detail/id/{{$item.article_id}}{{/if}}" target="_blank" title="{{$item.title}}">{{$item.title|cut_str:36:0:''}}</a><i>{{$item.add_time|date_format:'%m-%d'}}</i></li>
           {{/foreach}}
            </ul> 
        </div>
        <div class="sales" style="display: block;">
   	        {{widget class="AdvertWidget"  id="19"}}
            <ul>
            {{foreach from=$saleInfo item=item}}
            	<li><a href="{{$item.source}}"  target="_blank" title="{{$item.title}}">{{$item.title|cut_str:36:0:''}}</a><i>{{$item.add_time|date_format:'%m-%d'}}</i></li>
            {{/foreach}}
            </ul> 
        </div>
    </div>
</div>
	<script>
	 $('#floor1-tab').tab();
	 $('#notice-box').tab(); 
	</script>
	</div>
    
    
	<div class="b1"></div>
    
	<div style="height: 110px;overflow: hidden;">
		<div class="fl">{{widget class="AdvertWidget"  id="3"}}</div>
		<div class="fl">{{widget class="AdvertWidget"  id="4"}}</div>
		<div class="fr">{{widget class="AdvertWidget"  id="5"}}</div>
	</div>

	<div class="b1"></div>

     {{include file="index/inc-tuan.tpl"}}
	
	<div class="b1"></div>
	<div style="border: 1px solid #ddd;height: 100px;overflow: hidden;">{{html type="wdt" id="index_brand"}}</div>
	
	<div class="b1"></div>
	{{html type="wdt" id="index_floor1"}}
	
	<div class="b1"></div>
	{{html type="wdt" id="index_floor2"}}
	
	<div class="b1"></div>
	{{html type="wdt" id="index_floor3"}}
	
	<div class="b1"></div>
	{{html type="wdt" id="index_floor4"}}
	
	<div class="b1"></div>
	{{html type="wdt" id="index_floor5"}}
	
 <script>		
		$('#index-cat-menu').find('.item:gt(9)').remove();			
		$('.index-goods-list1 img,.brand-list img,.tab-c img,.goods-hot img').hover(function(){
			$(this).css({opacity:0.6});			
		},function(){
			$(this).css({opacity:1});
		});
		
		$('.floor-tab').tab();		
		$('.goods-hot').find('li').mouseenter(function(){
			$(this).siblings().find('.p-detail').hide();	
			$(this).siblings().find('.p-title').show();	
			$(this).find('.p-detail').show();	
			$(this).find('.p-title').hide();	
		});		
</script>
</div>


<div  class="wbox_1200">
	<div style="margin:6px 0 10px;height:70px">{{widget class="AdvertWidget" id="20"}}
	</div>
</div>


<div class="floor_add_bottom">

<div class="fl">
    <div class="list_zixun">
        <div class="title"><h2>垦丰资讯</h2><a href="/news" target="_blank">更多&gt;&gt;</a></div>
        <ul>
	{{if $articleList}}
             {{foreach from=$articlesList item=v key=k}}
              	<li><a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank" title="{{$v.title}}">{{$v.title|cut_str:36:0:''}}</a></li>
              {{/foreach}}
        {{/if}}  
        </ul>
    </div>   
{{include file="index/inc-latest-comment.tpl"}}    
</div>

<div class="fr">
    <div class="weibo_sina">
        <div class="title"><h2>新浪微博</h2></div>
        <iframe width="340" height="360" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=340&height=360&fansRow=1&ptype=1&speed=300&skin=1&isTitle=0&noborder=0&isWeibo=1&isFans=1&uid=2951251611&verifier=45494a03&dpc=1"></iframe>
        
    </div>
    <div class="weibo_attention">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td rowspan="3"><img width="90" height="85" src="{{$_static_}}/images/shop/logo_sina.jpg"></td>
            <td valign="top" height="31" colspan="2">北大荒垦丰种业股份有限公司</td>
          </tr>
          <tr>
            <td><img width="105" height="27" src="{{$_static_}}/images/shop/v_sina.jpg"></td>
            <td align="right"><a href="http://e.weibo.com/guoyao1jiankang" target="_blank"><img width="63" height="26" src="{{$_static_}}/images/shop/add_attention.jpg"></a></td>
          </tr>
          <tr>
            <td valign="bottom" colspan="2">关注微博，活动特价早知道</td>
          </tr>
        </tbody></table>
        <div class="weibo_code">
            <img width="158" height="158" src="{{$_static_}}/images/shop/weibo_code.jpg"> 
            <p><b>我的眼里只有你，关注送好礼</b></p>
            <p>扫一扫加微信好友</p>
            <p>或者查找微信号 <em>gy1jiankang</em></p>
        </div>
    </div>
</div>
</div>
-->
