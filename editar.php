<?php

require __DIR__ . '/vendor/autoload.php';
define('TITLE', 'Editar vaga');


use \App\Entidade\Vaga;

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
    header('location: index.php?status="error"');
    exit;
}

//Consulta vaga
$obVaga = Vaga::getVaga($_GET['id']);

//VALIDA A VAGA
if(!$obVaga instanceof Vaga){
    header('location: index.php?status=error');
    exit;
}

if (isset($_POST['titulo'], $_POST['descricao'])) {
    
    $obVaga->titulo = $_POST['titulo'];
    $obVaga->descricao = $_POST['descricao'];
    $obVaga->ativo = $_POST['ativo'];
    $obVaga->atualizar();

    header('location: index.php?status=success');
    exit;
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/formulario.php';
include __DIR__ . '/includes/footer.php';
