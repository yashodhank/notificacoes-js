<?php
	require_once "../configuration.php";
	require("pdo.php");
	$db = Database::conectar($db_host,$db_username,$db_password,$db_name);
	$ql = $db->query("SELECT value FROM tblconfiguration WHERE setting='SystemURL' LIMIT 1;");
	$rl = $ql->fetch();
	if(isset($_GET['u']) && isset($_GET['e']))
	{
		echo "var md5cli = '".$_GET['u']."';";
?>
$(document).ready(function() {
	var icones = {ok:"<?=$rl['value']?>/notificacoes/ok.png", novo:"<?=$rl['value']?>/notificacoes/chat.png"};
	var carregado = false;
	function notificar(texto,icone,titulo,url) {
		var options = {
			body: texto,
			icon: icone
		}
		var n = new Notification(titulo,options);
		$("#audio-notificacao").remove();
		$("body").append('<audio autoplay id="audio-notificacao"><source src="<?=$rl['value']?>/notificacoes/notificacao.mp3" type="audio/mpeg"></audio>');
		setTimeout(n.close.bind(n), 5000);
		$('.bottom-left').notify({
			message: {text: texto},
			fadeOut: {enabled: false}
		}).show();
		if(url != null)
		{
			n.onclick = function(event) {
				event.preventDefault();
				window.location=url;
			}
		}
	}
	if (!("Notification" in window)) {
		alert("Esse navegador não suporta notificações");
	}
	else if (Notification.permission !== 'denied') {
		Notification.requestPermission(function(){carregado=true;});
	}
	setInterval(function(){
		if(carregado === true)
		{
			$.get("<?=$rl['value']?>notificacoes.php?u=<?=$_GET['u']?>&e=<?=$_GET['e']?>", function(data) {
				for (i = 0; i < data.length; i++) {
					notificar(data[i].texto, icones[data[i].icone], data[i].titulo, data[i].url);
				}
			});
		}
	},5000);
});
<?php
	}
?>