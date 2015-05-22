{{include file="news/inc-header.tpl"}}
<link type="text/css" href="/newstatic/images/zt/style.css?v={{$sys_version}}" rel="stylesheet" />
<div class="bg">
	<div class="main">
        <div class="top">
        	<img src="/newstatic/images/zt/top_01.jpg" width="990" height="142" />
        	<img src="/newstatic/images/zt/top_02.jpg" width="990" height="148" />
        </div>
        <div class="bd bd01">
        	<div class="preview">
            	<div class="t_img"><img src="/newstatic/images/zt/title01.jpg" width="163" height="27" /></div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><img src="/newstatic/images/zt/bg_preview.jpg" width="114" height="103" /></td>
                    <td class="txt">“中国人口老龄化问题愈演愈烈”“421家庭结构”“2013，人口红利就将结束”一直是近几年来大家最为关注的民生话题，它也是2013中国两会上被讨论的热点议题之一。近期，有媒体不断曝出中国将放开“单独二胎”政策，“单独二胎”政策不久就会放开。这一政策的突然“开闸”乐坏了符合条件的宝爸、宝妈，“再生一个孩子”的愿望终于有了盼头了，对于他们来说，目前生第二个孩子可以说是指日可待啊！ </td>
                  </tr>
                </table>
            </div>
            <div class="articleList">
           	  <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-ertaizhengce" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
              <ul>
              {{foreach from=$data.ertaizhengce item=v key=k}}
              	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
              {{/foreach}}
              </ul>
            </div>
        </div>
        <div class="bd bd02">
        	<div class="t_img"><img src="/newstatic/images/zt/title02.jpg" width="990" height="31" /></div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="txt"><p>二胎政策“开闸”不仅乐坏了众年轻妈妈，也实现了如今已不再年轻妈妈们多年来的夙愿。一般来说，女性的最佳怀孕年龄为25—30岁，随着年龄的增长，生育力逐渐下降。医学上把年龄≥35岁，称为高龄孕产妇，年龄大了会有什么问题吗？</p><br /><br />
                <p>据统计，高龄孕产妇21三体（唐氏）综合征的发生率明显增加。下面数字可以说明这一问题21三体综合征的发生率：孕妇25～29岁，其发生率为1/1100，30～31岁为1/900；33～34岁为1/500～750；而35岁为1/350；40岁为1/100；≥45岁为1/35。此外，高龄孕产妇的流产、早产、妊娠期出现异常的情况如妊娠高血压综合征，妊娠糖尿病等机会均较年轻者增加，临产分娩时，肌肉力量差，易发生宫缩不好，宫颈的扩张力差，也容易发生宫颈水肿、宫口不易开大所谓宫颈难产的情况，高龄产妇中剖宫产率显然高于年轻产妇。</p></td>
                <td><div class="img"><img src="/newstatic/images/zt/img01.jpg" width="400" height="241" /></div></td>
              </tr>
            </table>
        </div>
      <div class="bd bd03">
       	<div class="t_img"><img src="/newstatic/images/zt/title03.jpg" width="990" height="31" /></div>
          <ul>
           	  <li class="question">
               	  <div class="img"><img src="/newstatic/images/zt/img02.jpg" width="300" height="176" /><h3>宝爸备孕必知</h3></div>
                  <p>在我们当今社会，社会节奏很快，尤其在北上广等大中城市，人们的工作压力、生活压力都非常大，更不要说男人了，已婚男人更甚。上有老下有小，自古就有“男人是家里的顶梁柱”之说，故男人不得不在外努力打拼，为一家老小撑起一片天。无休止的加班、整晚整晚的应酬、喝不完的酒、抽不断的香烟让男人的身体变得不堪一击，亚健康问题越来越突出，性冷淡、生育能力下降问题不断辈出。<a href="/news-man/detail-774.html" target="_blank">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-baobabeiyunbizhi" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                    {{foreach from=$data.baobabeiyunbizhi item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                   </ul>
                  </div>
              </li>
              <li class="question">
               	  <div class="img"><img src="/newstatic/images/zt/img03.jpg" width="297" height="176" /><h3>宝妈备孕必知</h3>
               	  </div>
                  <p>很多30岁以上的女性在进行了充分的孕前准备之后，开始了自己的妊娠计划，但不论怎样努力，都无法孕育出期盼中的新生命。造成了不孕的原因除了一些疾病之外，年龄本身也是重要原因之一。在女性的生育期内，可正常发育并正常排卵的卵泡只有400个。因此，在女性卵泡池中只有很少一部分能最终排卵，而绝大部分卵泡均闭锁，这是一个不可逆的逐步消耗的过程，但是卵泡细胞缺乏再生能力，因而导致女性生殖力随着年龄增长而衰退
                  <a href="/news-women/detail-956.html" target="_blank">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-baomabeiyunbizhi" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                      {{foreach from=$data.baomabeiyunbizhi item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                    </ul>
                  </div>
              </li>
              <li class="question last">
               	  <div class="img"><img src="/newstatic/images/zt/img04.jpg" width="300" height="176" /><h3>增强免疫力，加强体质</h3>
               	  </div>
                  <p>随着人们年龄的增长，人的身体机能是逐渐衰弱的，人自身的抵抗力、免疫力都会跟着不断下降，但是，想拥有自己的宝宝，宝爸、宝妈当务之急就是要增强自身的免疫力和抵抗力，加强自身的体质，只有这样做，才能为要宝宝打好基础。
                  <a href="/news-women/detail-841.html" target="_blank">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-zengqiangmianyi" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                   {{foreach from=$data.zengqiangmianyi item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                    </ul>
                  </div>
              </li>
              <li class="question">
               	  <div class="img"><img src="/newstatic/images/zt/img05.jpg" width="300" height="176" /><h3>补充叶酸</h3>
               	  </div>
                  <p>叶酸（FolicAcid）也叫维生素B9，是一种水溶性维生素，存在于小到病毒、细菌，大到人类的所有生命系统中，因最初是从菠菜叶中提取得到的，故称为叶酸。有促进骨髓中幼细胞成熟的作用，人类如缺乏叶酸可引起巨红细胞性贫血以及白细胞减少症，对孕妇尤其重要。天然叶酸广泛存在于动植物类食品中，尤以酵母、肝及绿叶蔬菜中含量比较多。<a href="/news-women/detail-958.html" target="_blank">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-buchongyesuan" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                    {{foreach from=$data.buchongyesuan item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                    </ul>
                  </div>
              </li>
              <li class="question">
               	  <div class="img"><img src="/newstatic/images/zt/img06.jpg" width="300" height="176" /><h3>孕前调理</h3>
               	  </div>
                  <p>孕前该怎么调养，才能顺利怀孕并拥有健康的宝宝？ 孕育，为妈妈这个神圣而伟大的词语做出了刻骨铭心的注脚。拥有一个健康的宝宝，是天下妈妈的心愿，所以做好孕前调养是重中之重。在这期间，要怎么“补”才科学宁不敢随便“补”，不“补”会不会亏了自己和宝宝，“补”错了怎么办？<a href="/news-women/detail-949.html" target="_blank">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a target="_blank" href="/chanel-yunqiantiaoli"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                      {{foreach from=$data.yunqiantiaoli item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                    </ul>
                  </div>
              </li>
              <li class="question last">
               	  <div class="img"><img src="/newstatic/images/zt/img07.jpg" width="300" height="176" /><h3>孕前营养补充</h3>
               	  </div>
                  <p>对于准备怀孕的准妈妈来说，孕期的营养对小孩&quot;先天&quot;的身体条件是起决定性作用的!如孕期VB族充足的话,小孩将来的智力会比常人高20%至少,孕期VE充足的话,小孩出生后患黄疸的机会会比常人低50%以上,孕期小孩优质蛋白充足的话,出生后体质会比常人高50%以上,孕期叶酸铁质充足的话,出生后产生畸形的机率会比常人低80%以上<a target="_blank" href="/news-women/detail-950.html">[全文阅读]</a> </p>
                  <div class="articleList">
                    <div class="title"><h2>推荐文章阅读</h2><a target="_blank" href="/chanel-yunqianyingyangbucho"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                    <ul>
                         {{foreach from=$data.yunqianyingyangbucho item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                    </ul>
                  </div>
              </li>
          </ul>
        </div>
        <div class="bd bd04">
        	<div class="t_img"><img src="/newstatic/images/zt/title04.jpg" width="990" height="31" /></div>
            <div class="pre">千辛万苦，终于迎来了自己的宝宝，这不仅仅是准爸爸准妈妈最为开心的事情，更是能引起两个家庭“沸腾”的大喜事。所以，为了顺利安全地诞下一个健康的宝宝，对于准妈妈来说，尤其是作为高龄产妇，孕期的任务还艰巨得很哦！正确地安胎保胎，平衡的饮食，必需营养的补充，孕期钙质的摄取，护肤品的正确使用都显得特别重要，为了到来的宝宝，准妈妈们必须要做好各门功课哦！</div>
            <ul>
            	<li class="notice">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td rowspan="2"><img src="/newstatic/images/zt/img08.jpg?v=11"  valign="top"/>
                    <td><p>孕妇膳食首先应该遵循平衡膳食原则。平衡膳食必须由多种食物构成。中国营养学会推荐的《中国居民膳食指南》是平衡膳食原则的直观体现。鉴于孕期营养需要、生理和代谢特点，孕妇膳食应以这些原则为基础进行调整，如孕前期叶酸的适量补充、孕中期富含优质蛋白质的动物性食物的适量增加等。<a target="_blank" href="/news-women/detail-951.html">[全文阅读]</a></p></td>
                  </tr>
                  <tr>
                    <td>
                    <div class="articleList">
                      <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-pinghenyinshi" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                      <ul>
                       {{foreach from=$data.pinghenyinshi item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                    {{/foreach}}
                      </ul>
                    </div></td>
                  </tr>
                </table>
            </li>
            <li class="notice">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td rowspan="2" valign="top"><img src="/newstatic/images/zt/img09.jpg?v=11" /></td>
                    <td><p>因为胎儿骨骼形成所需要的钙完全来源于母体，钙于孕期需增加储存30g，光靠饮食中的钙是不够的，因此就要求孕妇在孕期要多补充钙剂。由于孕期（孕早期、孕中期、孕晚期）不同，孕妇对钙的需要也不同，孕妇怀孕早期，需以膳食为主的其它途径补充钙元素约为300—600mg，怀孕中、晚期，需补充钙元素约为400—800mg<a href="/news-women/detail-947.html" target="_blank">[全文阅读]</a></p></td>
                  </tr>
                  <tr>
                    <td>
                    <div class="articleList">
                      <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-yunqibugai" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                      <ul>
                       {{foreach from=$data.yunqibugai item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                      </ul>
                    </div></td>
                  </tr>
                </table>
            </li>
            <li class="notice">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><p>由于荷尔蒙和身体的变化，孕期女性肌肤抵抗能力下降，面色会变差，身体也可能变得浮肿，整个人显得衰老很多。那么，孕期女人应该如何保养才能拥有好肌肤呢？其实，对于孕期护肤，准妈妈们不用过分惊慌，只要正确使用孕妇专用的护肤品，减少使用美白或淡斑类型的护肤品，多选择一些天然、无添加的护肤品，正确进行日常皮肤保养，当个漂漂的孕妈咪是没有问题的。<a href="/news-women/detail-948.html" target="_blank">全文阅读]</a></p></td>
                    <td rowspan="2" valign="top"><img src="/newstatic/images/zt/img10.jpg?v=11" /></td>
                  </tr>
                  <tr>
                    <td><div class="articleList">
                      <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-yunqihufupin"  target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                      <ul>
                          {{foreach from=$data.yunqihufupin item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                      </ul>
                    </div></td>
                    </tr>
                </table>

               
            </li>
            <li class="notice">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td rowspan="2" valign="top"><img src="/newstatic/images/zt/img11.jpg?v=11" /></td>
                    <td><p>妊娠是一个让人既喜且忧的生理过程，因为孕期各阶段注意事项都不同，所以准妈妈们要注意了，一定要去多去了解孕期注意事项有哪些。特别要知道孕期三个月注意事项，因为前三月算是比较危险的时期，在生活细节上要尤其留意，不然很容易造成流产<a href="/news-women/detail-941.html" target="_blank">[全文阅读]</a></p></td>
                  </tr>
                  <tr>
                    <td>
                    <div class="articleList">
                      <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-antaibaotai" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                      <ul>
                         {{foreach from=$data.antaibaotai item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                      </ul>
                    </div></td>
                  </tr>
                </table>
            </li>
            </ul>
        </div>
      <div class="bd bd05">
        	<div class="t_img"><img src="/newstatic/images/zt/title05.jpg" width="990" height="31" /></div>
        <div class="pre">经过十月怀胎的辛苦，妈妈们终于等到分娩的日子了。使尽全身力气，看着自己的宝宝就躺在自己身边，妈妈们如释重负，终于是松了一口气。但是，再看看自己，昔日的身材已经明显走了形，满是赘肉的肚子，臃肿的身子让妈妈们不免又有些黯然自伤，严重的还会患上产后抑郁症，尤其是作为职场妈妈，产后身体的恢复、产后迅速减肥、抑郁症的摆脱都显得颇为重要。</div>
          <ul>
          	<li class="notice02">
              <img src="/newstatic/images/zt/notice01.jpg" width="231" height="199" />
              <p>产后恢复，是指女性在生产完毕之后，常常会因为身体过于虚弱而需要一定的恢复和保养，而这种恢复和保养被称之为产后恢复。产后恢复包含的主要方面有产后会阴部位的私处细胞活力因子护理以及产后的体形恢复、产后的子宫恢复和产后的心理恢复，女性在恢复期间一定要注意营养饮食的均衡。<a href="/news-women/detail-934.html" target="_blank">[全文阅读]</a></p>
              <div class="articleList">
                  <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-chanhouhuifu" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                  <ul>
                       {{foreach from=$data.chanhouhuifu item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                  </ul>
              </div>
            </li>
            <li class="notice02">
              <img src="/newstatic/images/zt/notice02.jpg" width="231" height="199" />
              <p>产后减肥是指女性在生产过后进行的减肥。产后减肥方法有很多种，运动、饮食与药物的方法比较常见。常见的产后减肥方法有：饮食减肥，即通过调整，通过改变饮食结构来达到减肥目的，也可以吃一些减肥产品来进行减肥<a href="/news-women/detail-957.html" target="_blank">[全文阅读]</a></p>
              <div class="articleList">
                  <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-chanhoujianfei" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                  <ul>
                     {{foreach from=$data.chanhoujianfei item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                  </ul>
              </div>
            </li>
            <li class="notice02">
              <img src="/newstatic/images/zt/notice03.jpg" width="231" height="199" />
              <p>产后抑郁症是女性精神障碍中最为常见的类型，是女性生产之后，由于性激素、社会角色及心理变化所带来的身体、情绪、心理等一系列变化。典型的产后抑郁症是产后6周内发生，可持续整个产褥期，有的甚至持续至幼儿上学前。产后抑郁症的发病率在15%～30%。产后抑郁症通常在6周内发病，可在3～6个月自行恢复，但严重的也可持续1～2年，再次妊娠则有20%～30%的复发率。 <a href="/news-women/detail-959.html" target="_blank">[全文阅读]</a></p>
              <div class="articleList">
                  <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-chanhouyiyuzheng" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                  <ul>
                  {{foreach from=$data.chanhouyiyuzheng item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                  </ul>
              </div>
            </li>
            <li class="notice02">
              <img src="/newstatic/images/zt/notice04.jpg" width="231" height="199" />
              <p>高龄孕妇产后都很虚弱，一定要吃些补血的食品，但不能吃红参等大补之物，以防虚不受补。比较适合的是桂圆、乌鸡等温补之物。此外，要补充蛋白质。蛋白质可以促进伤口愈合，牛奶、鸡蛋、海鲜等动物蛋白和黄豆等动物蛋白都应该多吃。 <a href="/news-women/detail-898.html" target="_blank">[全文阅读]</a></p>
              <div class="articleList">
                  <div class="title"><h2>推荐文章阅读</h2><a href="/chanel-chanhouhuli" target="_blank"><img src="/newstatic/images/zt/more.jpg" width="42" height="11" /></a></div>
                  <ul>
                     {{foreach from=$data.chanhouhuli item=v key=k}}
                    	<li>{{$k+1}}.<a href="/news-{{$v.as_name|default:'jiankang'}}/detail-{{$v.article_id}}.html" target="_blank">{{$v.title}}</a></li>
                       {{/foreach}}
                  </ul>
              </div>
            </li>
          </ul>
        </div>
        <!--  <div class="bd bd06">
        	<div class="t_img"><img src="/newstatic/images/zt/title06.jpg" width="990" height="31" /></div>
        	<img src="/newstatic/images/zt/qishu.jpg" width="974" height="86" usemap="#Map" border="0" />
            <map name="Map" id="Map">
              <area shape="rect" coords="90,0,177,60" href="###" />
              <area shape="rect" coords="316,22,421,89" href="###" />
              <area shape="rect" coords="578,0,665,55" href="###" />
              <area shape="rect" coords="817,16,903,102" href="###" />
            </map> 
            <div class="w_zhuanti">
            	<ul style="display:block;">
                	<li>
                    	<div class="img"></div>
                        <p>*11111**专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**11111*专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**11111*专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**11111*专题</p>
                    </li>
                </ul>
                <ul>
                	<li>
                    	<div class="img"></div>
                        <p>**22222222222***专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**222222222*专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>*22222222222**专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**222*专题</p>
                    </li>
                </ul>
                <ul>
                	<li>
                    	<div class="img"></div>
                        <p>**333***专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>**333*专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>***333**专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>***333**专题</p>
                    </li>
                </ul>
                <ul>
                	<li>
                    	<div class="img"></div>
                        <p>***444**专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>***444**专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>*44****专题</p>
                    </li>
                    <li>
                    	<div class="img"></div>
                        <p>****44*专题</p>
                    </li>
                </ul>
            </div>
            <script type="text/javascript">
            	$(function(){
					$(".bd06 #Map area").click(function(){
						var index=$(".bd06 #Map area").index(this);
						$(".w_zhuanti ul").eq(index).show().siblings().hide();
					})
				})
            </script>
      </div>
       -->
      
      <div class="bd bd07">
      	<div class="t_img"><img src="/newstatic/images/zt/title07.jpg" width="990" height="31" /></div>
        <div class="w_words">
        
        	<div class="fl" id="comment-box">
            
            </div>
            
            <div class="fr">
            	<div class="weibo_attention">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td rowspan="3"><img src="/newstatic/images/zt/logo_sina.jpg"  width="51" height="42"/></td>
                        <td height="31" colspan="2" valign="top">北大荒垦丰种业股份有限公司</td>
                      </tr>
                      <tr>
                        <td><img src="/newstatic/images/zt/v_sina.jpg" width="79" height="22" /></td>
                        <td align="right"><a href="http://e.weibo.com/guoyao1jiankang" target="_blank"><img src="/newstatic/images/zt/add_attention.jpg" width="49" height="20" /></a></td>
                      </tr>
                      <tr>
                        <td colspan="2" valign="bottom">关注微博，活动特价早知道</td>
                      </tr>
                    </table>
                    <div class="weibo_code">
                        <img src="http://img.1jiankang.com/images/shop/code.gif" /> 
                        <p><b>我的眼里只有你，关注送好礼</b></p>
                        <p>扫一扫加微信好友</p>
                        <p>或者查找微信号 <em>gy1jiankang</em></p>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="bd bd08">
      	<div class="t_img"><img src="/newstatic/images/zt/title08.jpg" width="990" height="31" /></div>
        <div class="w_idea">
        	<div class="fl">
            	<div class="title_idea"><h4><img src="/newstatic/images/zt/title09.jpg" width="137" height="27" /></h4>
            	  <span>您的观点、困惑、疑问或是意见，都可以写在这里，大家一同分享！
</span></div>
		  <textarea name="content" id="content" ></textarea>
          </div>
            <div class="fr">
            <form action="/auth/fast-login/" onsubmit="return fast_login();" method="post" id="fast_loginFrm" {{if $user}}class="none"{{/if}}>
            	<!--未登录 begin---->
              <table width="100%" id="logining" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="right">用户名 ：                   </td>
                    <td><input type="text" name="user_name" id="user_name" /></td>
                    <td><a href="javascript:;" onclick="$('#fast_loginFrm').submit();"><img src="/newstatic/images/zt/btn_login.jpg" width="113" height="31" /></a></td>
                  </tr>
                  <tr>
                    <td align="right">密码：</td>
                    <td><input type="password" name="password" id="password" /></td>
                    <td><a href="/reg.html">注册请点击这里</a></td>
                  </tr>
                  <tr>
                    <td colspan="2"  >
                    <br/>
                    <a href="javascript:;" onclick="zt_comment()"><img src="/newstatic/images/zt/btn_comment.jpg" width="90" height="30" /></a></td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
               </form> 
				<!--未登录 end---->		
			
                <!--已登录 begin---->
                 <table id="login_success" width="100%" border="0" cellspacing="0" cellpadding="0" 	{{if !$user}}class="none"{{/if}} >
                  <tr>
                    <td><p><em id="username_txt">用户名：{{$user.user_name}}</em> <b>欢迎回来</b></p></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><a  href="javascript:;" onclick="zt_comment()"><br />
                    <img src="/newstatic/images/zt/btn_comment.jpg" width="90" height="30" /></a></td>
                    <td>&nbsp;</td>
                  </tr>
                 </table>
               
				<!--已登录 end---->			

            </div>
        </div>
      </div>
  </div>    
</div>
<script>
get_zt_comment(1);
function fast_login()
{
	if($("#user_name").val() == '')
	{
	  $.dialog.alert("请输入用户名");
	  $("#user_name").focus();
	  return false;
	}
	if($("#password").val() == '')
	{
	   $.dialog.alert("请输入密码");
	   $("#password").focus();
	  return false;
	}	
	
	var params = $("#fast_loginFrm").serializeArray();
	 $.post($("#fast_loginFrm").attr('action'),params,
				function(data)
				{
			       if(data.status=='yes')
			    	  {	  
			    	    $("#fast_loginFrm").hide();
			    	    $("#username_txt").html('用户名：'+data.user_name);
			    	  	$("#login_success").show();    	   
			    	  }else{
			    		  $.dialog.alert(data.msg);
			    	  }
	},'json');		
	return false;
}

function zt_comment()
{
  var content = $("#content").val();
  if(content == '')
   {	  
	  $.dialog.alert("请输入评论内容！")
	  $("#content").focus();
	  return false;
  }  
  if(content.length<5 || content.length>500)
  {
	  $.dialog.alert("评论内容在5-500字以内！");
	  $("#content").focus();
	  return false; 
  }

  $.post('/news/zt-comment/',{content:content},function(data){
	  if(data.status == 1)
	  {
		get_zt_comment(1);
		$("#content").val(''); 
		
	  }else if(data.unlogin == 1){
		  $("#login_success").hide();  
		  $("#fast_loginFrm").show();
	  }
	  $.dialog.alert(data.msg).time(2000);
  },'json');  
}

function get_zt_comment(page)
{
  $.get('/news/get-comment/',{page:page},function(data){
	  $("#comment-box").hide().fadeIn().html(data.html);
  },'json');
}
</script>
{{include file="news/inc-footer.tpl"}}