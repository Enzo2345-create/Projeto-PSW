<?php session_start(); 
    if(!isset($_SESSION['usuario'])) header('Location: login.php');?>
<?php include 'includes/header.php';?>
<h2> Painel </h2>
<a href= "dreamteam.php"> Dream Team </a>
<a href="votacao.php"> Votação </a>
<?php include 'includes/footer.php'; ?>