<?php
class Producto{
 static function listar($f){$w=['p.stock>0'];$a=[];
  if(!empty($f['c'])){$w[]='p.category_id=?';$a[]=(int)$f['c'];}
  if(!empty($f['r'])){$w[]='s.region=?';$a[]=$f['r'];}
  if(!empty($f['q'])){$w[]='(p.name LIKE ? OR p.description LIKE ?)';$a[]='%'.$f['q'].'%';$a[]='%'.$f['q'].'%';}
  $s=db()->prepare('SELECT p.*,s.name store,s.region,(SELECT ROUND(AVG(rating),1) FROM reviews WHERE product_id=p.id) avg FROM products p JOIN stores s ON s.id=p.store_id WHERE '.implode(' AND ',$w).' ORDER BY p.id DESC');$s->execute($a);return $s->fetchAll();}
 static function buscar($id){$s=db()->prepare('SELECT p.*,s.name store,s.region,s.description sdesc,c.name cat FROM products p JOIN stores s ON s.id=p.store_id JOIN categories c ON c.id=p.category_id WHERE p.id=?');$s->execute([$id]);return $s->fetch();}
 static function categorias(){return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();}
 static function porIds($ids){if(!$ids)return [];$s=db()->prepare('SELECT * FROM products WHERE id IN('.implode(',',array_fill(0,count($ids),'?')).')');$s->execute(array_values($ids));return $s->fetchAll();}
}
