<?php // Ejecutar UNA vez: http://localhost/tinkuy/instalar.php  (borra este archivo después)
$pdo=new PDO('mysql:host=localhost;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
foreach(array_filter(array_map('trim',explode(';',file_get_contents(__DIR__.'/database.sql')))) as $q)$pdo->exec($q);
$pdo->exec('USE tinkuy');
if($pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn()==0){
 foreach(['Textiles','Cerámica','Joyería','Madera y tallado','Cuero','Instrumentos','Decoración'] as $c)$pdo->prepare('INSERT INTO categories(name) VALUES(?)')->execute([$c]);
 $h=password_hash('123456',PASSWORD_DEFAULT);
 $u=$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)');
 $u->execute(['Rosa Quispe','vendedor@tinkuy.test',$h,'seller']);$s1=$pdo->lastInsertId();
 $u->execute(['Luis Huamán','vendedor2@tinkuy.test',$h,'seller']);$s2=$pdo->lastInsertId();
 $u->execute(['Ana Comprador','comprador@tinkuy.test',$h,'buyer']);
 $st=$pdo->prepare('INSERT INTO stores(user_id,name,region,description) VALUES(?,?,?,?)');
 $st->execute([$s1,'Telares de Rosa','Cusco','Tejemos con lana de alpaca teñida con tintes naturales.']);$a=$pdo->lastInsertId();
 $st->execute([$s2,'Cerámica Huamán','Huánuco','Cerámica modelada a mano con arcilla de la región.']);$b=$pdo->lastInsertId();
 $p=$pdo->prepare('INSERT INTO products(store_id,category_id,name,description,price,stock) VALUES(?,?,?,?,?,?)');
 $p->execute([$a,1,'Manta de alpaca con diseño andino','Manta tejida en telar de cintura, 1.5 x 2 m.',180,8]);
 $p->execute([$a,1,'Chalina de lana teñida con cochinilla','Color rojo natural, suave y abrigadora.',65,15]);
 $p->execute([$b,2,'Jarra de cerámica pintada','Pintada a mano, capacidad 1 litro.',48,12]);
 $p->execute([$b,7,'Cántaro decorativo','Pieza decorativa de 30 cm de alto.',90,5]);
}
if(PHP_SAPI==='cli'){echo "Tinkuy instalado.\nUsuarios demo (clave 123456): vendedor@tinkuy.test | comprador@tinkuy.test\nInicia el servidor con: php -S localhost:8000\n";return;}
echo '<h2>Tinkuy instalado ✔</h2><p>Base de datos lista. Usuarios demo (clave 123456): vendedor@tinkuy.test · comprador@tinkuy.test</p><p><a href="index.php">Ir a la tienda</a></p><p>Por seguridad, borra este archivo ahora.</p>';
