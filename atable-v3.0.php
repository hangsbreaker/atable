<!-- Atable Copyright @ 2018 Rachmadany -->
<script>
var xhr;
var thepage="";
var datapost={};
var sortby=[];
var ascdsc=[];
(function($) {
	$(window).load(function() {});
	$(document).ready(function(e) {
		var atable = document.querySelectorAll('.dtatable');
		var forEach = [].forEach;
		forEach.call(atable, function (el, i) {
			atable[i].insertAdjacentHTML('beforeBegin','<div class="col-xs-2 findfield" style="margin-bottom: 10px;padding:0px 5px;min-width:200px;"><div class="input-group"><input type="text" class="txtcari form-control" name="cari" placeholder="Find" id="txtcari-'+i+'"><span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span></div></div>');
			atable[i].insertAdjacentHTML('afterEnd','');
		});


		$('.halaman').live("click", function(){
			var vid = this.id.split('-');
			document.getElementById("atablepreloader"+vid[1]).style.display="block";

			var tbpage = Object.assign({}, datapost);
			tbpage.h=vid[0];
			tbpage['atabledata'+vid[1]]=true;
			tbpage['sortby']=sortby[vid[1]];
			//alert(tbpage.toSource());
			$.post(thepage, tbpage ,function(data) {
				document.getElementById("atablepreloader"+vid[1]).style.display="none";
				var atableno=[];
				var htmldata = "<div>"+rbline(data)+"</div>";
				$(htmldata).find(".dtatable").each(function(i, obj){
					atableno[i]=this.innerHTML;
				});

				forEach.call(atable, function (el, i) {
					if(i==vid[1]){
						atable[i].innerHTML=atableno[i];
					}
				});
			});
		});

		$('.showall').live("click", function(){
			var vid = this.id.split('-');
			document.getElementById("atablepreloader"+vid[1]).style.display="block";

			var tbpage = Object.assign({}, datapost);
			tbpage.showall=true;
			tbpage['atabledata'+vid[1]]=true;
			tbpage['sortby']=sortby[vid[1]];
			//alert(tbpage.toSource());
			$.post(thepage, tbpage ,function(data) {
				document.getElementById("atablepreloader"+vid[1]).style.display="none";
				var atableno=[];
				var htmldata = "<div>"+rbline(data)+"</div>";
				$(htmldata).find(".dtatable").each(function(i, obj){
					atableno[i]=this.innerHTML;
				});

				forEach.call(atable, function (el, i) {
					if(i==vid[1]){
						atable[i].innerHTML=atableno[i];
					}
				});
				document.getElementById("showles-"+vid[1]).style.display="inline-block";
				document.getElementById("showall-"+vid[1]).style.display="none";
			});
		});

		$('.showles').live("click", function(){
			var vid = this.id.split('-');
			document.getElementById("atablepreloader"+vid[1]).style.display="block";

			var tbpage = Object.assign({}, datapost);
			tbpage['atabledata'+vid[1]]=true;
			tbpage['sortby']=sortby[vid[1]];
			//alert(tbpage.toSource());
			$.post(thepage, tbpage ,function(data) {
				document.getElementById("atablepreloader"+vid[1]).style.display="none";
				var atableno=[];
				var htmldata = "<div>"+rbline(data)+"</div>";
				$(htmldata).find(".dtatable").each(function(i, obj){
					atableno[i]=this.innerHTML;
				});

				forEach.call(atable, function (el, i) {
					if(i==vid[1]){
						atable[i].innerHTML=atableno[i];
					}
				});
				document.getElementById("showles-"+vid[1]).style.display="none";
				document.getElementById("showall-"+vid[1]).style.display="inline-block";
			});
		});

		$(".txtcari").keyup(function(event){
			//if(event.which == 13){
				xhr.abort();
				var vid = this.id.split('-');

				var v_afind = $('#txtcari-'+vid[1]).val();
				document.getElementById("atablepreloader"+vid[1]).style.display="block";
				document.getElementById("showles-"+vid[1]).style.display="none";
				document.getElementById("showall-"+vid[1]).style.display="inline-block";

				var tbpage = Object.assign({}, datapost);
				tbpage['atabledata'+vid[1]]=true;
				tbpage.afind=v_afind;
				tbpage['sortby']=sortby[vid[1]];
				
				xhr = $.ajax({
					type: "POST",
					url: thepage,
					data: tbpage,
					success: function(data){
						document.getElementById("atablepreloader"+vid[1]).style.display="none";
						var atableno=[];
						var htmldata = "<div>"+rbline(data)+"</div>";
						$(htmldata).find(".dtatable").each(function(i, obj){
							atableno[i]=this.innerHTML;
						});

						forEach.call(atable, function (el, i) {
							if(i==vid[1]){
								atable[i].innerHTML=atableno[i];
							}
						});
					}
				});
			//}
		});

		$('.sortby').live("click", function(){
			var vid = this.id.split('-');
			document.getElementById("atablepreloader"+vid[1]).style.display="block";
			if(ascdsc[vid[1]]==''){
				sortby[vid[1]] = vid[2]+' ASC';
				ascdsc[vid[1]]='ASC';
			}else if(ascdsc[vid[1]]=='ASC'){
				sortby[vid[1]] = vid[2]+' DESC';
				ascdsc[vid[1]]='DESC';
			}else{
				sortby[vid[1]] = vid[2]+' ASC';
				ascdsc[vid[1]]='ASC';
			}

			var tbpage = Object.assign({}, datapost);
			tbpage['atabledata'+vid[1]]=true;
			tbpage['sortby']=sortby[vid[1]];
			//alert(tbpage.toSource());
			$.post(thepage, tbpage ,function(data) {
				document.getElementById("atablepreloader"+vid[1]).style.display="none";
				var atableno=[];
				var htmldata = "<div>"+rbline(data)+"</div>";
				$(htmldata).find(".dtatable").each(function(i, obj){
					atableno[i]=this.innerHTML;
				});

				forEach.call(atable, function (el, i) {
					if(i==vid[1]){
						atable[i].innerHTML=atableno[i];
					}
				});
				document.getElementById("showles-"+vid[1]).style.display="none";
				document.getElementById("showall-"+vid[1]).style.display="inline-block";
			});
		});

	});
}) (jQuery);

//var html= '<div><div id="data" class="atables">val</div></div>';
//alert($(html).find('.atables').html());

function load_atable(curpage,post){
	thepage = curpage;
	datapost=JSON.parse(post);
	var loadtable = Object.assign({}, datapost);
	var atable = document.querySelectorAll('.dtatable');
	var forEach = [].forEach;
	forEach.call(atable, function (el, i) {
		sortby[i]='';
		ascdsc[i]='';
		loadtable['atabledata'+i]=true;
	});

	loadtable.fromatable=true;
				
	xhr = $.ajax({
		type: "POST",
		url: thepage,
		data: loadtable,
		success: function(data){
			var atableno=[];
			var htmldata = "<div>"+rbline(data)+"</div>";
			$(htmldata).find(".dtatable").each(function(i, obj){
				 atableno[i]=this.innerHTML;
			});

			var atable = document.querySelectorAll('.dtatable');
			var forEach = [].forEach;
			forEach.call(atable, function (el, i) {
				atable[i].innerHTML=atableno[i];
			});
		}
	});
}

function rbline(str){
    var text=str;
    text = text.replace(/(\r\n|\n|\r)/gm," ");
    text = text.replace(/\s{2,}/g, ' ');
    return text;
}
</script>

<style>
.atable{display:block;clear:both;}
.atable .dtatable .table{margin-bottom:0px;}
.atable .atablepreloader{
	display:none;
	position: absolute;
	width: 200px;
	margin-top: 120px;
	margin-left: -100px;
	left: 50%;
	background: #337AB7;
	color:#ffffff;
	padding: 15px;
	text-align: center;
	font-weight: bold;
	border: 3px solid #337AB7;
	border-radius: 3px;
	z-index:9;
}
.atable table tr td:first-child{
    width:1%;
    /*white-space:nowrap;*/
}
.atable .paggingfield{
	margin:0px 5px;
}
.atable .datainfo{
	right:0px;margin:20px 5px;position:absolute;padding:10px 5px;
}
.atable .warningdb{
	position:absolute;
	width:100%;
	padding:25px;
	font-size:18px;
	font-weight:bold;
	text-align:center;
	background:#eee;
}
.atable .paggingfield, .atable .findfield{
	float:right;
}
.atable .datainfo{
	left:0px;
}
</style>
<?php
// == Atable Function ===================================================================================================================
$http_s = isset($_SERVER['HTTPS'])?"https://":"http://";
$this_page = $http_s.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// Get what database connection use
$linkDB = "";
if(isset($_POST['databases'])){
	$linkDB = $_POST['databases'];
}
foreach(array_reverse(get_defined_vars()) as $variable){
	if(is_resource($variable) && get_resource_type($variable)=='mysql link'){
		$linkDB = "mysql";
		$dbcon=$variable;
		break;
	}else if(is_object($variable)  && get_class($variable)=='mysqli'){
		$linkDB = "mysqli";
		$dbcon=$variable;
		break;
	}else if(is_resource($variable) && get_resource_type($variable)=='pgsql link'){
		$linkDB = "pgsql";
		$dbcon=$variable;
		break;
	}
}
echo "<script>$(document).ready(function(e) {load_atable('".$this_page."','".json_encode($_POST)."');});</script>";
// === end database defined

$atablenum=0;
function the_atable($atable){
// ==== param init
$limit = !empty($atable['limit'])?$atable['limit']:10;
$limitfind = !empty($atable['limitfind'])?$atable['limitfind']:300;
$orderby = !empty($atable['orderby'])?$atable['orderby']:'';
$groupby = !empty($atable['groupby'])?$atable['groupby']:'';
$where = !empty($atable['where'])?$atable['where']:'';
$qrytable = $atable['query'];
$atablecol = $atable['col'];
$atablecolv = $atable['colv'];
$param = !empty($atable['param'])?$atable['param']:'';
$colsize = !empty($atable['colsize'])?$atable['colsize']:'';
$tdalign = !empty($atable['tdalign'])?$atable['tdalign']:'';
$showsql = !empty($atable['showsql'])?$atable['showsql']:'';
$style = !empty($atable['style'])?$atable['style']:'table table-bordered table-hover';
$sortpost="";
$sqlerror = FALSE;
if(isset($_POST['sortby'])){
	if($_POST['sortby']!=""){
		$orderby=$_POST['sortby'];
		$sortpost=$_POST['sortby'];
	}
}
$getcoltable=preg_replace("/ as [\s\S]+? /"," ",preg_replace("/ as [\s\S]+?,/",",",GetBetween($qrytable,"select","from")));
// ===============================

$theatable= '<div class="atable">'.($GLOBALS['linkDB'] == ''?'<div class="warningdb">Atable Unkown Database connection.</div>':'').'<div class="atablepreloader" id="atablepreloader'.$GLOBALS['atablenum'].'">Loading ....</div>
		<div class="dtatable" id="dtatable'.$GLOBALS['atablenum'].'">';
if(isset($_POST['atabledata'.$GLOBALS['atablenum']])){
	if(empty($limit)){$limit=10;}
	if(!empty($orderby)){$orderby='ORDER BY '.$orderby;}
	if(!empty($groupby)){$groupby='GROUP BY '.$groupby;}else{if($getcoltable!=' * '){$groupby='GROUP BY '.$getcoltable;}}
	if(!empty($where)){$where='HAVING '.$where;}
	$theatable.= '<table class="'.$style.'">
				<thead>
					<th>No</th>';
				$sortpost = explode(" ",$sortpost);
				foreach($atablecolv as $key=>$acolv){
					$theatable.= '<th '.(isset($colsize[$key])?'width="'.$colsize[$key].'"':'').' '.(isset($tdalign)?'style="text-align:'.($tdalign[$key]=='R'?'right':'left').';"':'').'>';
							$nmcol= str_replace('$','',str_replace(';','',$atablecol[$key]));
							$existcol= GetBetween($qrytable,"select","from");
							if(strpos($existcol,$nmcol)!==false){
								$bysort = $nmcol;
							}else{
								$bysort = $atablecol[$key];
							}
							if($sortpost[0]==$bysort){
								if($sortpost[1]=='ASC'){
									$iconsort = '<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>';
								}else{
									$iconsort = '<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>';
								}
							}else{
								$iconsort = '';
							}
					$theatable.= (strpos($bysort, ';')!==false?$acolv:'<a href="#!" id="sortby-'.$GLOBALS['atablenum'].'-'.$bysort.'" class="sortby">'.$iconsort.'&nbsp;'.$acolv.'</a>');
					$theatable.= '</th>';
				}
	$theatable.= '</thead>
			<tbody>';
	
	$i = 1;
	$per_page = $limit;
	$datarecord = db_num_rows(db_query($qrytable." ".$groupby." ".$where));
	$jml_halaman = ceil($datarecord/$per_page);
	$halaman = 1;

	// get page
	if(isset($_POST['h'])) {
		$halaman = $_POST['h'];
		$i=$i+(($halaman-1)*$per_page);
	}

	if(isset($_POST['afind']) != ""){
		if($_POST['afind']==''){
			$per_page = $limit;
			$querysql = $qrytable." ".$groupby." ".$where." ".$orderby." LIMIT $per_page OFFSET ".($halaman-1) * $per_page;
			$qry = db_query($querysql);
			if(db_num_rows($qry)==0){
				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="font-weight:bold;">No Data.</td><tr>';
			}
		}else{
			$afind=str_replace(' ','%',$_POST['afind']);
			if(strpos(strtolower($_POST['afind']), '"')!==false){
				$afind=str_replace('"','',preg_replace('/(?| *(".*?") *| *(\'.*?\') *)| +/s', '%$1', $_POST['afind']));
			}
			$per_page = $limitfind;

			if($where!=""){
				$iswhere = ' AND ';
			}else{
				$iswhere = ' HAVING ';
			}

			$colsrc=preg_replace("/,(?=[^)]*(?:[(]|$))/", ",' ',",$getcoltable);
			if($colsrc==" * "){$colsrc=implode(",",$atablecol);}
			$querysql = $qrytable." ".$groupby." ".$where.$iswhere."lower(concat(".$colsrc.")) LIKE '%".strtolower($afind)."%' "." ".$orderby." LIMIT $per_page OFFSET ".($halaman-1) * $per_page;
			$qry=db_query($querysql);

			if(db_num_rows($qry)==0){
				if(strpos(strtolower($qry), 'error')!==false){
					$sqlerror = TRUE;
					$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="color:#e74c3c;">'.$qry.'</td><tr>';
				}else{
					$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="font-weight:bold;">Not Found.</td><tr>';
				}
			}

			$jml_halaman = 1;
		}
	}else{
		if(isset($_POST['showall']) != ""){
			$querysql = $qrytable." ".$groupby." ".$where." ".$orderby;
			$qry = db_query($querysql);
			$jml_halaman = 1;
		}else{
			$querysql = $qrytable." ".$groupby." ".$where." ".$orderby." LIMIT $per_page OFFSET ".($halaman-1) * $per_page;
			$qry = db_query($querysql);
		}
		if(db_num_rows($qry)==0){
			if(strpos(strtolower($qry), 'error')!==false){
				$sqlerror = TRUE;
				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="color:#e74c3c;">'.$qry.'</td><tr>';
			}else{
				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="font-weight:bold;">No Data.</td><tr>';
			}
		}
	}
	
	if($showsql || $sqlerror){
		$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" align="center" style="color:#c1a;">'.$querysql.'</td><tr>';
	}

	if($qry){
		while($row=db_fetch_array($qry)){
			if(!empty($param)){
				eval($param);
			}
			$theatable.= '<tr>
					<td>'.$i.'</td>';
					$nocols=0;
					foreach($atablecol as $acol){
						$theatable.= '<td '.(isset($tdalign)?'style="text-align:'.($tdalign[$nocols++]=='R'?'right':'left').';"':'').'>';
							if(strpos($acol, ';')!==false){
								eval('$theatable.='.$acol);
							}else{
								$theatable.= $row[$acol];
							}
						$theatable.= '</td>';
					}
			$theatable.= '</tr>';
			$i++;
		}
	}

$theatable.= '</tbody>
	</table>';

$showpg=0;$class="";
$theatable.= '<!-- paging -->
<div class="datainfo">
'.((($i-1)==0?0:((($halaman-1) * $per_page)+1))." to ".($i-1)." of ".$datarecord." data").'
&nbsp;&nbsp;
<a href="#!" id="showall-'.$GLOBALS['atablenum'].'" class="showall">Show All</a>
<a href="#!" id="showles-'.$GLOBALS['atablenum'].'" class="showles" style="display:none;">Show Less</a>
</div>
<div class="paggingfield">
	<ul class="pagination">';
	if($halaman>1)$theatable.= '<li '.$class.'><a href="#!" id="'.($halaman-1).'-'.$GLOBALS['atablenum'].'" class="halaman">&laquo;</a></li>';
		for($page = 1;$page <= $jml_halaman;$page++){
			$page == $halaman ? $class='class="active"' : $class="";
			if((($page >= $halaman-3) && ($page <= $halaman +3)) || ($page==1) || ($page==$jml_halaman)){
				if(($showpg==1)&&($page !=2 )) $theatable.= '<li><a href="#!">...</a></li>';
				if(($showpg!=($jml_halaman-1))&&($page == $jml_halaman)) $theatable.= '<li><a href="#">...</a></li>';
				if($page == $halaman) $theatable.= '<li '.$class.'><a href="#!" id="'.$page.'-'.$GLOBALS['atablenum'].'">'.$page.'</a></li>';
				else $theatable.= '<li '.$class.'><a href="#!" id="'.$page.'-'.$GLOBALS['atablenum'].'" class="halaman">'.$page.'</a></li>';
				$showpg=$page;
			}
		}
	if($halaman<$jml_halaman)$theatable.= '<li '.$class.'><a href="#!" id="'.($halaman+1).'-'.$GLOBALS['atablenum'].'" class="halaman">&raquo;</a></li>
	</ul>
</div>';

}// end post
$theatable.= "</div></div>";
$GLOBALS['atablenum']++;
return $theatable;
}// end function

function GetBetween($pool,$var1="",$var2=""){
	$pool=strtolower($pool);//exception
	$temp1 = strpos($pool,$var1)+strlen($var1);
	$result = substr($pool,$temp1,strlen($pool));
	$dd=strpos($result,$var2);
	if($dd == 0){
	  $dd = strlen($result);
	}

	return substr($result,0,$dd);
}

function db_query($qry){
	$res = "";
	if($GLOBALS['linkDB']=="mysql"){
		$res = mysql_query($qry);
		if(!$res){
			$res = mysql_error();
		}
	}else if($GLOBALS['linkDB']=="mysqli"){
		$res = mysqli_query($GLOBALS['dbcon'],$qry);
		if(!$res){
			$res = mysqli_errno($GLOBALS['dbcon']);
		}
	}else if($GLOBALS['linkDB']=="pgsql"){
		$res = pg_query($qry);
		if(!$res){
			$res = pg_last_error($GLOBALS['dbcon']);
		}
	}
	return $res;
}
function db_fetch_array($qry){
	$res = "";
	if($GLOBALS['linkDB']=="mysql"){
		$res = mysql_fetch_array($qry);
	}else if($GLOBALS['linkDB']=="mysqli"){
		$res = mysqli_fetch_array($qry);
	}else if($GLOBALS['linkDB']=="pgsql"){
		$res = pg_fetch_array($qry);
	}
	return $res;
}
function db_num_rows($qry){
	$res = "";
	if($GLOBALS['linkDB']=="mysql"){
		$res = mysql_num_rows($qry);
	}else if($GLOBALS['linkDB']=="mysqli"){
		$res = mysqli_num_rows($qry);
	}else if($GLOBALS['linkDB']=="pgsql"){
		$res = pg_num_rows($qry);
	}
	return $res;
}

/* NOTE:
* Please asign $_POST['databases'] before call atable.php if the Atable Unkown Database
* Example:
* $_POST['databases']='mysql'; // for mysql database
* $_POST['databases']='mysqli'; // for mysqli database
* $_POST['databases']='pgsql'; // for pgsql database
*/
?>
