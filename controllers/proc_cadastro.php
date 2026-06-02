<?php 
$linha=$_POST['usuario']. ";" .password_hash($_POST['senha'], PASSWORD_DEFAULT). "\n";
file_put_contents('../data/usuarios.txt', $linha, FILE_APPEND);
header('Location: ../login.php');