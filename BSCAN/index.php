<?php
session_start();
include('./includes/conn.php');

if(!isset($_SESSION["access_granted"]))
{
	$_SESSION["access_granted"] = false;
	$conn->close();
	Header('Location: ./');
	die();
}

// REQUEST ACCESS TO SCANNER
if(isset($_POST['submitRequest']))
{
	if($_SESSION["access_granted"])
	{
		$getrequest = 'UPDATE `projaccounts` SET `request_scan`=1 WHERE username="'.$_SESSION["username"].'"';
		$submitscanrequest = $conn->query($getrequest);
		$conn->close();
		echo 'DISABLE YOUR NOREDIRECT!';
		Header('Location: ./');
		die();
	}
}
// REQUEST ACCESS TO SCANNER

if(isset($_POST['logout']))
{
	if($_SESSION['access_granted'])
	{
		session_destroy();
	}
	$conn->close();
	echo 'DISABLE YOUR NOREDIRECT!';
	Header('Location: ./');
	die();
}
//REGISTER SYSTEM

if(isset($_POST['goToRegister']))
{
	if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmpassword']))
	{
		$u = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']), ENT_QUOTES);
		$e = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']), ENT_QUOTES);
		$p = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']), ENT_QUOTES);
		$cp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['confirmpassword']), ENT_QUOTES);
		
		if(strlen($u) < 3)
		{
			$_SESSION["notification"] = '
			<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Nume de utilizator prea scurt!
			</div>
			';
		}
		elseif(!filter_var($e, FILTER_VALIDATE_EMAIL))
		{
			$_SESSION["notification"] = '
			<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Email invalid!
			</div>
			';
		}
		elseif(strlen($p) < 6)
		{
			$_SESSION["notification"] = '
			<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Parola prea scurta!
			</div>
			';
		}
		elseif($p != $cp)
		{
			$_SESSION["notification"] = '
			<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Parolele nu corespund!
			</div>
			';
		}
		else
		{
			$phash = hash("sha256", $p); 
			$newuser = 'INSERT INTO projaccounts (username, password, email, register_time, last_login) VALUES ("'.$u.'", "'.$phash.'" , "'.$e.'", CURRENT_DATE, CURRENT_DATE)';
			if($conn->query($newuser))
			{
				$_SESSION["notification"] = '
				<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-success alert-dismissable fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				Contul a fost inregistrat!
				</div>
				';
			}
			else
			{
				$_SESSION["notification"] = '
				<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-warning alert-dismissable fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				Cineva foloseste deja acest cont/email!
				</div>
				';
			}
		}
	}
	$conn->close();
	echo 'DISABLE YOUR NOREDIRECT!';
	Header('Location: ./');
	die();
}

//REGISTER SYSTEM

function checkNotification()
{
	if(isset($_SESSION["notification"]))
	{
		$notification = $_SESSION["notification"];
		if($notification != NULL)
		{
			echo $notification;
			$_SESSION["notification"] = NULL;
		}
	}
}

//LOGIN SYSTEM

checkNotification();

if(isset($_POST['goToLogin']))
{
	if(isset($_POST['username']) && isset($_POST['password']))
	{
		$u = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']), ENT_QUOTES);
		$p = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']), ENT_QUOTES);
		$phash = hash("sha256", $p);
		$checklogin = 'SELECT id, username, password FROM projaccounts WHERE username="'.$u.'" AND password="'.$phash.'"';
		$updatelastlogin = 'UPDATE projaccounts SET last_login=CURRENT_DATE WHERE username="'.$u.'" AND password="'.$phash.'"';
		$loggedin = $conn->query($updatelastlogin);
		$checkin = $conn->query($checklogin);
		if($checkin->num_rows > 0)
		{
			while($setdata = $checkin->fetch_assoc())
			{
				$_SESSION["id"] = $setdata["id"];
				$_SESSION["access_granted"] = true;
				//More things will be added soon;
			}
		}
		else
		{
			$_SESSION["notification"] = '
			<div style="position: absolute; bottom: 0px; left: 5px;" class="alert alert-danger alert-dismissable fade in">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			Numele de utilizator si parola nu corespund!
			</div>
			';
		}
	}
	$conn->close();
	echo 'DISABLE YOUR NOREDIRECT!';
	Header('Location: ./');
	die();
}

//LOGIN SYSTEM

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>B-SCAN v0.1</title>
		<!--<link rel="icon" type="image/png" href="https://i.imgur.com/y8UKL3G.png" />-->
		<!--EXTERNAL API-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Ubuntu:300|Roboto" rel="stylesheet">
		<!--EXTERNAL API-->
	</head>
	<body>
		<div align="center" id="main" class="changepic main jumbotron">
			<div id="shade" style="display: none; position: absolute; top: 0px; background-color: black; opacity: 0.6; width: 100%; height: 190px;"></div>
			<div style="position: relative;">
				<h1 id="title" style="font-size: 15px; margin-top: 0px;margin-bottom: 10px; color: white;">B-SCAN v0.1</h1>
				<p id="subtitle" style="display: none; margin-bottom: 0px; color: white;">Cloud Scanner</p>
			</div>
		</div>
		
		<!--SPACER-->
		 <div style="width: 100%; height: 35px;; position: relative; background-color:SeaGreen;"></div>
		<!--SPACER-->
		
			<div align="center" id="home"> <!--class="tab-pane fade in active"-->
			<?php
			if(isset($_SESSION['access_granted']))
			{
				$acc = $_SESSION['access_granted'];
				if(!$acc)
				{
			?>
				<div id="login-form">
					<h1>Login</h1>
					<form method="POST" action="./">
						<div class="col-sm-4"></div>
						<div class="col-sm-4">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="username" type="text" class="form-control" name="username" placeholder="Username">
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="password" type="password" class="form-control" name="password" placeholder="Password">
							</div>
							<br>
							<input class="custombtn btn btn-sm" type="submit" name="goToLogin" value="Login">
							<br></br>
							<a id="goregister" class="custombtn btn btn-sm" href="#">You don't have an account?</a>
						</div>
						<div class="col-sm-4"></div>
					</form>
				</div>
				
				<div style="display: none;" id="register-form">
					<h1>Register</h1>
					<div class="container">
						<form method="POST" action="./">
						<div class="col-sm-4"></div>
						<div class="col-sm-4">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="username" type="text" class="form-control" name="username" placeholder="Username">
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<input id="email" type="text" class="form-control" name="email" placeholder="Email">
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="password" type="password" class="form-control" name="password" placeholder="Password">
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="password" type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password">
							</div>
							<br>
							<input class="custombtn btn btn-sm" type="submit" name="goToRegister" value="Register">
							<br></br>
							<a id="gologin" class="custombtn btn btn-sm" href="#">You are already registered?</a>
							</div>
							<div class="col-sm-4"></div>
						</form>
					</div>
				</div>
				<?php
				}
				else
				{
					//UPDATE RESULTS
					$checklogin = 'SELECT id, username, password, register_time, last_login, request_scan, assigned_ip, assigned_port, assigned_delay, assigned_class FROM projaccounts WHERE id="'.$_SESSION["id"].'"';
					$checkin = $conn->query($checklogin);
					if($checkin->num_rows > 0)
					{
						while($setdata = $checkin->fetch_assoc())
						{
							$phash = hash("sha256", $setdata["password"]);
							$_SESSION["id"] = $setdata["id"];
							$_SESSION["username"] = $setdata["username"];
							$_SESSION["epassword"] = $phash;
							$_SESSION["reg_date"] = $setdata["register_time"];
							$_SESSION["log_date"] = $setdata["last_login"];
							$_SESSION["request_scan"] = $setdata["request_scan"];
							$_SESSION["assigned_ip"] = $setdata["assigned_ip"];
							$_SESSION["assigned_port"] = $setdata["assigned_port"];
							$_SESSION["assigned_delay"] = $setdata["assigned_delay"];
							$_SESSION["assigned_class"] = $setdata["assigned_class"];
							$_SESSION["access_granted"] = true;
							//More things will be added soon;
						}
					}
					//UPDATE RESULTS
					?>
					<h1>Salut, <?php echo $_SESSION['username']; ?></h1>
					<br>
					<p>Statistici:</p>
					<p>Data inregistrarii: <?php echo $_SESSION['reg_date']; ?></p>
					<p>Ultima logare: <?php echo $_SESSION['log_date']; ?></p>
					<p>Delay: <?php
					if(!empty($_SESSION['assigned_delay']))
					{
						$sec = $_SESSION['assigned_delay']/1000;
						if($sec == 0 || ($sec > 1 && $sec < 20))
						{	
							echo $sec.' secunde';
						}
						elseif($sec == 1)
						{
							echo 'o secunda';
						}
						else
						{
							echo $sec.' de secunde';
						}
					}
					else
					{
						echo (10000/1000)." secunde";
					}
					?></p>
					<?php
					if(!empty($_SESSION['assigned_class']))
					{
					?>
					<p>Clasa curenta: <?php echo $_SESSION['assigned_class']; ?></p>
					<?php
					}
					?>
					<br>
					<?php
					if(!$_SESSION["request_scan"])
					{
						?>
						<p>Se pare ca nu ai nici un scanner activ! Da click pe butonul de mai jos pentru a avea acces la un scanner.</p>
						<form method="POST" action="./">
						<input class="custombtn btn btn-sm" type="submit" name="submitRequest" value="Cere acces la scaner">
						</form>
						<br>
						<?php
					}
					else
					{
						if(empty($_SESSION['assigned_ip']))
						{
						?>
						<p>Cererea ta a fost inregistrata cu success! In cel mai scurt timp vei avea acces la unul din scannerele noastre. In caz contrar, cererea ta va fi stearsa si vei putea cere din nou alt scanner!</p>
						<?php
						}
						else
						{
							?>
							<div id="platform"><p>Se incarca scannerul...</p></div>
							<script>
							window.onload = $("#platform").load("live.php");
							function platform(){
							$("#platform").load("live.php");
							}
							setInterval(function(){platform()}, <?php if(!empty($_SESSION['assigned_delay'])) { echo $_SESSION['assigned_delay']; } else { echo 10000; } ?>);
							</script>
							<div style="display: none;" id="controale">
								<form method="POST" action="./">
									<div class="col-sm-12"></div>
									<div class="col-sm-4"></div>
									<div class="col-sm-4"><input id="scanClass" name="class" style="text-align: center;" maxlength="7" type="text" class="input-sm form-control" <?php if(!empty($_SESSION['assigned_class'])) { echo 'value="'.$_SESSION['assigned_class'].'"'; } ?> placeholder="123.123"></div>
									<div class="col-sm-4"></div>
									<div class="col-sm-12"><br>
										<div class="btn-group">
											<input id="scanStart" type="submit" name="scanAction" value="Start" class="btn btn-sm btn-success">
											<input id="scanStop" type="submit" name="scanAction" value="Stop" class="btn btn-sm btn-danger">
										</div>
									</div>
									<div class="col-sm-12"><br>
									<input id="vulnAction" type="submit" name="vulnAction" value="Sterge entitatile scanate" class="btn btn-sm btn-success">
									</div>
								</form>
								
								<div style="position: fixed; bottom: 0px; left: -1000px;" id="AJAXanswer" class="notification"></div>
							</div>
								<script>
								window.onload = toggleControls();
								function toggleControls()
								{
									if(document.getElementById("platform").innerHTML != "<p>Se incarca scannerul...</p>")
									{
										$('#controale').show();
									}
									else
									{
										$('#controale').hide();
									}
								}
								setInterval(function(){toggleControls()}, 1);
								
								var vuln = new XMLHttpRequest();
								var start = new XMLHttpRequest();
								var stop = new XMLHttpRequest();
								
								$("#scanStart").click(function(e) {
								e.preventDefault();
								var scanclass = document.getElementById("scanClass").value;
								start.onreadystatechange = function() {
									if (this.readyState == 4 && this.status == 200)
									{
									document.getElementById("AJAXanswer").innerHTML = this.responseText;
									}
								};
								
								start.open('POST', 'controls.php', true);
								start.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
								start.send('scanAction=Start&class=' + scanclass);
								$(".notification").animate({left: '-1000px'});
								$(".notification").animate({left: '0px'}).delay(3000).animate({left: '-1000px'});
								});
								
								
								
								$("#scanStop").click(function(e) {
								e.preventDefault();
								stop.onreadystatechange = function() {
									if (this.readyState == 4 && this.status == 200)
									{
									document.getElementById("AJAXanswer").innerHTML = this.responseText;
									}
								};
								
								stop.open('POST', 'controls.php', true);
								stop.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
								stop.send('scanAction=Stop');
								$(".notification").animate({left: '-1000px'});
								$(".notification").animate({left: '0px'}).delay(3000).animate({left: '-1000px'});
								});
								
								
								
								$("#vulnAction").click(function(e) {
								e.preventDefault();
								vuln.onreadystatechange = function() {
									if (this.readyState == 4 && this.status == 200)
									{
									document.getElementById("AJAXanswer").innerHTML = this.responseText;
									}
								};
								
								vuln.open('POST', 'controls.php', true);
								vuln.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
								vuln.send('vulnAction=');
								$(".notification").animate({left: '-1000px'});
								$(".notification").animate({left: '0px'}).delay(3000).animate({left: '-1000px'});
								});
								</script>
							<?php
						}
					}
					?>
					<form method="POST" action="./">
					<div class="col-sm-12">
					<br>
					<input class="custombtn btn btn-sm" type="submit" name="logout" value="Logout">
					</div>
					</form>
					<?php
				}
			}
				?>
				<div class="col-sm-12">
					<br>
						<button style="border-radius: 100%;" id="scrollButton" class="custombtn btn btn-sm glyphicon glyphicon-hand-up" onclick="goTop()"></button>
					<br>
				</div>
			</div>
	</body>
</html>

<!--CSS-->
<style>
.main {
	margin-bottom: 0px;
	background-color: RoyalBlue;
	background: url('https://i.imgur.com/azxQM9M.png') no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
#main {
display: none;
padding-bottom: 20px;
}
h1 {
	font-family: 'Ubuntu', sans-serif;
}
p, span {
	font-family: 'Roboto', sans-serif;
	font-size: 16px;
}
body {
	cursor: default;
	-moz-user-select: none;
	-webkit-user-select: none;
	-ms-user-select: none;
	user-select: none;
	-o-user-select: none;
}
a.mainbtn {
	width: 100%;
	border: 0px solid;
}
a.scrollmenu {
background-color: RoyalBlue;
border: 0px solid transparent;
}

a.scrollmenu:hover, a.scrollmenu:focus{
	background-color: RoyalBlue;
}
.notificare {
margin-bottom: 5px;
margin-left: 5px;
padding: 10px;
border-radius: 0px;
}
.custombtn, .custombtn:hover, .custombtn:focus, .custombtn:active  {
	background-color: SeaGreen;
	color: white;
	border-radius: 0px;
	border: 0px solid transparent;
	outline: none;
}
.btn:focus,.btn:active {
   outline: none !important;
}
a, a:active, a:focus{
	outline: none;
}
a.nolink, a.nolink:hover, a.nolink:focus {
outline: none;
text-decoration: none;
}
</style>
<!--CSS-->

<!--JS-->
<script>
$(document).ready(function(){
$('#main').slideDown('slow');
$('#shade').fadeIn(15000);
$('#home').slideDown(8000);
var title = $("#title");
var subtitle = $("#subtitle");
title.animate({fontSize: '63px'}, 4000);
subtitle.fadeIn(3000);
});

function goTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
window.onscroll = function() {getScroll()};
window.onload = function() {getScroll()};

function getScroll() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scrollButton").style.display = "block";
    } else {
        document.getElementById("scrollButton").style.display = "none";
    }
}
$(document).ready(function(){
	$(".notification" ).click(function() {
		$(".notification").animate({left: '-1000px'});
	});
});

$("#goregister").click(function(){
	$("#login-form").hide('slow');
	$("#register-form").slideDown('slow');
});

$("#gologin").click(function(){
	$("#login-form").slideDown('slow');
	$("#register-form").hide('slow');
});
</script>
<!--JS-->
<?php
$optimize = 'OPTIMIZE TABLE projaccounts';
$conn->query($optimize);
$conn->close();
?>
