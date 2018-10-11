<?php
include "atable.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>aTable</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width"/>
<link href="../../dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="../../dist/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<?php atable_init();?>
</head>
<body>
<?php
$tahun=date('Y');
if(isset($_POST['tahun'])){
	$tahun=$_POST['tahun'];
}
if(!isset($_POST['fromatable'])){
echo '<div style="display:block;clear:both;">
<form method="post" class="form-inline">
  <select name="tahun" class="form-control">';
	for($th=date('Y');$th>2000;$th--){
		echo '<option value="'.$th.'" '.($th==$tahun?'selected':'').'>'.$th.'</option>';
	}
echo '</select>
		<input type="submit" name="toatable" value="Lihat" class="btn btn-default"/></form></div><br><br>';
}
require "../../koneksi.php";
// ======================================================================================
// creating ajax atable
$atable = new Atable();
$atable->limit = 5;
$atable->caption = "Data Mahasiswa";
//$atable->databases = 'pgsql';
$atable->query = "SELECT nim, no_kwitansi, nama, kelamin, tahun_masuk, jurusan FROM mahasiswa";
$atable->where = "tahun_masuk='$tahun'";
$atable->col = '["nim", "no_kwitansi", "nama", "kelamin", "$jurusan;", "$btn;"]';
$atable->colv = '[
  				[["NIM NAMA","AC","col3"], ["KELAMIN","AC","row2","w10px"], ["JURUSAN","row2"], ["BTN","row2"]],
  				[["NIM","AR","w10px"], ["No Kwitansi","AC"], "Nama"]
  			]';
$atable->colalign = '["R","C",""]';
$atable->colsize = '["110px", "10px", "20px"]';
//$atable->showsql = TRUE;
//$atable->database = 'pgsql';
$atable->param = '$jurusan="Prodi: ".$row["jurusan"];
                  $btn="<a href=\"#\" class=\"btn btn-default\" onclick=\'return confirm(\"Klik!\");\'>Klik</a>";';
$atable->style = 'table table-hover table-striped table-bordered';
$atable->colnumber = FALSE;
//$atable->reload=TRUE;
//$atable->datainfo=FALSE;
//$atable->paging=FALSE;
$atable->collist=TRUE;
$atable->xls=TRUE;

echo $atable->load();
?>
<br>

<table border="1" id="tablenew" width="100%">
	<tr>
		<td>1</td>
		<td>2</td>
		<td>3</td>
	</tr>
	<tr>
		<td>1</td>
		<td>2</td>
		<td>3</td>
	</tr>
</table>
<button type="button" onclick="atable_toexcel('dtblatable0');" class="btn btn-default">xls</button>
<!--
<button type="button" onclick="atable_reload(0);" class="btn btn-default">Reload</button>
-->
</body>
</html>
