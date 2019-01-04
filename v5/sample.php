<?php
include "atable.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>aTable</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width"/>
<link href="dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<script src="dist/js/jquery.1.12.4.min.js" type="text/javascript"></script>
<script src="dist/js/bootstrap.min.js"></script>
<?php atable_init();?>
</head>
<body>
<?php
$year=date('Y');
if(isset($_POST['year'])){
	$year=$_POST['year'];
}
if(!isset($_POST['fromatable'])){
echo '<div style="display:block;clear:both;">
<form method="post" class="form-inline">
  <select name="year" class="form-control">';
	for($th=date('Y');$th>2000;$th--){
		echo '<option value="'.$th.'" '.($th==$year?'selected':'').'>'.$th.'</option>';
	}
echo '</select>
		<input type="submit" name="toatable" value="Submit" class="btn btn-default"/></form></div><br><br>';
}
require "database_connection.php";
// ======================================================================================
// creating ajax atable
$atable = new Atable();
$atable->limit = 5;
$atable->caption = "TABLE CAPTION";
$atable->query = "SELECT tbl_field1, tbl_field2, tbl_field3, tbl_field4, tbl_field5, year FROM data_table";
$atable->where = "year='$year'";
$atable->col = '["tbl_field1", "tbl_field2", "tbl_field3", "tbl_field4", "$param1;", "$param2;"]';
$atable->colv = '[
  				[["COLSPAN HEADER","AC","col3"], ["ROWSPAN HEADER","AC","row2","w10px"], ["ROWSPAN HEADER","row2"], ["ROWSPAN HEADER","row2"]],
  				[["HEADER","AR","w10px"], ["HEADER","AC"], "HEADER"]
  			]';
$atable->colalign = '["R","C",""]';
$atable->colsize = '["110px", "10px", "20px"]';
//$atable->showsql = TRUE;
//$atable->database = 'pgsql';
$atable->param = '$param1="From field 5: ".$row->tbl_field5;
									extract(params($row));';
$atable->style = 'table table-hover table-striped table-bordered';
$atable->colnumber = FALSE;
//$atbb->showall=TRUE;
//$atbb->loadmore=FALSE;
//$atable->reload=TRUE;
//$atable->datainfo=FALSE;
//$atable->paging=FALSE;
//$atable->debug=FALSE;
$atable->collist=TRUE;
$atable->xls=TRUE;

//$atable->add=TRUE;
//$atable->edit=TRUE;
//$atable->delete=TRUE;

echo $atable->load();

function params($row){
	$data['param2']='<a href="#" class="btn btn-default" onclick="return confirm(\'Click!\');">Clikc '.$row->year.'</a>';
	return $data;
}
?>
<script>
//skip to page
//atable_topage(0,11);

//get page
//atable_getpage(0);

//push search
//atable_find(0,"rachmadany");

//hide column
//atable_colhide(0,[0,1]);
</script>
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
<button type="button" onclick="atable_toxls('tablenew');" class="btn btn-default">Create xls</button>
<!--
<button type="button" onclick="atable_reload(0);" class="btn btn-default">Reload</button>
-->
</body>
</html>
