<?php
// Modelo: ventas y pedidos recibidos por una tienda (metricas del panel).
class VentasTienda{
 /** Retorna totales de la tienda: tot (ventas), ped (pedidos), uni (unidades) y pend (items por enviar). */
 static function resumen($sid){$s=db()->prepare("SELECT COALESCE(SUM(qty*price),0) tot,COUNT(DISTINCT order_id) ped,COALESCE(SUM(qty),0) uni,COALESCE(SUM(status='pendiente'),0) pend FROM order_items WHERE store_id=?");$s->execute([$sid]);return $s->fetch();}
 /** Retorna los 5 productos mas vendidos de la tienda (nombre, unidades y monto). */
 static function masVendidos($sid){$s=db()->prepare('SELECT p.name,SUM(oi.qty) u,SUM(oi.qty*oi.price) m FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.store_id=? GROUP BY p.id ORDER BY u DESC LIMIT 5');$s->execute([$sid]);return $s->fetchAll();}
 /** Retorna las ventas por dia de los ultimos 7 dias (d = fecha, m = monto). */
 static function porDia($sid){$s=db()->prepare('SELECT DATE(o.created_at) d,SUM(oi.qty*oi.price) m FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE oi.store_id=? AND o.created_at>=DATE_SUB(CURDATE(),INTERVAL 6 DAY) GROUP BY d ORDER BY d');$s->execute([$sid]);return $s->fetchAll();}
 /** Retorna los items vendidos por la tienda con comprador, direccion y estado. */
 static function pedidos($sid){$s=db()->prepare('SELECT oi.id,oi.qty,oi.price,oi.status,o.id oid,o.address,u.name buyer,p.name FROM order_items oi JOIN orders o ON o.id=oi.order_id JOIN users u ON u.id=o.user_id JOIN products p ON p.id=oi.product_id WHERE oi.store_id=? ORDER BY o.id DESC');$s->execute([$sid]);return $s->fetchAll();}
 /** Cambia el estado (pendiente, enviado o entregado) de un item que pertenece a la tienda. */
 static function cambiarEstado($item,$sid,$estado){db()->prepare('UPDATE order_items SET status=? WHERE id=? AND store_id=?')->execute([$estado,$item,$sid]);}
}