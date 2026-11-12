<?php
class CarritoController{
 function index(){$c=$_SESSION['cart']??[];$rows=Producto::porIds(array_keys($c));$tot=0;foreach($rows as $p)$tot+=$p['price']*$c[$p['id']];
  view('carrito/index',['title'=>'Carrito','rows'=>$rows,'c'=>$c,'tot'=>$tot]);}
 function agregar(){chk();$id=(int)$_POST['id'];$p=Producto::buscar($id);
  if(!$p||$p['stock']<1){flash('Ese producto no está disponible.');go('carrito/index');}
  $_SESSION['cart'][$id]=min($p['stock'],($_SESSION['cart'][$id]??0)+max(1,(int)$_POST['qty']));flash('Agregado al carrito.');go('carrito/index');}
 function quitar(){chk();unset($_SESSION['cart'][(int)$_POST['id']]);go('carrito/index');}
 function pagar(){need('buyer');chk();$c=$_SESSION['cart']??[];$addr=trim($_POST['address']??'');$card=preg_replace('/\D/','',$_POST['card']??'');
  if(!$c){flash('Tu carrito está vacío.');go('carrito/index');}
  if(!$addr||strlen($card)<13){flash('Revisa la dirección y el número de tarjeta (mínimo 13 dígitos).');go('carrito/index');}
  $items=array_map(fn($p)=>[$p,$c[$p['id']]],Producto::porIds(array_keys($c)));$oid=Pedido::crear(user()['id'],$addr,$items);
  if(!$oid){flash('No se pudo procesar el pago.');go('carrito/index');}
  $_SESSION['cart']=[];flash("Pedido #$oid confirmado. ¡Gracias por comprar a un artesano!");go('pedidos/index');}
}
