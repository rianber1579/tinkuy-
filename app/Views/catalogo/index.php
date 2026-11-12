<section class="hero"><h1>Lo hecho a mano<br>llega a tu casa.</h1><p>Compra directo a tejedores, ceramistas y joyeros de todo el Perú.</p></section>
<form class="filters" method="get" action="index.php"><input type="hidden" name="q" value="<?=e($_GET['q']??'')?>">
<select name="c" onchange="this.form.submit()"><option value="">Todas las categorías</option><?php foreach($cats as $c):?><option value="<?=$c['id']?>" <?=($_GET['c']??'')==$c['id']?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select>
<select name="r" onchange="this.form.submit()"><option value="">Todas las regiones</option><?php foreach(REGIONES as $g):?><option <?=($_GET['r']??'')===$g?'selected':''?>><?=$g?></option><?php endforeach;?></select></form>
<?php if(!$rows):?><p class="muted">No hay productos con esos filtros. Prueba quitando alguno.</p><?php endif;?>
<div class="grid"><?php foreach($rows as $p):?><a class="prod" href="<?=url('catalogo/detalle',['id'=>$p['id']])?>"><?=img($p['image'])?><h3><?=e($p['name'])?></h3><p class="muted"><?=e($p['store'])?> · <?=e($p['region'])?></p><p><strong><?=money($p['price'])?></strong> <?=$p['avg']?'· ★ '.$p['avg']:''?></p></a><?php endforeach;?></div>
