<?php require 'config.php';$title='Carrito';$c=&$_SESSION['cart'];$c=$c??[];
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 if(isset($_POST['del']))unset($c[(int)$_POST['del']]);
 if(isset($_POST['pay'])){need('buyer');$ids=array_keys($c);
  if($ids){$in=implode(',',array_fill(0,count($ids),'?'));$s=$pdo->prepare("SELECT * FROM products WHERE id IN($in)");$s->execute($ids);$ps=$s->fetchAll();
   $card=preg_replace('/\D/','',$_POST['card']);$addr=trim($_POST['address']);
   if(strlen($card)<13||!$addr){flash('Revisa la dirección y el número de tarjeta.');header('Location: cart.php');exit;}
   $pdo->beginTransaction();try{$tot=0;foreach($ps as $p){$q=min($c[$p['id']],$p['stock']);$tot+=$q*$p['price'];}
    $pdo->prepare('INSERT INTO orders(user_id,total,address) VALUES(?,?,?)')->execute([user()['id'],$tot,$addr]);$oid=$pdo->lastInsertId();
    foreach($ps as $p){$q=min($c[$p['id']],$p['stock']);if($q<1)continue;
     $pdo->prepare('INSERT INTO order_items(order_id,product_id,store_id,qty,price) VALUES(?,?,?,?,?)')->execute([$oid,$p['id'],$p['store_id'],$q,$p['price']]);
     $pdo->prepare('UPDATE products SET stock=stock-? WHERE id=?')->execute([$q,$p['id']]);}
    $pdo->commit();$c=[];flash("Pedido #$oid confirmado. ¡Gracias por comprar a un artesano!");header('Location: pedidos.php');exit;}
   catch(Exception $x){$pdo->rollBack();flash('No se pudo procesar el pago.');}}}
 header('Location: cart.php');exit;}
$rows=[];$tot=0;if($c){$in=implode(',',array_fill(0,count($c),'?'));$s=$pdo->prepare("SELECT * FROM products WHERE id IN($in)");$s->execute(array_keys($c));$rows=$s->fetchAll();}
include 'header.php';?><h1>Tu carrito</h1>
<?php if(!$rows):?><p class="muted">Está vacío. <a href="index.php">Explora el catálogo</a>.</p><?php else:?><table><?php foreach($rows as $p):$sub=$p['price']*$c[$p['id']];$tot+=$sub;?><tr><td><?=e($p['name'])?></td><td>× <?=$c[$p['id']]?></td><td><?=money($sub)?></td><td><form method="post"><input type="hidden" name="t" value="<?=csrf()?>"><button name="del" value="<?=$p['id']?>" class="link">Quitar</button></form></td></tr><?php endforeach;?><tr><th colspan="2">Total</th><th colspan="2"><?=money($tot)?></th></tr></table>
<?php if(user()&&user()['role']==='buyer'):?><form method="post" class="card narrow"><input type="hidden" name="t" value="<?=csrf()?>"><h3>Pago</h3><label>Dirección de envío<input name="address" required></label><label>Número de tarjeta (simulado)<input name="card" placeholder="4111 1111 1111 1111" required></label><button name="pay" class="btn">Pagar <?=money($tot)?></button><p class="muted">Pago de demostración: no se cobra nada ni se guarda la tarjeta.</p></form>
<?php else:?><p><a class="btn" href="auth.php">Ingresa como comprador para pagar</a></p><?php endif;endif;include 'footer.php';?>
