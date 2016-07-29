<?php
	if (!defined("WHMCS"))
	{
		die("Acesso restrito!");
	}
	function notificacaojs($vars) {
		global $smarty;
		$ca = new WHMCS_ClientArea();
		$idcliente = $ca->getUserID();
		$query = "SELECT * FROM tblclients WHERE id = '".$ca->getUserID()."'";
		$cliente = mysql_fetch_array(mysql_query($query));
		$tokens = sha1(md5($cliente['uuid']).$cliente['password']);
		$javascript = '<div class="notifications bottom-left"></div><script src="notificacoes/bootstrap-notify.js"></script><script src="notificacoes/notificacoes.php?u='.$tokens.'&e='.$cliente['email'].'"></script>';
		return $javascript;
	}
	function notificacaojshead($vars) {
		return '<link href="notificacoes/bootstrap-notify.css" rel="stylesheet">';
	}
	add_hook("ClientAreaFooterOutput",1,"notificacaojs");
	add_hook("ClientAreaHeaderOutput",1,"notificacaojshead");
?>
