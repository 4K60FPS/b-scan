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

if(isset($_POST['scanAction']))
{
	$act = $_POST['scanAction'];
	if($act == 'Start')
	{
		if(isset($_POST['class']))
		{
			$class = $_POST['class'];
			echo get_data('action=startScan&classb='.$class);
		}
	}
	elseif($act == 'Stop')
	{
		echo get_data('action=stopScan');
	}
	else
	{
		echo 'Invalid action!';
	}
}
elseif(isset($_POST['vulnAction']))
{
	echo get_data('action=flushVuln');
}
else
{
	echo 'Cloud Scanner Controller';
}
?>