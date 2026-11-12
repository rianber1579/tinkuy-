<section class="card narrow"><h1><?=$reg?'Crea tu cuenta':'Ingresa a tu cuenta'?></h1><?php if($err):?><p class="err"><?=e($err)?></p><?php endif;?>
<form method="post" action="<?=url($reg?'auth/registro':'auth/login')?>"><input type="hidden" name="t" value="<?=csrf()?>">
<?php if($reg):?><label>Nombre<input name="name" required></label><?php endif;?>
<label>Correo<input type="email" name="email" required></label><label>Contraseña<input type="password" name="password" required></label>
<?php if($reg):?><label>Quiero<select name="role"><option value="buyer">Comprar productos</option><option value="seller">Vender mis productos (abrir tienda)</option></select></label><?php endif;?>
<button class="btn"><?=$reg?'Crear cuenta':'Ingresar'?></button></form>
<p class="muted"><?=$reg?'¿Ya tienes cuenta? <a href="'.url('auth/login').'">Ingresa</a>':'¿Primera vez? <a href="'.url('auth/registro').'">Crea tu cuenta</a>'?></p></section>
