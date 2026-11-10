<?php require 'config.php';$title='Catálogo';
$w=['p.stock>0'];$a=[];
if(!empty($_GET['c'])){$w[]='p.category_id=?';$a[]=(int)$_GET['c'];}
if(!empty($_GET['r'])){$w[]='s.region=?';$a[]=$_GET['r'];}
if(!empty($_GET['q'])){$w[]='(p.name LIKE ? OR p.description LIKE ?)';$a[]='%'.$_GET['q'].'%';$a[]='%'.$_GET['q'].'%';}
$st=$pdo->prepare('SELECT p.*,s.name store,s.region,(SELECT ROUND(AVG(rating),1) FROM reviews WHERE product_id=p.id) avg FROM products p JOIN stores s ON s.id=p.store_id WHERE '.implode(' AND ',$w).' ORDER BY p.id DESC');$st->execute($a);$rows=$st->fetchAll();
$cats=$pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();include 'header.php';?>
<section class="hero"><h1>Lo hecho a mano<br>llega a tu casa.</h1><p>Compra directo a tejedores, ceramistas y joyeros de todo el Perú.</p></section>
<form class="filters" method="get"><input type="hidden" name="q" value="<?=e($_GET['q']??'')?>">
<select name="c" onchange="this.form.submit()"><option value="">Todas las categorías</option><?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=($_GET['c']??'')==$c['id']?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select>
<select name="r" onchange="this.form.submit()"><option value="">Todas las regiones</option><?php foreach(REGIONES as $r):?><option <?=($_GET['r']??'')===$r?'selected':''?>><?=$r?></option><?php endforeach;?></select></form>
<?php if(!$rows):?><p class="muted">No hay productos con esos filtros. Prueba quitando alguno.</p><?php endif;?>
<div class="grid"><?php foreach($rows as $p):?><a class="prod" href="product.php?id=<?=$p['id']?>"><?=img($p['image'])?><h3><?=e($p['name'])?></h3><p class="muted"><?=e($p['store'])?> · <?=e($p['region'])?></p><p><strong><?=money($p['price'])?></strong> <?=$p['avg']?'· ★ '.$p['avg']:''?></p></a><?php endforeach;?></div>
<?php include 'footer.php';?>
