<?php
class CatalogoController{
 function index(){view('catalogo/index',['title'=>'Catálogo','rows'=>Producto::listar($_GET),'cats'=>Producto::categorias()]);}
 function detalle(){$id=(int)($_GET['id']??0);$p=Producto::buscar($id);
  if(!$p){http_response_code(404);exit('Producto no encontrado. <a href="index.php">Volver</a>');}
  view('catalogo/detalle',['title'=>$p['name'],'p'=>$p,'rv'=>Resena::delProducto($id)]);}
 function resenar(){need('buyer');chk();$id=(int)$_POST['id'];
  if(!Pedido::compro(user()['id'],$id))flash('Solo puedes valorar productos que compraste.');
  else{Resena::guardar($id,user()['id'],(int)$_POST['rating'],trim($_POST['comment']??''));flash('Gracias por tu valoración.');}
  go('catalogo/detalle',['id'=>$id]);}
}
