<?php echo Theme::partial('email.header'); ?>

Olá <?php echo $user->first_name;?>.<br /><br />

Recebeu uma mensagem nova na aplicação <a href="<?php echo $site_url;?>">Lactiweb</a> da Lacticoop.<br />

Obrigado<br /><br />

<?php echo Theme::partial('email.footer'); ?>
