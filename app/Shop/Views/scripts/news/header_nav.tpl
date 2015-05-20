<div class="nav">
	<ul>
        <li><a href="/news">首页</a></li>
        {{foreach from=$list_cat_nav item=v key=k}}
        <li class="{{if $v.cat_id == $nav_c}}current{{/if}}" ><a href="/news-{{$v.as_name}}">{{$v.cat_name}}</a></li>
        {{/foreach}}
	</ul>
</div>