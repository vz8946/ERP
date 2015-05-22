<?php /* Smarty version 2.6.19, created on 2014-10-24 18:39:14
         compiled from offers/buy-gift.tpl */ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">联盟ID</td>
<td>
  <input type="text" name="uid" value="<?php echo $this->_tpl_vars['offers']['config']['uid']; ?>
" size="2"/>&nbsp;&nbsp;
  下家编号 <input type="text" name="aid" value="<?php echo $this->_tpl_vars['offers']['config']['aid']; ?>
" size="5" /> <font color="999999">区分同一联盟不同下家来源,可向技术人员索要</font>
</td>
<td width="10%">是否免运费 *</td>
<td>
  <input name="freight" type="radio" value="1" <?php if ($this->_tpl_vars['offers']['config']['freight'] == 1): ?>checked<?php endif; ?> />是
  <input name="freight" type="radio" value="2" <?php if ($this->_tpl_vars['offers']['config']['freight'] == 2 || ! $this->_tpl_vars['offers']['config']['freight']): ?>checked<?php endif; ?> />否
</td>
<tr>
  <td width="10%">商品数量下限</td>
  <td width="40%"><input type="text" name="number" size="2" value="<?php if ($this->_tpl_vars['offers']['config']['number']): ?><?php echo $this->_tpl_vars['offers']['config']['number']; ?>
<?php else: ?>1<?php endif; ?>" /></td>
  <td colspan="2"><input type="checkbox" name="loop_gift" value="1" <?php if ($this->_tpl_vars['offers']['config']['loop_gift'] == 1): ?>checked<?php endif; ?>> 第N件的倍数都启用买赠</td>
</tr>
<tr>
<td width="10%">
  <input type="hidden" name="allGoods" id="allGoods" value="<?php echo $this->_tpl_vars['offers']['config']['allGoods']; ?>
" />
  <input type="hidden" name="allGroupGoods" id="allGroupGoods" value="<?php echo $this->_tpl_vars['offers']['config']['allGroupGoods']; ?>
"/>
</td>
<td colspan="3">
  <input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '<?php echo $this->_tpl_vars['offers']['offers_type']; ?>
')" />
  <input type="button" value="设置组合商品范围" onclick="openAllGroupGoodsWin('checkbox', 'allGroupGoods', '<?php echo $this->_tpl_vars['offers']['offers_type']; ?>
')" />
</td>
</tr>
<tr>
<td width="10%">
  <input type="hidden" name="allGift" id="allGift" value="<?php echo $this->_tpl_vars['offers']['config']['allGift']; ?>
" />
  <input type="hidden" name="allGiftGroup" id="allGiftGroup" value="<?php echo $this->_tpl_vars['offers']['config']['allGiftGroup']; ?>
" />
</td>
<td colspan="3">
  <input type="button" value="设置赠品商品范围" onclick="openAllGoodsWin('text', 'allGift')" />
  <input type="button" value="设置赠品组合商品范围" onclick="openAllGroupGoodsWin('text', 'allGiftGroup')" />
</td>
</tr>
</table>
<script>
function offersSubmit()
{
    var msg = '';
    return msg;
}
</script>