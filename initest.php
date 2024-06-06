<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

#print_r($_POST);

$funktionINI   = ''; 
$grundINI      = '';
$entlastungINI = '';

$sizeofini           = 0;
$sizeoffunktionINI   = 0; 
$sizeofgrundINI      = 0;
$sizeofentlastungINI = 0;

$db = new SQLite3('funktionsentlastung.sqlite3');


if ( isset( $_POST[ 'ini' ] ) )
{ $csvData =  $_POST[ 'ini' ];
  $lines = explode(PHP_EOL, $csvData);
  $array = array();
  foreach ($lines as $line)
  { $line    = trim( $line);
    if($line != '')
      $array[] = str_getcsv($line);
  }
  
  foreach( $array as $ark => $arv )  ## Leerzeichen entfernen
  { foreach( $arv as $ak => $av )
  { $a[$ark][$ak] = trim($av);
  }
  }
  
  
  
  if ( isset( $_POST[ 'typ' ] ) )
{ print_r( $_POST[ 'typ' ] );
  if( $_POST[ 'typ' ] == 'funktion' )
  {   $ini = "";
    foreach ( $a as $ar )
    { #$ini .= $ar[0] .' , '. $ar[1] ."\r\n";
      $SQL = 'UPDATE funktionsGruppe SET funktionsGruppe = \''.$ar[1] .'\' WHERE ID = '.$ar[0] .' ';
      $db->exec($SQL);
      echo "<br>".$SQL;
    }
  }
}





$result = $db->query("SELECT * FROM funktionsGruppe"   ); while( $data = $result -> fetchArray() ) { $sizeoffunktionINI++   ; $funktionINI   .= $data[ 'ID'                ] . ' , ' .$data[ 'funktionsGruppe' ] .                                                  "\r\n"; }
$result = $db->query("SELECT * FROM grund"             ); while( $data = $result -> fetchArray() ) { $sizeofgrundINI++      ; $grundINI      .= $data[ 'ID'                ] . ' , ' .$data[ 'abk'             ] . ' , ' .$data[ 'grund' ].                         "\r\n"; }
$result = $db->query("SELECT * FROM entlastung"        ); while( $data = $result -> fetchArray() ) { $sizeofentlastungINI++ ; $entlastungINI .= $data[ 'funktionsGruppeID' ] . ' , ' .$data[ 'abt'             ] . ' , ' .$data[ 'grund' ]. ' , ' . $data[ 'LVL' ] ."\r\n"; }

$ini_array = parse_ini_file( "funktionsentlastung.ini", TRUE );
foreach ( $ini_array as $ia => $i )
{ foreach ( $i as $ik => $iv )
  { $v = explode( ",",$iv );
    $f[ 'grund'     ] = $v[ 0 ];   
    $f[ 'LVS'       ] = $v[ 1 ];  
    $fe[ $ia ][ $ik ] = $f ;
	$sizeofini++;
  }
}

$_SESSION[ 'FE' ][ 'funktion'            ] = $funktionINI;
$_SESSION[ 'FE' ][ 'grund'               ] = $grundINI;
$_SESSION[ 'FE' ][ 'entlastung'          ] = $entlastungINI;
$_SESSION[ 'FE' ][ 'funktionsentlastung' ] = $fe;




echo "<pre>";
print_r( $ini);
print_r($a);
echo "</pre>";
# file_put_contents ( "funktionsentlastung.ini", $_POST[ 'ini' ] );	
}

echo "<pre>";
#print_r($_SESSION);
echo "</pre>";

 $ini = file_get_contents("funktionsentlastung.ini");
?>

<html>
<head>
<style>
h1 {color:blue; font-size:14px;}
form.left { float: left;   margin-right: 10px; }
</style>
</head>
<body>

<span style="float:left; border:1px solid black; padding:20px">
<form class="left" action="initest.php" method="POST" >
<textarea name="ini" rows="<?php echo $sizeofini; ?>" cols="60" > <?php echo $ini; ?></textarea>
<input type="hidden" ID='typ' name='typ' value='ini'></input>
<br><button type="submit" >Speichern 1</button></form>
</span>

<span style="float:left; border:1px solid black; padding:20px">
<form class="left" action="initest.php" method="POST" >
<textarea name="ini" rows="<?php echo $sizeoffunktionINI; ?>" cols="60" ><?php echo $funktionINI; ?></textarea>
<br><button type="submit" >Speichern 2</button><input type="hidden" ID='typ' name='typ' value='funktion'></input>
</form>
</span>

<span style="float:left; border:1px solid black; padding:20px">
<form  class="left"  action="initest.php" method="POST" >
<textarea name="ini" rows="<?php echo $sizeofgrundINI; ?>" cols="60" ><?php echo $grundINI; ?></textarea>
<br><button type="submit" >Speichern3 </button>
<input type="hidden" ID='typ' name='typ' value='grund'></input>
</form>
</span>

<span style="float:left; border:1px solid black; padding:20px">
<form class="left" action="initest.php" method="POST" >
<textarea name="ini" rows="<?php echo $sizeofentlastungINI; ?>" cols="60" ><?php echo $entlastungINI;?></textarea>
<br><button type="submit" >Speichern 4 </button>
<input type="hidden" ID='typ' name='typ' value='entlastung'></input>
</form>
</span>

<body>
</html>
