<div class="brand">
	<div class="focus">
	<div id="pic">
		<ul>
			<li><a href="#"><img width="990" height="200" alt="" src="/_static/images/brand/ad.jpg" /></a></li>
			<li><a href="#"><img width="990" height="200" alt="" src="/_static/images/brand/ad.jpg" /></a></li>
			<li><a href="#"><img width="990" height="200" alt="" src="/_static/images/brand/ad.jpg" /></a></li>
			<li><a href="#"><img width="990" height="200" alt="" src="/_static/images/brand/ad.jpg" /></a></li>
            <li><a href="#"><img width="990" height="200" alt="" src="/_static/images/brand/ad.jpg" /></a></li>
		</ul>
	</div>
	<div id="tip">
		<ul>
			<li id="smallimg_1" onclick="change(1)" class="current"></li>
			<li id="smallimg_2" onclick="change(2)"></li>
			<li id="smallimg_3" onclick="change(3)"></li>
			<li id="smallimg_4" onclick="change(4)"></li>
            <li id="smallimg_5" onclick="change(5)"></li>
		</ul>
	</div>
</div>
<script>
var isround = "";
var scrollmove = "";
var masktime = 10;
var focus_cur = 1;
var p = document.getElementById("pic").getElementsByTagName("li");
var h = document.getElementById("tip").getElementsByTagName("li");
function change(id){
	clearTimeout(isround);
	clearInterval(scrollmove);
	for (var i = 1; i <= h.length; i++) {
		if(i == id){
			document.getElementById("smallimg_"+i).className="current";
		}else{
			document.getElementById("smallimg_"+i).className="";
		}
	}
	if ((next = id + 1) > h.length){
		next = 1;
	}
	isround = setTimeout("change("+next+")",3000);
	scrollmove = setInterval("scrollMove("+id+")",masktime);
	focus_cur = id;
}
function scrollMove(m){
	var srl = document.getElementById("pic").scrollLeft;
	var dsrl = Math.floor((p[0].clientWidth*(m-1)-srl)/5);
	var xsrl = Math.ceil((p[0].clientWidth*(m-1)-srl)/5);
	if(srl > p[0].clientWidth*(m-1)){
		document.getElementById("pic").scrollLeft = srl + dsrl;
	}else if(srl < p[0].clientWidth*(m-1)){
		document.getElementById("pic").scrollLeft = srl + xsrl;
	}else{
		clearInterval(scrollmove);
	}
}
function focus_prev(){
	var prev = (focus_cur+3)%4;
	if(prev == "0"){
		prev = 4;
	}
	change(prev);
}
function focus_next(){
	var next = (focus_cur+1)%4;
	if(next == "0"){
		next = 4;
	}
	change(next);
}
isround = setTimeout("change(2)",3000);
</script>
  <!---推荐品牌 begin---->
  <div class="brand_recommend">
    <div class="title"><h2>推荐品牌</h2><span><input name="" type="text" />
        <a href="#"><img src="/_static/images/brand/btn_search.jpg" width="60" height="26" /></a></span></div>
      <div class="list_img">
      	<ul>
		
		
			{{foreach from = $indextag.48.details item = tag }}
				<li>
					<a href="#" target="_blank"> <img alt="{{$tag.brand_name}}"  width="118" height="58" src="http://img.1jiankang.com/{{$tag.small_logo}}"/> </a>
				</li>
			{{/foreach}}
		
		
            <li class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a></li>
			
			
            <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a></li>
            <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a></li>

            <li class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a></li>
     
        </ul>
    </div>
    <div class="list_txt">
    	<ul>
		
			{{foreach from = $indextag.49.details item = tag }}
				<li>
					<a href="#" target="_blank"> {{$tag.brand_name}} </a>
				</li>
			{{/foreach}}
		
        	
            <li class="last"><a href="#">品牌文字</a></li>
            <li class="last"><a href="#">品牌文字</a></li>
			
			
        </ul>
    </div>
  </div>
  <!---推荐品牌 end---->
  
  <div class="brand_sort">
  	<div class="tab">
    	<ul>
        	<li class="current"><a href="#">女性保健</a></li>
            <li><a href="#">男性保健</a></li>
            <li><a href="#">中老年保健</a></li>
            <li><a href="#">儿童营养补充</a></li>
            <li><a href="#">基础保健</a></li>
            <li><a href="#">亚健康</a></li>
            <li><a href="#">健康食品</a></li>
            <li><a href="#">传统滋补</a></li>
            <li><a href="#">家用医疗器械</a></li>
            <li><a href="#">日化个护</a></li>
        </ul>
    </div>
    <div class="w_tab_con">
    	<div class="tab_con">
        	<ul>
            	<li class="current"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                <li class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></li>
                
            </ul>
            <div class="find">
                <h3>按类型查找商品</h3>
                <p> <a href="#">缓解衰老</a><a href="#">纤体塑身</a><a href="#">调节内分泌</a><a href="#">美容养颜</a><a href="#">补血养血</a><a href="#">更年期调养</a><a href="#">泌尿系统呵护</a></p>
            </div>
        </div>
    </div>
  </div>
  <div class="brand_sort">
  	<div class="tab">
    	<ul>
        	<li class="current"><a href="#">进口品牌</a></li>
            <li><a href="#">国产品牌</a></li>
        </ul>
    </div>
    <div class="w_tab_con">
    	<div class="tab_con">
        	<dl>
            	<dt>美国</dt>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
            </dl>
            <dl>
            	<dt>美国</dt>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
            </dl>
            <dl>
            	<dt>美国</dt>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
            </dl>
            <dl>
            	<dt>美国</dt>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
                <dd class="last"><a href="#"><img src="/_static/images/brand/brand.jpg" width="118" height="58" /></a><a href="#">品牌名称</a></dd>
            </dl>
        </div>
    </div>
  </div>
  <div class="shili">
  	<div class="title"><h2>我们的实力</h2><a href="#">了解更多 >></a></div>
    <p><img src="/_static/images/brand/ad02.jpg" width="990" height="460" /></p>
  </div>
</div>
