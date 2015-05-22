->where("customer_id=$customer_id")<div class="searchBox">
	<!---main begin----->
	<div class="main">
    	<div class="w_notice">
            <div class="title">公&nbsp;告</div>
            <ul>
            {{foreach from=$list item=item}}         
            <li><a href="/notice/detail/id/{{$item.article_id}}"  target="_blank" title="{{$item.title}}">{{$item.title}}</a><span>{{$item.add_time|date_format:'%Y-%m-%d'}}</span></li>
           {{/foreach}}
            </ul>
          
        </div>
        <div class="mt10">{{$pagenav}}</div>
    	
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