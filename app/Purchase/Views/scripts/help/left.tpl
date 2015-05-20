<div class="help-left">
    	<div class="help-title">帮助中心</div>
    	{{foreach from=$menu item=item}}
    	<div class="help-parent-menu">{{$item.cat_name}}</div>
    	<div class="help-child-menu">
	    	<ul>
	    		{{foreach from=$item.list item=vo}}
		    	<li>
		    		<span class="help-ico"></span><a title="{{$vo.title}}" href="/help/detail-{{$vo.article_id}}.html">{{$vo.title}}</a>
		    	</li>
		    	{{/foreach}}
		    </ul>
	    </div>
	    {{/foreach}}
	    <div class="help-parent-menu">站点信息</div>
        <div class="help-child-menu">
        	<ul>
            	<li><span class="help-ico"></span><a title="网站地图" href="/sitemap.html">网站地图</a></li>
            	
            </ul>
        </div>
</div>