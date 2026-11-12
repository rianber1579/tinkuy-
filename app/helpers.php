<?php
function db(){global $pdo;return $pdo;}
function url($r,$q=[]){return 'index.php?'.http_build_query(['r'=>$r]+$q);}
function go($r,$q=[]){header('Location: '.url($r,$q));exit;}
function view($v,$d=[]){extract($d);include __DIR__.'/Views/layout/header.php';include __DIR__."/Views/$v.php";include __DIR__.'/Views/layout/footer.php';}
