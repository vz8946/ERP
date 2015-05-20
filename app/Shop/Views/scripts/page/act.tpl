<link rel="stylesheet" type="text/css" href="/styles/shop/list.css"/>
<div class="groups mar">
<div class="ad"><a href="/special-38.html" target="_blank"><img src="{{$imgBaseUrl}}/images/shop/index_ad02.jpg" /></a></div>
<h2 class="clear"><strong>活动专区</strong></h2>
<ul class="clear">
          {{foreach from=$datas key=key item=data}}
            <li><a href="/{{$data.act_url}}" target="_blank"><img src="{{$imgBaseUrl}}/{{$data.act_img|replace:'.':'_350_350.'}}" alt="{{$data.act_name}}"/></a>
            <p><a href="/{{$data.act_url}}" target="_blank" >{{$data.act_name}}</a></p>
           </li>
          {{/foreach}}
</ul>
<div class="pageNav">{{$pageNav}}</div>
</div>




