<?php
class Pedido{
 static function crear($uid,$addr,$items){$pdo=db();$pdo->beginTransaction();
  try{$tot=0;$ls=[];foreach($items as [$p,$q]){$q=min($q,$p['stock']);if($q<1)continue;$tot+=$q*$p['price'];$ls[]=[$p,$q];}
   if(!$ls){$pdo->rollBack();return false;}
   $pdo->prepare('INSERT INTO orders(user_id,total,address) VALUES(?,?,?)')->execute([$uid,$tot,$addr]);$oid=$pdo->lastInsertId();
   foreach($ls as [$p,$q]){$pdo->prepare('INSERT INTO order_items(order_id,product_id,store_id,qty,price) VALUES(?,?,?,?,?)')->execute([$oid,$p['id'],$p['store_id'],$q,$p['price']]);
    $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=?')->execute([$q,$p['id']]);}
   $pdo->commit();return $oid;}catch(Exception $x){$pdo->rollBack();return false;}}
 static function delUsuario($uid){$s=db()->prepare('SELECT o.id,oi.qty,oi.price,oi.status,p.id pid,p.name FROM orders o JOIN order_items oi ON oi.order_id=o.id JOIN products p ON p.id=oi.product_id WHERE o.user_id=? ORDER BY o.id DESC');$s->execute([$uid]);return $s->fetchAll();}
 static function compro($uid,$pid){$s=db()->prepare('SELECT 1 FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.user_id=? AND oi.product_id=?');$s->execute([$uid,$pid]);return (bool)$s->fetch();}
}
