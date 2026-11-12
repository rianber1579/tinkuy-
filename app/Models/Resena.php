<?php
class Resena{
 static function delProducto($id){$s=db()->prepare('SELECT r.*,u.name FROM reviews r JOIN users u ON u.id=r.user_id WHERE product_id=? ORDER BY r.id DESC');$s->execute([$id]);return $s->fetchAll();}
 static function guardar($pid,$uid,$rating,$com){db()->prepare('REPLACE INTO reviews(product_id,user_id,rating,comment) VALUES(?,?,?,?)')->execute([$pid,$uid,min(5,max(1,$rating)),substr($com,0,400)]);}
}
