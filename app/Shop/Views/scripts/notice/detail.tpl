<div class="searchBox">
	<!---main begin----->
<div class="main">
    	<div class="w_notice">
            <div class="notice_title"><h2>{{$info.title}}</h2><p>{{$info.add_time|date_format:'%Y-%m-%d %H:%M:%S'}}</p></div>
            <div class="notice_con">              
              {{$info.content}}
              <div class="share">
              <span>
              <i>分享到：</i>
              <img width="184" height="31" border="0" usemap="#Map" src="{{$_static_}}/images/shop/share.jpg">
          <map id="Map" name="Map">
            <area href="javascript:window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="t-sina" title="分享到新浪微博" coords="5,5,27,24" shape="rect">
            <area href="javascript:(function(){window.open('http://v.t.qq.com/share/share.php?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href)+'&source=bookmark','_blank','width=610,height=350');})()" class="t-qq" title="分享到QQ微博" coords="38,5,58,24" shape="rect">
            <area href="javascript:window.open('http://www.kaixin001.com/repaste/share.php?rtitle='+encodeURIComponent(document.title)+'&rurl='+encodeURIComponent(window.location.href)+'&rcontent=看中一个好东东，很好看，是垦丰的'+encodeURIComponent(document.title)+' 亲爱的您也看下吧'+encodeURIComponent(location.href),'_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" class="kaixin" title="分享到开心网" coords="69,6,87,25" shape="rect">
            <area href="javascript:window.open('http://cang.baidu.com/do/add?it='+encodeURIComponent(document.title)+'&iu='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到百度收藏" coords="99,7,117,25" shape="rect">
            <area href="javascript:window.open('http://bai.sohu.com/share/blank/addbutton.do?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到搜狐白社会" coords="128,5,147,25" shape="rect">
            <area href="javascript:window.open('http://www.douban.com/recommend?title='+encodeURIComponent(document.title)+'&link='+encodeURIComponent(window.location.href)+'&rcontent=','_blank','scrollbars=no,width=600,height=450,left=75,top=20,status=no,resizable=yes'); void 0" title="分享到豆瓣" coords="158,5,174,24" shape="rect">
          </map></span>
          
          <a class="more" href="/notice"><img width="88" height="22" src="{{$_static_}}/images/shop/more.jpg"></a>
        </div>
            
        </div>
        </div>
    	
    </div>
    <!---main end----->
    <!---sidebar begin--->
    <div class="sidebar">
    	<!--sidemenu begin---->
    	<div class="sidemenu">        	
        	{{foreach from=$tree_nav_cat item=item key=key}}    
        	<dl>    	
            	<dt><i class="icon_search {{if $key >0}}fold{{/if}}"></i>{{$item.cat_name}}</dt>
                <dd {{if $key eq 0}}style="display:block"{{/if}}>
                	<ul>
                    	{{foreach from=$item.sub item=sub}}  
                        <li><a href="/gallery-{{$sub.cat_id}}.html" target="_blank">{{$sub.cat_name}}</a></li>
                        {{/foreach}}
                    </ul>
                </dd>  
             </dl>        
          {{/foreach}}        
        </div>
        <!--sidemenu end---->
        <script type="text/javascript">
        	$(function(){
				$(".sidemenu dt").click(function(){
					$(".sidemenu dd").hide();
					$(".sidemenu dt i").addClass("fold");
					if($(this).find("i").hasClass("fold")){
						$(this).find("i").removeClass("fold");
						$(this).siblings("dd").show();
					}else{
						$(this).find("i").addClass("fold");
						$(this).siblings("dd").hide();
					}
					
				})				
				
			})
        </script>
        
    </div>
    <!---sidebar end--->
    
</div>
{{if  $links}}
<div class="youlove">
   	  <h2>猜你喜欢</h2>
      <div class="loveproList">
          <div class="w_loveList">
              <ul style="width: 2760px;">             
                {{foreach from =$links key=key item=linksgoods}}      
                <li>
                <a href="/goods-{{$linksgoods.goods_id}}.html" title="{{$linksgoods.goods_name}}" target="_blank" class="img"><img src="{{$imgBaseUrl}}/{{$linksgoods.goods_img|replace:'.':'_180_180.'}}"/></a>
                <b class="c1" title="{{$linksgoods.goods_alt}}">{{$linksgoods.goods_alt}}</b>
                <p><a href="/goods-{{$linksgoods.goods_id}}.html" target="_blank"> {{$linksgoods.goods_name}}</a></p>
                <p class="price"><span>￥{{$linksgoods.market_price}}</span><em>￥{{$linksgoods.price}}</em></p>
                </li>        
               {{/foreach}}
              </ul>
          </div>      
      </div>    
    </div>
{{/if}}