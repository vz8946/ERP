<div id="slides1">
	{{foreach from=$links item=v key=k}}
    <div class="slide_wrap"><a href="{{$v.url}}"><img src="{{$imgBaseUrl}}/{{$v.img}}" alt="{{$v.title}}" width="399" height="300" /></a></div>
    {{/foreach}}
</div>
<div id="myController1">
    <span class="jPrev">&lt;</span>
	{{foreach from=$links item=v key=k}}
    <span class="jFlowControl1">{{$k+1}}</span>
    {{/foreach}}
    <span class="jNext">&gt;</span>
</div>
