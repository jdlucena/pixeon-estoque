<?php

/*
 * configuração do banco de dados
 * local onde estão salvas as contantes
 * 
 * para mais informações
 * @see http://stackoverflow.com/q/2447791/1114320
 * 
 * DB_HOST: local do banco de dados
 * DB_NAME: nome do banco de dados
 * DB_USER: usuário com direitos para SELECT, UPDATE, DELETE and INSERT. Criar usuário, não colocar root
 * DB_PASS: senha do usuário
 * 
*/
define("DB_HOST", "");
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASS", "");

/*
 * endereço padrão para consulta de af
 * 
*/
define('ENDERECO', $_SERVER['REQUEST_SCHEME'].':'.$_SERVER['REQUEST_URI']);
