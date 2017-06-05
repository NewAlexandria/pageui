<?php /* Smarty version 2.6.13, created on 2008-07-14 06:34:11
         compiled from inc/messages.tpl */ ?>
<?php if ($this->_tpl_vars['messages']): ?>
<div id="alertmsg" class="warn">

<ul>
<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
<li><strong><?php echo $this->_tpl_vars['msg']; ?>
</strong></li>
<?php endforeach; endif; unset($_from); ?>
</ul>

</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['errors']): ?>
<div id="alertmsg" class="error">

<?php if ($this->_tpl_vars['fatalMsg']): ?><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/alert.png" alt="fatal error icon" /><?php endif; ?>

<ul>
<?php $_from = $this->_tpl_vars['errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
<li><?php echo $this->_tpl_vars['msg']; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>

</div>
<?php endif; ?>