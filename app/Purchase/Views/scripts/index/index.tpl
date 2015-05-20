<!-- 主体-->
<div class="wbox_1200">	
	<div class="b1"></div>
	<div style="border: 1px solid #ddd;height: 100px;overflow: hidden;">
	
				<div id="wgt-index_brand" class="widget"><script type="text/javascript">
				$('#brand-list').ready(function(){
					$('#brand-jcl-index_brand').jcarousel({
						auto : 5,
						wrap: 'circular',
						scroll : 8
					}); 
				});
				</script>
				<div id="brand-list" class="brand-list">
				<ul id="brand-jcl-index_brand" class="jcarousel-skin-default" style="width: 100%;">
					{{foreach from = $indextag.40.details item = tag }}
						<li>
							<img alt="{{$tag.brand_name}}"  width="120" height="58" src="http://img.1jiankang.com/{{$tag.small_logo}}"/>
						</li>
					{{/foreach}}
				</ul>
				</div>
				</div>
	
	
	</div>
	<div class="b1"></div>
	
	{{include file="widget/index_floor1.tpl"}}
	
	<div class="b1"></div>
	{{include file="widget/index_floor2.tpl"}}
	
	<div class="b1"></div>
	{{include file="widget/index_floor3.tpl"}}
	
	<div class="b1"></div>
	{{include file="widget/index_floor4.tpl"}}
	
	<div class="b1"></div>
    {{include file="widget/index_floor5.tpl"}}
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
<!--end 主体-->
