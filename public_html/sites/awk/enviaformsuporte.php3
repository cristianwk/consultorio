<?php

	$nome = $_POST['nome'];
	$telefone = $_POST['telefone'];
	$email = $_POST['email'];
	$empresa = $_POST['empresa'];			
	$data = $_POST['data'];	

	if ( empty($nome) )
	{
		echo "<script>alert('Voc� n�o digitou NOME no formul�rio'); history.go(-1);</script>";
		exit;
	}
	
	if ( empty($telefone) )
	{
		echo "<script>alert('Voc� n�o digitou TELEFONE no formul�rio'); history.go(-1);</script>";
		exit;
	}		
	
	if ( empty($email) )
	{
		echo "<script>alert('Voc� n�o digitou E-MAIL no formul�rio'); history.go(-1);</script>";
		exit;
	}

	if ( empty($info) )
	{
		echo "<script>alert('Voc� n�o digitou INFO no formul�rio'); history.go(-1);</script>";
		exit;
	}

	$to = "cristian@awktec.com";
		
	$headers = "From: $email \n";
	$headers .= "X-Mailer: Contatos para a AWK Tecnologia\n";

	$hoje = date("Y-m-d"); 
	$hora = date("H:i:s");

	$subject = "[$hoje] - [$hora] : Suporte T�cnico";

	$msg  = "[$hoje] - [$hora] : Suporte T�cnico";
	$msg .= $nome . "\n";
	$msg .= $email . "\n";
	$msg .= $telefone . "\n";	
	$msg .= $empresa . "\n";
	$msg .= $data . "\n";	
	$msg .= $info . "\n";	

	//mail($to, $subject, $msg, $headers);
	mail($to, $subject, $msg, $headers);
		
	mail($email, $subject, $msg, $headers);

	header("location:http://www.awktec.com");
	exit;

?> 

