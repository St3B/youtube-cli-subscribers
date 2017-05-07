<?php
echo "inserisci la APIkey fornita da google (0 se vuoi usare quella salvata): ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);

$tmp_link = null;
$clear = "clear";
$API = file_get_contents('data/APIkey.dat');
$ID = null;
$filename = "data/APIkey.dat";
$username = null;
$tmp = null;
$subs = null;

if(trim($line) != '0')
{
    $API = trim($line);
    echo "uso la chiave : [$API] ...\n";
}

else 
{
	echo "uso la chiave [$API]...\n";
}

$handle = fopen ("php://stdin","r");
$line = fgets($handle);

if (file_exists($filename)) 
{
	//ora ho la chiave $API_fromfile = 'la mia chiave', quindi posso procedere,
	//mi manca il link
	shell_exec ( $clear );
	UsernameInput:
	echo "ora immetti lo username (immetti 0 se hai già l'ID): ";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	$username = trim($line);
	if (trim($line) != '0')
	{
		//ORA GETTO L'ID DEL CANALE DATO LO USERNAME
		$tmp_link = 'https://www.googleapis.com/youtube/v3/channels?key='.$API.'&forUsername='.$username.'&part=id';
		//echo $tmp_link;
		$tmp = file_get_contents($tmp_link);
		$File2 = fopen('data/ID_response.json', 'w+' ) or die("Unable to open file!");
		fwrite($File2, $tmp);
		$lines = file('data/ID_response.json');//file in to an array
		if ($lines[11] != '')
		{
			$ID = $lines[11];			
		}
		else 
		{
			echo "itente non trovato!\n";
			exit;
		}

		$ID = substr($ID, 10, -2);    //dal carattere 10 all'ultimo -2, dovrebbe andare
		echo "l'utente [$username] ha ID [$ID]...\n";
		//$tmp = file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=statistics&id=UCH8oKwITnew7W2zT-l5wPHQ&key=AIzaSyDn1ciIKusyxovIcVc4_yXddow0Hqoo1GQ');
		//echo $tmp;
	}

	else 
	{
		echo "\nimmetti l'ID: ";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$ID = trim($line);
		//processo per trovare lo username dell'utente
	}
}

else
{
	echo "--- PROCESSO DI CREAZIONE DEL FILE API ---\n";
	if(trim($line) != 'n' || trim($line) != 'no')
	{
		$File = fopen($filename, 'w+' ) or die("Unable to open file!");
		echo "FILE APERTO...\n";
		fwrite($File, $API);
		echo "FILE SCRITTO.\n\n";
		goto UsernameInput;
	}
}
//ora prendo l'ID e restituisco gli iscritti, pronti?

$tmp_link = 'https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$ID.'&key='.$API.'';
//echo $tmp_link;
$tmp = file_get_contents($tmp_link);
$File3 = fopen('data/subs_response.json', 'w+' ) or die("Unable to open file!");
fwrite($File3, $tmp);
$lines = file('data/subs_response.json');//file in to an array
$subs = $lines[15];
//echo $subs;
$subs = substr($subs, 24, -3);    //dal carattere 10 all'ultimo -2, dovrebbe andare

//TEST DEL NUOVO MODO DI GETTARE L'ID ECC
$str = file_get_contents('data/subs_response.json');
$json = json_decode($str, true);

$subznew = isset($json['items']['subscriberCount']) ? $json['items']['message'] : "";
echo $subznew;
echo $subs;


?>
