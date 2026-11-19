<?php
// Modelo: datos de la tienda de un vendedor.
class Tienda{
 /** Retorna la tienda del usuario vendedor (fila de stores) o false si no tiene. */
 static function delUsuario($uid){$s=db()->prepare('SELECT * FROM stores WHERE user_id=?');$s->execute([$uid]);return $s->fetch();}
 /** Actualiza nombre, region y descripcion de la tienda indicada. */
 static function actualizar($id,$nombre,$region,$desc){db()->prepare('UPDATE stores SET name=?,region=?,description=? WHERE id=?')->execute([$nombre,$region,$desc,$id]);}
}