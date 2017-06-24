<?php
function getScanStatus()
{
	$scan1 = shell_exec('pgrep scaneste1');
	$scan2 = shell_exec('pgrep scaneste2');
	if($scan1 == 0 && $scan2 == 0)
	{
		return 'Offline';
	}
	elseif($scan1 > 0 && $scan2 == 0)
	{
		return 'Scanez dupa IP-uri...';
	}
	elseif($scan1 == 0 && $scan2 > 0)
	{
		return 'Scanez dupa vulnerabilitati...';
	}
}

function getVuln()
{
	$vuln = htmlspecialchars(shell_exec('cat confidential/BSSH2/vuln.txt'), ENT_QUOTES);
	if(empty($vuln))
	{
		return '<p>Nu am gasit nimic pana acum!</p>';
	}
	else
	{
		return '
		<div class="col-sm-4"></div>
		
		<div class="col-sm-4">
			<div class="form-group">
			<label for="rezultate">Rezultate:</label>
			<textarea readonly style="resize: none;" class="form-control" rows="10" id="rezultate">'.$vuln.'</textarea>
			</div>
		</div>
		
		<div class="col-sm-4"></div>
		';
	}
}

function flushVuln()
{
	shell_exec('sudo cp /dev/null confidential/BSSH2/vuln.txt');
	return '
	<div class="alert alert-success notificare">
	Vuln flushed
	</div>';
}

function startScan()
{
	if(getScanStatus() == 'Offline')
		{
			if(isset($_POST['classb']))
			{
				$class = htmlspecialchars($_POST['classb'], ENT_QUOTES);
				$ip = $class.'.0.0';
				if(filter_var($ip, FILTER_VALIDATE_IP))
				{
					if($class[0] == '0' || substr($class, 0, 3) == '127' || substr($class, 0, 3) == '192' || substr($class, 0, 3) == '255') //previne anumite scan-uri inutile
					{
						return '
						<div class="alert alert-danger notificare">
						Nu poti scana pe aceasta clasa de IP: '.$class.'
						</div>
						';
					}
					else
					{
						shell_exec('cd confidential/BSSH2 && sudo ./scan_user '.$class.' > /dev/null 2>/dev/null &');
						return '
						<div class="alert alert-success notificare">
						Scanul va porni in cateva momente!
						</div>
						';
					}
				}
				else
				{
					return '
					<div class="alert alert-danger notificare">
					Clasa introdusa nu este valida:'.$class.'
					</div>
					';
				}
			}
			else
			{
				return '
				<div class="alert alert-danger notificare">
				Clasa invalida!
				</div>
				';
			}
		}
		else
		{
			return '
			<div class="alert alert-danger notificare">
			Deja este un scan in desfasurare!
			</div>
			';
		}
}

function stopScan()
{
	if(getScanStatus() !== 'Offline')
	{
		shell_exec('sudo pkill scan_user');
		shell_exec('sudo pkill scaneste1');
		shell_exec('sudo pkill scaneste2');
		return '
		<div class="alert alert-success notificare">
		Scanner-ul se va opri in cateva momente!
		</div>
		';
	}
	else
	{
		return '
		<div class="alert alert-danger notificare">
		Scanner-ul nu este activ!
		</div>
		';
	}
}

if(isset($_POST['action']))
{
	$a = $_POST['action'];
	if($a == 'getVuln')
	{
		echo getVuln();
	}
	elseif($a == 'flushVuln')
	{
		echo flushVuln();
	}
	elseif($a == 'getScanStatus')
	{
		echo getScanStatus();
	}
	elseif($a == 'startScan')
	{
		echo startScan();
	}
	elseif($a == 'stopScan')
	{
		echo stopScan();
	}
	else
	{
		echo 'Actiune invalida!';
	}
}
else
{
	echo '
	<div class="alert alert-info notificare">
	B-SCAN API backend
	</div>
	';
}
?>