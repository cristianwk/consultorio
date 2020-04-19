<?php


/**
 * Created by PhpStorm.
 * User: cristian
 * Date: 03/03/16
 * Time: 12:35
 */
##
# Classe de encriptação de dados com chave
# Estas funções impossibilitam o acesso aos texto sem a chave
##

class Seguranca
{
	function __construct() {}
	##
	# Codificação simples
	##
	function encriptar($texto, $chave)
	{
		$texto = str_split($texto, 1);
		$final = NULL;

		foreach($texto as $char)
		{
			$final .= sprintf("%03x", ord($char) + $chave);
		}

		return $final;
	}

	##
	# Decodificação simples
	##
	function decriptar($texto, $chave)
	{
		$final = NULL;
		$texto = str_split($texto, 3);
		foreach($texto as $char)
		{
			$final .= chr(hexdec($char) - $chave);
		}

		return $final;
	}

	##
	# Encontrar uma chave de acordo com um texto
	##
	function chave($texto)
	{
		$texto = str_split(md5($texto), 1);
		$sinal = 0;
		$soma = 0;
		foreach($texto as $char)
		{
			if($sinal)
			{
				$soma -= ord($char);
				$sinal = 0;
			}
			else
			{
				$soma += ord($char);
				$sinal = 1;
			}
		}
		if($soma < 0)
			$soma *= -1;

		return $soma;
	}
}

# Função responsável por conexão de Banco de Dados
function conexao() {

 $host='br540.hostgator.com.br';
 $user='cons0645_drawk';
 $password='drawk';
 $db='cons0645_consultorio';

 //PHP 5.4 o earlier (DEPRECATED)
 //$con = mysqli_connect($host,$user,$password) or exit("Connection Error");
 //$connection = mysqli_select_db($db, $con);

 //PHP 5.5 (New method)
 $connection =  mysqli_connect($host,$user,$password,$db);

}

conexao();


# Função responsável por proteção contra SQL INJECTION
function anti_injection($sql) {
	// remove palavras que contenham sintaxe sql
	$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
	$sql = trim($sql);//limpa espaços vazio
	$sql = strip_tags($sql);//tira tags html e php
	$sql = addslashes($sql);//Adiciona barras invertidas a uma string
	return $sql;
}

#$sysconfig = mysql_fetch_array(mysql_query("SELECT * FROM sysconfig"));
?>