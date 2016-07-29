<?php
	require_once "configuration.php";
	header('Content-Type: application/json; charset=utf-8');
	require("notificacoes/pdo.php");
	if(isset($_GET['u']))
	{
		require_once("configuration.php");
		session_start();
		if(!isset($_SESSION['tckid']))
		{
			$_SESSION['tckid'] = array();
		}
		$db = Database::conectar($db_host,$db_username,$db_password,$db_name);
		$qc = $db->query("SELECT * FROM tblclients WHERE email = '".$_GET['e']."';");
		$rc = $qc->fetch();
		if(sha1(md5($rc['uuid']).$rc['password']) == $_GET['u'])
		{
			$resultado = array();
			if($qt = $db->query("SELECT * FROM tbltickets WHERE userid = '".$rc['id']."' AND status = 'Answered';"))
			{
				while($rt = $qt->fetch())
				{
					$ql = $db->query("SELECT value FROM tblconfiguration WHERE setting='SystemURL' LIMIT 1;");
					$rl = $ql->fetch();
					$urls = $rl['value'];
					if(isset($_SESSION['tckid'][$rt['id']]))
					{
						if($_SESSION['tckid'][$rt['id']] != $rt['lastreply'])
						{
							$resultado[count($resultado)] = array('texto' => 'O ticket '.$rt['title'].' foi respondido.', 'icone' => 'novo', 'titulo' => 'Nova resposta', 'url' => ''.$urls.'/viewticket.php?tid='.$rt['tid'].'&c='.$rt['c']);
							$_SESSION['tckid'][$rt['id']] = $rt['lastreply'];
						}
					}
					else {
						$resultado[0] = array('texto' => 'O ticket '.$rt['title'].' foi respondido.', 'icone' => 'novo', 'titulo' => 'Nova resposta', 'url' => ''.$urls.'?tid='.$rt['tid'].'&c='.$rt['c']);
						$_SESSION['tckid'][$rt['id']] = $rt['lastreply'];
					}
				}
			}
			if(isset($resultado[0]))
			{
				echo json_encode($resultado);
			}
		}
	}
?>