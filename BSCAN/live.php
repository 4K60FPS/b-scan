<?php
session_start();

function get_data($post) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://'.$_SESSION['assigned_ip'].'/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PORT, $_SESSION['assigned_port']);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if(isset($_SESSION['assigned_ip']) && isset($_SESSION['assigned_port']))
{
	$vuln = get_data("action=getVuln");
	$scan = get_data("action=getScanStatus");
	echo '<p>Status: '.$scan.'</p>';
	echo $vuln;
}
?>