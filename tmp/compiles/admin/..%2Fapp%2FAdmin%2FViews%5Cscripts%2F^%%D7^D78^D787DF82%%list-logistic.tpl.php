<?php /* Smarty version 2.6.19, created on 2014-11-12 16:46:01
         compiled from logistic/list-logistic.tpl */ ?>
<div class="search">
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"add-logistic",)));?>" method="post">
<table>
    <tr>
        <td >物流公司名</td>
        <td width=150><input type="text" name="name" /></td>
        <td >物流公司类别</td>
        <td width=150>
            <select name="logistic_code">
            <?php $_from = $this->_tpl_vars['logisticPlugin']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code'] => $this->_tpl_vars['label']):
?>
            <option value=<?php echo $this->_tpl_vars['code']; ?>
><?php echo $this->_tpl_vars['label']; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
            </select>        </td>
        <td >代收款费率</td>
        <td width=150><input type="text" name="cod_rate" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>排序</td>
        <td><input type="text" name="sort" /></td>
        <td>启用</td>
        <td>
            <select name="open">
            <option value=0>否</option>
            <option value=1>是</option>
            </select>        </td>
        <td>简介</td>
        <td><input type="text" name="brief" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td>连接地址</td>
      <td colspan="5"><input type="text" name="url" /></td>
      <td><input type="submit" value="添加" /></td>
    </tr>
</table>
</form>
</div>

<div id="ajax_search">
<div class="title">物流管理 -&gt; 物流公司管理</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
    <tr>
        <td >快递公司别名</td>
        <td >快递公司类别</td>
        <td >代收款费率</td>
        <td >代收款最小费用</td>
        <td >服务费</td>
        <td >排序</td>
        <td >启用</td>
        <td >操作区域管理</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['logistic']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr>
        <td><?php echo $this->_tpl_vars['data']['name']; ?>
</td>
        <td>
            <?php $_from = $this->_tpl_vars['logisticPlugin']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['code'] => $this->_tpl_vars['label']):
?>
            <?php if ($this->_tpl_vars['code'] == $this->_tpl_vars['data']['logistic_code']): ?><?php echo $this->_tpl_vars['label']; ?>
<?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>        </td>
        <td><?php echo $this->_tpl_vars['data']['cod_rate']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['cod_min']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['fee_service']; ?>
</td>
        <td><?php echo $this->_tpl_vars['data']['sort']; ?>
</td>
        <td><?php if ($this->_tpl_vars['data']['open']): ?>是<?php else: ?>否<?php endif; ?></td>
        <td><input type="button" value="操作区域管理" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"list-logistic-area",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" /></td>
        <td>
            <input type="button" value="编辑" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"edit-logistic",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" />
            <input type="button" value="删除" onclick="if(confirm('确定删除？')){G('<?php echo $this -> callViewHelper('url', array(array('action'=>"del-logistic",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>');}" />
            <input type="button" value="模板" onclick="G('<?php echo $this -> callViewHelper('url', array(array('action'=>"template-logistic",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" />
            <input type="button" value="导出" onclick="window.open('<?php echo $this -> callViewHelper('url', array(array('action'=>"export-logistic",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>')" />
            <input type="button" value="导入" onclick="openDiv('<?php echo $this -> callViewHelper('url', array(array('action'=>"import-logistic",'logistic_code'=>$this->_tpl_vars['data']['logistic_code'],)));?>','ajax','数据导入',MsgW=500,MsgH=200);" />
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
</table>
</div>
</div>