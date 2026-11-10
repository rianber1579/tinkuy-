<?php require 'config.php';$reg=($_GET['m']??'')==='reg';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();$em=trim($_POST['email']);$pw=$_POST['password'];
 if(isset($_POST['name'])){$nm=trim($_POST['name']);$role=$_POST['role']==='seller'?'seller':'buyer';
  if(!$nm||!filter_var($em,FILTER_VALIDATE_EMAIL)||strlen($pw)<6)$err='Completa el nombre, un correo válido y una contraseña de 6+ caracteres.';
  else{$q=$pdo->prepare('SELECT id FROM users WHERE email=?');$q->execute([$em]);
   if($q->fetch())$err='Ese correo ya tiene una cuenta. Ingresa en su lugar.';
   else{$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)')->execute([$nm,$em,password_hash($pw,PASSWORD_DEFAULT),$role]);
    $id=$pdo->lastInsertId();if($role==='seller')$pdo->prepare('INSERT INTO stores(user_id,name,region,description) VALUES(?,?,?,?)')->execute([$id,"Taller de $nm",'Cusco','Cuenta aquí la historia de tu taller.']);
    $_SESSION['u']=['id'=>$id,'name'=>$nm,'role'=>$role];header('Location: '.($role==='seller'?'panel.php?t=tienda':'index.php'));exit;}}}
 else{$q=$pdo->prepare('SELECT * FROM users WHERE email=?');$q->execute([$em]);$u=$q->fetch();
  if($u&&password_verify($pw,$u['password'])){$_SESSION['u']=['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];header('Location: '.($u['role']==='seller'?'panel.php':'index.php'));exit;}
  $err='Correo o contraseña incorrectos.';}}
$title=$reg?'Crear cuenta':'Ingresar';include 'header.php';?>
<section class="card narrow"><h1><?=$reg?'Crea tu cuenta':'Ingresa a tu cuenta'?></h1><?php if($err):?><p class="err"><?=e($err)?></p><?php endif;?>
<form method="post"><input type="hidden" name="t" value="<?=csrf()?>">
<?php if($reg):?><label>Nombre<input name="name" required></label><?php endif;?>
<label>Correo<input type="email" name="email" required></label><label>Contraseña<input type="password" name="password" required></label>
<?php if($reg):?><label>Quiero<select name="role"><option value="buyer">Comprar productos</option><option value="seller">Vender mis productos (abrir tienda)</option></select></label><?php endif;?>
<button class="btn"><?=$reg?'Crear cuenta':'Ingresar'?></button></form>
<p class="muted"><?=$reg?'¿Ya tienes cuenta? <a href="auth.php">Ingresa</a>':'¿Primera vez? <a href="auth.php?m=reg">Crea tu cuenta</a>'?></p></section><?php include 'footer.php';?>
