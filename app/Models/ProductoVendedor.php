<?php
// Modelo: productos desde el punto de vista del vendedor (publicar y retirar).
class ProductoVendedor{
 /** Retorna los productos de una tienda, del mas reciente al mas antiguo. */
 static function deTienda($sid){$s=db()->prepare('SELECT * FROM products WHERE store_id=? ORDER BY id DESC');$s->execute([$sid]);return $s->fetchAll();}
 /** Inserta un producto en la tienda. La foto es el nombre del archivo guardado o null. */
 static function crear($sid,$cat,$nombre,$desc,$precio,$stock,$foto){
  db()->prepare('INSERT INTO products(store_id,category_id,name,description,price,stock,image) VALUES(?,?,?,?,?,?,?)')->execute([$sid,$cat,$nombre,$desc,$precio,$stock,$foto]);}
 /** Elimina un producto de la tienda. Si ya tiene ventas no se puede borrar (clave foranea): se oculta con stock 0. Retorna true si se elimino, false si solo se oculto. */
 static function eliminar($id,$sid){
  $pdo=db();$v=$pdo->prepare('SELECT COUNT(*) FROM order_items WHERE product_id=?');$v->execute([$id]);
  if($v->fetchColumn()>0){$pdo->prepare('UPDATE products SET stock=0 WHERE id=? AND store_id=?')->execute([$id,$sid]);return false;}
  $pdo->prepare('DELETE FROM products WHERE id=? AND store_id=?')->execute([$id,$sid]);return true;}
}