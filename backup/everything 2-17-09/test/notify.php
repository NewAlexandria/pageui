<?php If ( $_REQUEST['err'] ) { ?>	<div class="notify_on">	<div class="notify_text">	 <?php echo $_REQUEST['err']; ?>	</div>	</div><?php } else {	echo "all things working well";}?>