<!doctype html>
<html lang="Hu">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Feladat kezelő') }}</title>
<style class="ticket-styles">
body {
    font-family: DejaVu Sans;
    font-size: 12px;
}
table {
    border-collapse: collapse;
    width: 720px;
}

table, th, td {
    border: 1px solid black;
    padding: 5px;
}
th {
    text-align: center;   
}
td {
    text-align: left;   
}
div {
    padding: 0px;
    margin: auto;
}
</style>
</head>
    
<body class="centered-content">
<?php
//    $megrendelo="BePro Kft";
//    $szall_cim="";
//    $kontakt="";
    $email="";        
//    $megjegyzes="";
//    $tipus="nagyméretű nyomtató";
//    $sn="";
    $garancialis="Garanciális";
//    $hiba="Nem működik";
?>

<table>
  <tr><td width="210px" height="60px"valign="center">Munkaszám: <?php echo $ticket_ID; ?><br>
    Dátum: <?php echo date('Y-m-d'); ?></td>
    <td scope="col" colspan="2"><div align="center">
        <img src='images/NP_logo_new.jpg'></div></td>
    <td width="210px" scope="col"><div>
    1132 Bp. Váci út 64/A<br>
    Tel: 350-6157<br>
    E-mail: info@negypolus.hu</div></td>    
  </tr>
  <tr>
    <th colspan="4" height= "40px" scope="col"><div align="center">
      <p><b style='mso-bidi-font-weight:normal'><span style='font-size:14.0pt;font-family:"Arial","sans-serif"'>
        <u>MUNKALAP</u></span></b></p>
    </div></th>
  </tr>
  <tr><td width="250px" valign="top" scope="col" colspan="2"><b>A megrendelő adatai</b><br>
<?php
echo " Név: $megrendelo <br>";
echo " Cím: $cim <br>";
echo " Telefon: $telefon"; 
?>
  </td>
  <td scope="col" colspan="2" valign="top"><div align="left"><b>A készülék adatai</b><br>
<?php
echo "$device_name <br>";
?>
  </div></td>
  </tr>
  <tr><td colspan="4" height="80px" valign="top" scope="col"><b>Hibajelenség:</b><br>
  <?php echo $hiba; ?>
  </td></tr>
  <tr><td colspan="4" height="60px" valign="top" scope="col"><b>Megjegyzés:</b><br>
  <?php echo $megjegyzes; ?>
  </td></tr>
  <tr><td colspan="2">Átvétel ideje: 
  <?php echo $datum; ?></td>
  <td colspan="2">Elszámolás:
  <?php echo $garancialis; ?></td> </tr>
</table>
<br>
<div height="130px" align="center">
A készüléket javításra átvettem: _____________________
</div>
<br>
<table cellpadding="0" cellspacing="0">
  <tr><td height="130px" valign="top" scope="col" colspan="3"><b>Elvégzett munka:</b><br>
<?php
	    $lista=explode("\n",$munka,12);
	    $sorok=count($lista);
	    for ($j=0; $j<$sorok; $j++){
        echo "$lista[$j]<br>";
      };
?>
  </td></tr>
  <tr><td height="130px" valign="top" scope="col" colspan="3"><b>Felhasznált anyagok:</b><br>
  </td></tr>
    <tr><td width="180px" height="30px" scope="col"><b>Munkaórák száma:</b>
        <br>munkaidőben: <?php echo $in_worktime; ?>
        <br>munkaidőn kívűl: <?php echo $after_worktime; ?></td>
        <td scope="col"><b>Munkadíj: </b>
                        <br><b>Anyagköltség: </b>
                        <br><b>Kiszállás: </b></td>
        <td scope="col"><b>Fizetendő: </b> </td></tr>
    <tr><td colspan="2"></td>
        <td>A munkát végezte:<br> <?php echo $done_by; ?> </td></tr>
</table>
<br> <br>   
A munkát átvettem: ________________________  

</body>
</html>