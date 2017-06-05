<?php /* Smarty version 2.6.13, created on 2008-07-14 06:36:16
         compiled from admin/admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'admin/admin.tpl', 3, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/admin.header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h2><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Admin Menu<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

<div id="language" class="right">
<form method="POST" action="" id="language">
<select name="lang" onChange="this.form.submit();">
<option value="en">English (en)</option>
<option value="en-uk" <?php if ($this->_tpl_vars['lang'] == 'en-uk'): ?>SELECTED<?php endif; ?>>british english (en-uk)</option>
<option value="bg" <?php if ($this->_tpl_vars['lang'] == 'bg'): ?>SELECTED<?php endif; ?>>български (bg)</option>
<option value="da" <?php if ($this->_tpl_vars['lang'] == 'da'): ?>SELECTED<?php endif; ?>>dansk (da)</option>
<option value="de" <?php if ($this->_tpl_vars['lang'] == 'de'): ?>SELECTED<?php endif; ?>>deutsch (de)</option>
<option value="es" <?php if ($this->_tpl_vars['lang'] == 'es'): ?>SELECTED<?php endif; ?>>español (es)</option>
<option value="fr" <?php if ($this->_tpl_vars['lang'] == 'fr'): ?>SELECTED<?php endif; ?>>français (fr)</option>
<option value="it" <?php if ($this->_tpl_vars['lang'] == 'it'): ?>SELECTED<?php endif; ?>>italiano (it)</option>
<option value="nl" <?php if ($this->_tpl_vars['lang'] == 'nl'): ?>SELECTED<?php endif; ?>>nederlands (nl)</option>
<option value="pl" <?php if ($this->_tpl_vars['lang'] == 'pl'): ?>SELECTED<?php endif; ?>>polski (pl)</option>
<option value="pt" <?php if ($this->_tpl_vars['lang'] == 'pt'): ?>SELECTED<?php endif; ?>>português (pt)</option>
<option value="pt-br" <?php if ($this->_tpl_vars['lang'] == 'pt-br'): ?>SELECTED<?php endif; ?>>brasil português (pt-br)</option>
<option value="ro" <?php if ($this->_tpl_vars['lang'] == 'ro'): ?>SELECTED<?php endif; ?>>română (ro)</option>
<option value="ru" <?php if ($this->_tpl_vars['lang'] == 'ru'): ?>SELECTED<?php endif; ?>>русский язык (ru)</option>
</select>
</form>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/messages.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="boxMenu">

<div><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
admin/mailings/admin_mailings.php"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/mailing.png" alt="envelope icon" class="navimage" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Mailings<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> - <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Send mailings to the entire list or to a subset of subscribers. Mailing status and history can also be viewed from here.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<div><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
admin/subscribers/admin_subscribers.php"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/subscribers.png" alt="people icon" class="navimage" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Subscribers<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> - <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Here you can list, add, delete, import, export, and update your subscribers. You can also create groups (subsets) of your subsribers from here.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<div><a href="<?php echo $this->_tpl_vars['url']['base']; ?>
admin/setup/admin_setup.php"><img src="<?php echo $this->_tpl_vars['url']['theme']['shared']; ?>
images/icons/settings.png" alt="hammer and screw icon" class="navimage" /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Setup<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> - <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This area allows you to configure poMMo. Set mailing list parameters, choose the information you'd like to collect from subscribers, and generate subscription forms from here.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<br />
<p>
<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>If you like poMMo, please consider a dontation to support its development<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>: <br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
</form>
</p>

</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/admin.footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>