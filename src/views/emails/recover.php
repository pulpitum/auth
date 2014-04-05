<?php echo Theme::partial('email.header'); ?>

Foi efectuado um pedido de alteração de password da sua conta.<br />
Se não pediu essa alteração simplemente ignore esta mensagem ou reporte ao Adminstrador.<br /><br />

Para continuar o processo de alteração da password aceda ao seguinte endereço:<br /><br />

<a href="<?php echo $recoverUrl;?>"><?php echo $recoverUrl;?></a><br /><br />

Obrigado<br /><br />

<?php echo Theme::partial('email.footer'); ?>
