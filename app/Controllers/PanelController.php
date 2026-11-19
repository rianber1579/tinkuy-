<?php
// Controlador del panel del vendedor (acceso solo para el rol seller).
// Rutas: panel/index (pestanas), panel/guardarTienda, panel/publicar, panel/eliminar, panel/estado.
class PanelController{
 private $tienda;

 /** Exige sesion de vendedor y carga su tienda. */
 function __construct(){
  need('seller');
  $this->tienda=Tienda::delUsuario(user()['id']);
  if(!$this->tienda){flash('No se encontro tu tienda.','err');go('catalogo/index');}
 }

 /** Muestra una pestana del panel segun ?t= (resumen, productos, pedidos o tienda). */
 function index(){
  $t=$_GET['t']??'resumen';
  if(!in_array($t,['resumen','productos','pedidos','tienda']))$t='resumen';
  $sid=$this->tienda['id'];
  $datos=['title'=>'Panel del vendedor','st'=>$this->tienda,'t'=>$t];
  if($t==='resumen'){
   $dia=VentasTienda::porDia($sid);
   $montos=array_map('floatval',array_column($dia,'m'));
   $datos+=['m'=>VentasTienda::resumen($sid),'top'=>VentasTienda::masVendidos($sid),'dia'=>$dia,'mx'=>max(1,$montos?max($montos):1)];
  }elseif($t==='productos'){
   $datos+=['cats'=>Producto::categorias(),'ps'=>ProductoVendedor::deTienda($sid)];
  }elseif($t==='pedidos'){
   $datos+=['o'=>VentasTienda::pedidos($sid)];
  }
  view('panel/'.$t,$datos);
 }

 /** Guarda nombre, region e historia de la tienda (valida nombre y region). */
 function guardarTienda(){
  chk();
  $nombre=trim($_POST['name']??'');$region=$_POST['region']??'';
  if($nombre===''||!in_array($region,REGIONES)){flash('El nombre de la tienda es obligatorio y la region debe ser valida.','err');go('panel/index',['t'=>'tienda']);}
  Tienda::actualizar($this->tienda['id'],$nombre,$region,trim($_POST['description']??''));
  flash('Tienda actualizada.');go('panel/index',['t'=>'tienda']);
 }

 /** Publica un producto nuevo: valida datos y foto, y lo guarda en la tienda. */
 function publicar(){
  chk();
  $nombre=trim($_POST['name']??'');$desc=trim($_POST['description']??'');$precio=(float)($_POST['price']??0);$cat=(int)($_POST['cat']??0);
  $ids=array_map('intval',array_column(Producto::categorias(),'id'));
  if($nombre===''||$desc===''||$precio<=0||!in_array($cat,$ids)){flash('Completa nombre, descripcion, categoria y un precio mayor a 0.','err');go('panel/index',['t'=>'productos']);}
  $foto=$this->guardarFoto();
  if($foto===false){flash('La foto debe ser JPG, PNG o WEBP de menos de 3 MB.','err');go('panel/index',['t'=>'productos']);}
  ProductoVendedor::crear($this->tienda['id'],$cat,$nombre,$desc,$precio,max(0,(int)($_POST['stock']??0)),$foto);
  flash('Producto publicado.');go('panel/index',['t'=>'productos']);
 }

 /** Elimina un producto o, si ya tiene ventas, lo oculta del catalogo. */
 function eliminar(){
  chk();
  if(ProductoVendedor::eliminar((int)($_POST['delp']??0),$this->tienda['id']))flash('Producto eliminado.');
  else flash('Ese producto ya tiene ventas: se oculto del catalogo (stock 0) en lugar de eliminarse.');
  go('panel/index',['t'=>'productos']);
 }

 /** Cambia el estado de un item vendido (pendiente, enviado o entregado). */
 function estado(){
  chk();
  $estado=$_POST['status']??'';
  if(!in_array($estado,['pendiente','enviado','entregado'])){flash('Estado no valido.','err');go('panel/index',['t'=>'pedidos']);}
  VentasTienda::cambiarEstado((int)($_POST['item']??0),$this->tienda['id'],$estado);
  flash('Estado del pedido actualizado.');go('panel/index',['t'=>'pedidos']);
 }

 /** Guarda la foto subida en uploads/. Retorna el nombre del archivo, null si no hay foto, o false si es invalida. */
 private function guardarFoto(){
  $f=$_FILES['image']??null;
  if(!$f||$f['name']==='')return null;
  $ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
  if($f['error']!==UPLOAD_ERR_OK||!in_array($ext,['jpg','jpeg','png','webp'])||$f['size']>=3e6||!@getimagesize($f['tmp_name']))return false;
  $nombre=bin2hex(random_bytes(8)).'.'.$ext;
  move_uploaded_file($f['tmp_name'],dirname(__DIR__,2).'/uploads/'.$nombre);
  return $nombre;
 }
}