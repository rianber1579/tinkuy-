<?php
session_start();
try{$pdo=new PDO('mysql:host=localhost;dbname=tinkuy;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}
catch(Exception $x){die('No se pudo conectar a la base de datos. Ejecuta <a href="instalar.php">instalar.php</a> primero.');}
const REGIONES=['Amazonas','Ancash','Apurímac','Arequipa','Ayacucho','Cajamarca','Cusco','Huancavelica','Huánuco','Ica','Junín','La Libertad','Lima','Loreto','Piura','Puno','San Martín','Ucayali'];
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function user(){return $_SESSION['u']??null;}
function need($role=null){if(!user()||($role&&user()['role']!==$role)){header('Location: auth.php');exit;}}
function money($n){return 'S/ '.number_format((float)$n,2);}
function csrf(){return $_SESSION['t']??($_SESSION['t']=bin2hex(random_bytes(16)));}
function chk(){if(!hash_equals($_SESSION['t']??'x',$_POST['t']??''))die('Sesión inválida, recarga la página.');}
function flash($m=null){if($m!==null){$_SESSION['f']=$m;return;}$f=$_SESSION['f']??'';unset($_SESSION['f']);return $f;}
function img($p){return $p?'<img src="uploads/'.e($p).'" alt="'.e($p).'" loading="lazy">':'<div class="noimg"></div>';}
function myStore($pdo){$s=$pdo->prepare('SELECT * FROM stores WHERE user_id=?');$s->execute([user()['id']]);return $s->fetch();}
