<?php echo Theme::partial('email.header'); ?>

Olá <?php echo $user->first_name;?>.<br /><br />

Recebeu uma mensagem nova.<br />

Obrigado<br /><br />

<?php echo Theme::partial('email.footer'); ?>
