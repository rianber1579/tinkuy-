<?php
class Usuario{
 static function porEmail($em){$s=db()->prepare('SELECT * FROM users WHERE email=?');$s->execute([$em]);return $s->fetch();}
 static function crear($nm,$em,$pw,$role){$pdo=db();$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)')->execute([$nm,$em,password_hash($pw,PASSWORD_DEFAULT),$role]);$id=$pdo->lastInsertId();
  if($role==='seller')$pdo->prepare('INSERT INTO stores(user_id,name,region,description) VALUES(?,?,?,?)')->execute([$id,"Taller de $nm",'Cusco','Cuenta aquí la historia de tu taller.']);return $id;}
}
