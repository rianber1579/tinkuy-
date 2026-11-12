<?php // Controlador frontal: index.php?r=controlador/accion
require __DIR__.'/config.php';require __DIR__.'/app/helpers.php';
foreach(glob(__DIR__.'/app/Models/*.php') as $m)require $m;
[$c,$a]=array_pad(explode('/',$_GET['r']??'catalogo/index'),2,'index');
$clase=ucfirst(preg_replace('/\W/','',$c)).'Controller';$a=preg_replace('/\W/','',$a);$f=__DIR__."/app/Controllers/$clase.php";
if(!is_file($f)){http_response_code(404);exit('Página no encontrada');}
require $f;$ctrl=new $clase();
if(!method_exists($ctrl,$a)){http_response_code(404);exit('Página no encontrada');}
$ctrl->$a();
