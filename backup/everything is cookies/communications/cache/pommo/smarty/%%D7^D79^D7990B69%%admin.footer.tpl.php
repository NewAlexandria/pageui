<?php /* Smarty version 2.6.13, created on 2008-07-14 06:34:11
         compiled from inc/admin.footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'inc/admin.footer.tpl', 8, false),)), $this); ?>

<br class="clear" />
</div>
<!-- end content -->

<div id="footer">

<p>- <?php $this->_tag_stack[] = array('t', array('escape' => 'no','url' => '<a href="http://www.pommo.org/">poMMo</a>')); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Page fueled by %1 mailing management software<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> -</p>

</div>
<!-- end footer -->

<?php echo $this->_smarty_vars['capture']['footer']; ?>

<?php echo $this->_smarty_vars['capture']['dialogs']; ?>


</body>
</html>