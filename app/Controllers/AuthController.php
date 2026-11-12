<?php
class AuthController{
 function login(){$err='';
  if($_SERVER['REQUEST_METHOD']==='POST'){chk();$u=Usuario::porEmail(trim($_POST['email']));
   if($u&&password_verify($_POST['password'],$u['password'])){$_SESSION['u']=['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
    if($u['role']==='seller'){header('Location: panel.php');exit;}go('catalogo/index');}
   $err='Correo o contraseña incorrectos.';}
  view('auth/form',['title'=>'Ingresar','reg'=>false,'err'=>$err]);}
 function registro(){$err='';
  if($_SERVER['REQUEST_METHOD']==='POST'){chk();$nm=trim($_POST['name']);$em=trim($_POST['email']);$pw=$_POST['password'];$role=$_POST['role']==='seller'?'seller':'buyer';
   if(!$nm||!filter_var($em,FILTER_VALIDATE_EMAIL)||strlen($pw)<6)$err='Completa el nombre, un correo válido y una contraseña de 6+ caracteres.';
   elseif(Usuario::porEmail($em))$err='Ese correo ya tiene una cuenta. Ingresa en su lugar.';
   else{$id=Usuario::crear($nm,$em,$pw,$role);$_SESSION['u']=['id'=>$id,'name'=>$nm,'role'=>$role];
    flash('Cuenta creada. ¡Bienvenido a Tinkuy!');if($role==='seller'){header('Location: panel.php?t=tienda');exit;}go('catalogo/index');}}
  view('auth/form',['title'=>'Crear cuenta','reg'=>true,'err'=>$err]);}
 function salir(){session_destroy();header('Location: index.php');exit;}
}
