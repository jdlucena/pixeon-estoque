<?php

// dados de acesso ao banco
require_once('conn/db.php');

// classe de conexao e consulta ao banco
require_once('classes/Conexao.php');

// classe de consulta ao materiais
require_once('classes/Consulta.php');

// instanciando classe
$consulta = new Consulta();

// verifica se a consulta retorna algum resultado
if ($consulta->consultarAF()):
	require_once ('views/consulta-af.php');
endif;