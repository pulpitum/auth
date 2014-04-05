<?php echo Theme::partial('email.header'); ?>

Olá <?php echo $user->first_name;?>.<br /><br />

Foi criada uma nova conta na aplicação <a href="<?php echo $site_url;?>">Lactiweb</a> da Lacticoop.<br />

Poderá aceder à <a href="<?php echo $site_url;?>">aplicação</a> utilizando as seguintes credenciais:<br /><br />

Utilizador: <?php echo $user->email;?><br />
Palavra-Passe: <?php echo $password;?><br /><br />
Endereço: <a href="<?php echo $site_url;?>"><?php echo $site_url;?></a><br /><br />

Obrigado<br /><br />


<?php echo Theme::partial('email.footer'); ?>
