<?php require 'config.php';$id=(int)($_GET['id']??0);
$q=$pdo->prepare('SELECT p.*,s.name store,s.region,s.description sdesc,c.name cat FROM products p JOIN stores s ON s.id=p.store_id JOIN categories c ON c.id=p.category_id WHERE p.id=?');$q->execute([$id]);$p=$q->fetch();if(!$p){http_response_code(404);die('Producto no encontrado. <a href="index.php">Volver</a>');}
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 if(isset($_POST['add'])){$_SESSION['cart'][$id]=min($p['stock'],($_SESSION['cart'][$id]??0)+max(1,(int)$_POST['qty']));flash('Agregado al carrito.');header('Location: cart.php');exit;}
 if(isset($_POST['rating'])){need('buyer');$ok=$pdo->prepare('SELECT 1 FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.user_id=? AND oi.product_id=?');$ok->execute([user()['id'],$id]);
  if(!$ok->fetch())flash('Solo puedes valorar productos que compraste.');
  else{$pdo->prepare('REPLACE INTO reviews(product_id,user_id,rating,comment) VALUES(?,?,?,?)')->execute([$id,user()['id'],min(5,max(1,(int)$_POST['rating'])),substr(trim($_POST['comment']),0,400)]);flash('Gracias por tu valoración.');}
  header("Location: product.php?id=$id");exit;}}
$rv=$pdo->prepare('SELECT r.*,u.name FROM reviews r JOIN users u ON u.id=r.user_id WHERE product_id=? ORDER BY r.id DESC');$rv->execute([$id]);$rv=$rv->fetchAll();
$title=$p['name'];include 'header.php';?>
<section class="detail"><div><?=img($p['image'])?></div><div><h1><?=e($p['name'])?></h1><p class="price"><?=money($p['price'])?></p><p><?=nl2br(e($p['description']))?></p>
<p class="muted">Categoría: <?=e($p['cat'])?> · Quedan <?=$p['stock']?></p>
<form method="post"><input type="hidden" name="t" value="<?=csrf()?>"><label>Cantidad<input type="number" name="qty" value="1" min="1" max="<?=$p['stock']?>"></label><button name="add" class="btn">Agregar al carrito</button></form>
<div class="card"><h3><?=e($p['store'])?></h3><p class="muted"><?=e($p['region'])?></p><p><?=e($p['sdesc'])?></p></div></div></section>
<section><h2>Valoraciones</h2><?php foreach($rv as $r):?><div class="card"><strong><?=e($r['name'])?></strong> ★ <?=$r['rating']?><p><?=e($r['comment'])?></p></div><?php endforeach;if(!$rv):?><p class="muted">Aún no hay valoraciones.</p><?php endif;?>
<?php if(user()&&user()['role']==='buyer'):?><form method="post" class="card"><input type="hidden" name="t" value="<?=csrf()?>"><h3>Deja tu valoración</h3><label>Puntaje<select name="rating"><?php for($i=5;$i>=1;$i--)echo "<option>$i</option>";?></select></label><label>Comentario<textarea name="comment" maxlength="400"></textarea></label><button class="btn">Publicar valoración</button></form><?php endif;?></section>
<?php include 'footer.php';?>
