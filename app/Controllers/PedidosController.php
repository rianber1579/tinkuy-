<?php
class PedidosController{function index(){need('buyer');view('pedidos/index',['title'=>'Mis pedidos','r'=>Pedido::delUsuario(user()['id'])]);}}
