<?php
$GLOBALS['atablenum']=0;
class Atable {
	// ==== param init
	var $query; var $col; var $colv; var $limit; var $limitfind; var $orderby; var $groupby; var $where; var $addvar; var $param; var $addlastrow; var $colsize; var $colalign; var $showsql; var $caption; var $style;
	var $colnumber=TRUE;
	var $searchbar=TRUE; var $datainfo=TRUE; var $paging=TRUE;
	var $reload=FALSE; var $collist=FALSE; var $xls=FALSE;
	var $querysql;
	var $database;
	var $linkDB="";var $dbcon="";

	function load(){
		if(empty($this->database)){
			foreach(array_reverse($GLOBALS) as $variable){
				if(is_resource($variable) && get_resource_type($variable)=='mysql link'){
					$this->linkDB = "mysql";
					$this->dbcon=$variable;
					break;
				}else if(is_object($variable)  && get_class($variable)=='mysqli'){
					$this->linkDB = "mysqli";
					$this->dbcon=$variable;
					break;
				}else if(is_resource($variable) && get_resource_type($variable)=='pgsql link'){
					$this->linkDB = "pgsql";
					$this->dbcon=$variable;
					break;
				}
			}
		}else{
			if(empty($this->linkDB)){
				$this->linkDB = $this->database;
			}
		}
		// ==== param init
		$qrytable = $this->query;
		$atablecol = json_decode($this->col);
		$atablecolv = json_decode($this->colv);
		$limit = !empty($this->limit)?$this->limit:10;
		$limitfind = !empty($this->limitfind)?$this->limitfind:300;
		$orderby = !empty($this->orderby)?$this->orderby:'';
		$groupby = !empty($this->groupby)?$this->groupby:'';
		$where = !empty($this->where)?$this->where:'';
		$colnumber = isset($this->colnumber)?$this->colnumber:TRUE;
		$addvar = !empty($this->addvar)?json_decode($this->addvar,true):'';
		$param = !empty($this->param)?$this->param:'';
		$addlastrow = !empty($this->addlastrow)?$this->addlastrow:'';
		$colsize = !empty($this->colsize)?json_decode($this->colsize):'';
		$colalign = !empty($this->colalign)?json_decode(strtoupper($this->colalign)):'';
		$showsql = !empty($this->showsql)?$this->showsql:'';
		$caption = !empty($this->caption)?$this->caption:'';
		$style = !empty($this->style)?$this->style:'table table-hover';
		$lblcol = array();
		$sortpost = "";
		$sqlerror = FALSE;
		$getcoltable=preg_replace("/ as [\s\S]+? /"," ",preg_replace("/ as [\s\S]+?,/",",",$this->GetBetween($qrytable,"select","from")));
		// ===============================


		$theatable= '<div class="atable">'.($this->linkDB == ''?'<div class="warningdb">Atable Unknown Database connection.</div>':'').'<div class="atablepreloader" id="atablepreloader'.$GLOBALS['atablenum'].'">Loading ....</div>
		<div class="col-xs-2 findfield" style="margin-bottom: 10px;padding:0px 5px;min-width:200px;"><div class="input-group"><input type="text" class="txtfind form-control" name="cari" placeholder="Find" id="txtfind-'.$GLOBALS['atablenum'].'"><span class="input-group-addon" id="basic-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span></div></div>
			<div class="dtatable" id="dtatable'.$GLOBALS['atablenum'].'">';
		if(isset($_POST['atabledata'.$GLOBALS['atablenum']]) && isset($_POST['fromatable'])){
			if(isset($_POST['sortby'])){
				if($_POST['sortby']!=""){
					$orderby=$_POST['sortby'];
					$sortpost=$_POST['sortby'];
				}
			}
			if(empty($limit)){$limit=10;}
			if(!empty($addvar)){extract($addvar);}
			if(!empty($orderby)){$orderby='ORDER BY '.$orderby;}
			if(!empty($groupby)){$groupby='GROUP BY '.$groupby;}else{if($getcoltable!=' * '){$groupby='GROUP BY '.$getcoltable;}}
			if(!empty($where)){$where='HAVING '.$where;}
			$theatable.= '<div class="atablewrap" id="atablewrap'.$GLOBALS['atablenum'].'">
						<table class="'.$style.'" id="dtblatable'.$GLOBALS['atablenum'].'" border="0">
    				<caption>'.$caption.'</caption>
    				<thead>';
    				$atr=0;
    				foreach ($atablecolv as $vth) {if(is_array($vth)){$atr++;}}
    				if($atr==0){$theatable.= '<tr>';$theatable.= ($colnumber==TRUE?'<th width="1px"'.($atr>0?' rowspan="'.$atr.'"':'').'>No</th>':'');}
    				$sortpost = explode(" ",$sortpost);
    				foreach($atablecolv as $key=>$acolv){
    					if(is_array($acolv)){
    						$theatable.= '<tr>';
                if($key==0){$theatable.= ($colnumber==TRUE?'<th width="1px"'.($atr>0?' rowspan="'.$atr.'"':'').'>No</th>':'');}
    		        $vthn=$vth[0];$colrown=array();$colrowv=array();$arrkey=0;
    						foreach ($acolv as $key => $vth) {
    				      $vthn=$vth;$colrow='';$colsz='';$colalgn='';
    				      if(is_array($vth)){
    				        $vthn=$vth[0];
    				        foreach ($vth as $keyn => $value) {
    				          if($value!='' && $keyn!=0){
    										if(strtolower(substr($value,0,1))=='w'){
    											$colsz=' width="'.substr($value,1).'"';
    										}else if(strtolower($value)=='ac'){
    											$colalgn=' style="text-align:center;"';
    										}else if(strtolower($value)=='al'){
    											$colalgn=' style="text-align:left;"';
    										}else if(strtolower($value)=='ar'){
    											$colalgn=' style="text-align:right;"';
    										}else{
    					            $colrown[$key]=substr($value,3);
    					            $colrowv[$key]=strtolower(substr($value,0,3));
    										}
    				            $colrow.=' '.$colrowv[$key].'span="'.$colrown[$key].'"';
    				          }
    				        }
    				      }

    							$theatable.= '<th'.$colsz.$colalgn.$colrow.'>';
									$arrkey=($colrowv[$key-1]=='col' && $key>0?$key+$colrown[$key-1]-1:$arrkey);
									$nmcol= str_replace('$','',str_replace(';','',$atablecol[$arrkey]));
									$existcol= $this->GetBetween($qrytable,"select","from");
									if(strpos($existcol,$nmcol)!==false){
										$bysort = $nmcol;
									}else{
										$bysort = $vthn.';';
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

									$lblcol[$arrkey]=$vthn;

    							$theatable.= (strpos($bysort, ';')!==false||($colrowv=='col')?$vthn.$kk:$kk.'<a href="javascript:void(0);" id="sortby-'.$GLOBALS['atablenum'].'-'.$bysort.'" class="sortby" onclick="atable_sortedby(this);">'.$iconsort.'&nbsp;'.$vthn.'</a>');
    							$theatable.= '</th>';
									$arrkey++;
    						}
								$kyrow++;
    						$theatable.= '</tr>';
    					}else{
    						$theatable.= '<th'.(isset($colsize[$key])?' width="'.$colsize[$key].'"':'').(isset($colalign)?' style="text-align:'.($colalign[$key]=='R'?'right':($colalign[$key]=='C'?'center':'left')).';"':'').$colrow.'>';
								$nmcol= str_replace('$','',str_replace(';','',$atablecol[$key]));
								$existcol= $this->GetBetween($qrytable,"select","from");
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
							$lblcol[$key]=$acolv;
    						$theatable.= (strpos($bysort, ';')!==false?$acolv:'<a href="javascript:void(0);" id="sortby-'.$GLOBALS['atablenum'].'-'.$bysort.'" class="sortby" onclick="atable_sortedby(this);">'.$iconsort.'&nbsp;'.$acolv.'</a>');
    						$theatable.= '</th>';
    					}
    				}
					ksort($lblcol);
    				if($atr==0){$theatable.= '</tr>';}
    	$theatable.= '</thead>
    			<tbody>';

    	$i = 1;
    	$per_page = $limit;
    	$datarecord = $this->db_num_rows($this->db_query($qrytable." ".$groupby." ".$where));
    	$jml_pages = ceil($datarecord/$per_page);
    	$pages = 1;

    	// get page
    	if(isset($_POST['h'])) {
    		$pages = $_POST['h'];
    		$i=$i+(($pages-1)*$per_page);
    	}

    	if(isset($_POST['afind'])){
    		if($_POST['afind']==''){
    			$per_page = $limit;
    			if(isset($_POST['showall'])){
    				$forlimit = "";
    				$jml_pages = 1;
    			}else{
    				$forlimit = " LIMIT $per_page OFFSET ".($pages-1) * $per_page;
    			}
    			$this->querysql = $qrytable." ".$groupby." ".$where." ".$orderby.$forlimit;
    			$qry = $this->db_query($this->querysql);
    			if($this->db_num_rows($qry)==0){
    				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="font-weight:bold;text-align:center;">No Data.</td><tr>';
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

    			if(isset($_POST['showall'])){
    				$forlimit = "";
    			}else{
    				$forlimit = " LIMIT $per_page OFFSET ".($pages-1) * $per_page;
    			}

    			$columnwhere="";
    			$colsrc=preg_replace("/,(?=[^)]*(?:[(]|$))/", ",' ',",$getcoltable);
    			if($colsrc==" * "){$colsrc=implode(",' ',",$atablecol);}
    			$colwhere=explode(",' ',",$colsrc);
    			$lencol=count($colwhere);
    			if($lencol>50){
    				$i=0;
    				$columnwhere.="(";
    				for($n=0;$n<ceil(count($colwhere)/50);$n++){
    					$arrhalf = array_slice($colwhere, $n+$i, 25*($n+1));

    					if($n>0){$columnwhere.=" OR ";}
    					$columnwhere.="lower(concat(";
    					foreach($arrhalf as $key => $value){
    						if($key!=0){$columnwhere.=",' ',";}
    						$columnwhere.=$value;
    						$i++;
    					}
    					$columnwhere.=")) LIKE '%".strtolower($afind)."%'";
    				}
    				$columnwhere.=")";
    			}else{
    				$columnwhere="lower(concat(".$colsrc.")) LIKE '%".strtolower($afind)."%'";
    			}
    			$this->querysql = $qrytable." ".$groupby." ".$where.$iswhere.$columnwhere." "." ".$orderby.$forlimit;
    			$qry=$this->db_query($this->querysql);

    			if($this->db_num_rows($qry)==0){
    				if(strpos(strtolower($qry), 'error')!==false){
    					$sqlerror = TRUE;
    					$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="color:#e74c3c;text-align:center;">'.$qry.'</td><tr>';
    				}else{
    					$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="font-weight:bold;text-align:center;">Not Found.</td><tr>';
    				}
    			}

    			$jml_pages = 1;
    		}
    	}else{
    		if(isset($_POST['showall'])){
    			$this->querysql = $qrytable." ".$groupby." ".$where." ".$orderby;
    			$qry = $this->db_query($this->querysql);
    			$jml_pages = 1;
    		}else{
    			$this->querysql = $qrytable." ".$groupby." ".$where." ".$orderby." LIMIT $per_page OFFSET ".($pages-1) * $per_page;
    			$qry = $this->db_query($this->querysql);
    		}

    		if($this->db_num_rows($qry)==0){
    			if(strpos(strtolower($qry), 'error')!==false){
    				$sqlerror = TRUE;
    				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="color:#e74c3c;text-align:center;">'.$qry.'</td><tr>';
    			}else{
    				$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="font-weight:bold;text-align:center;">No Data.</td><tr>';
    			}
    		}
    	}

    	if($showsql || $sqlerror){
    		$theatable.= '<tr><td colspan="'.(count($atablecol)+1).'" style="text-align:center !important;color:#c1a;">'.$this->querysql.'</td></tr>';
    	}

    	if($qry){$continue=FALSE;$break=FALSE;
    		while($row=$this->db_fetch_object($qry)){
    			if(!empty($param)){eval($param);}
    			if($continue){$continue=FALSE;continue;}
    			if($break){$break=FALSE;break;}
    			$theatable.= '<tr>'.
    					($colnumber==TRUE?'<td data-label="No">'.$i.'</td>':'');
    					$nocols=0;
    					foreach($atablecol as $key=>$acol){
    						$theatable.= '<td '.(isset($colalign)?'style="text-align:'.($colalign[$nocols]=='R'?'right':($colalign[$nocols]=='C'?'center':'left')).';"':'').' data-label="'.$lblcol[$key].'">';
    							if(strpos($acol, ';')!==false){
    								eval('$theatable.='.$acol);
    							}else{
    								$theatable.= $row->$acol!=""?$row->$acol:"&nbsp;";
    							}
    						$theatable.= '</td>';
    						$nocols++;
    					}
    			$theatable.= '</tr>';
    			$i++;
    		}
    	}

		if(!empty($addlastrow)){eval('$theatable.='.$addlastrow);}
		$theatable.= '</tbody>
		</table></div>';

		$showpg=0;$class="";//$lblcol
		$theatable.= '<!-- datainfo -->
		<div class="colhide" id="colhide'.$GLOBALS['atablenum'].'">
		<div style="margin-bottom:6px;"><select multiple="multiple" style="width:250px;min-height:83px;max-height:120px;" id="slctmltp'.$GLOBALS['atablenum'].'" class="form-control">';
			if($this->colnumber){
				$theatable.= '<option value="0" selected="selected">No</option>';
			}
			foreach($lblcol as $key=>$lbl){
				$theatable.= '<option value="'.($this->colnumber?$key+1:$key).'" selected="selected">'.$lbl.'</option>';
			}
		$theatable.= '</select></div>
		<button type="button" class="btn btn-default btn-sm" id="colhidecancel" style="float:right" onclick="showhide(\'colhide'.$GLOBALS['atablenum'].'\')">Cancel</button>
		<button type="button" onclick="atable_hidecol(\'dtblatable'.$GLOBALS['atablenum'].'\',getSelectMultiValues(\'slctmltp'.$GLOBALS['atablenum'].'\'),'.$GLOBALS['atablenum'].');showhide(\'colhide'.$GLOBALS['atablenum'].'\')" class="btn btn-default btn-sm" id="colhideok" style="float:right">Ok</button>
		</div>
		<div class="datainfo">'.
		($this->reload==TRUE?
		  '<button type="button" onclick="atable_reload('.$GLOBALS['atablenum'].')" class="btn btn-info btn-xs" title="Reload" id="dtreload" style="font-size:18px;height:30px;">&#8635;</button>&nbsp;':'').
		($this->collist==TRUE?
		  '<button type="button" onclick="showhide(\'colhide'.$GLOBALS['atablenum'].'\')" class="btn btn-default btn-xs" title="Column" id="dtlist" style="font-size:18px;height:30px;">&#8862;</button>&nbsp;':'').
		($this->xls==TRUE?
		  '<button type="button" onclick="atable_toxls(\'dtblatable'.$GLOBALS['atablenum'].'\',\''.str_replace(" ","_",$caption).'\')" class="btn btn-success btn-sm" title="Export to Excel" id="dtxls">xls</button>&nbsp;':'').
		($this->datainfo==TRUE?
		((($i-1)==0?0:((($pages-1) * $per_page)+1))." to ".($i-1)." of ".$datarecord." data").
		'&nbsp;&nbsp;
		<a href="javascript:void(0);" id="showall-'.$GLOBALS['atablenum'].'" class="showall" onclick="atable_showall(this);">Show All</a>
		<a href="javascript:void(0);" id="showless-'.$GLOBALS['atablenum'].'" class="showless" style="display:none;" onclick="atable_showless(this);">Show Less</a>':'').'
		</div>
		<!-- paging -->
		<div class="paggingfield" '.($this->paging==TRUE?'':'style="display:none;"').'>
			<ul class="pagination">';
			if($pages>1){
				$theatable.= '<li '.$class.'><a href="javascript:void(0);" id="'.($pages-1).'-'.$GLOBALS['atablenum'].'" class="pages" onclick="atable_pages(\''.($pages-1).'-'.$GLOBALS['atablenum'].'\');">&laquo;</a></li>';
			}
			for($page = 1;$page <= $jml_pages;$page++){
				$page == $pages ? $class='class="active"' : $class="";
				if((($page >= $pages-2) && ($page <= $pages +2)) || ($page==1) || ($page==$jml_pages)){
					if(($showpg==1)&&($page !=2 )){$theatable.= '<li><a href="javascript:void(0);" class="gapdot">...</a></li>';}
					if(($showpg!=($jml_pages-1))&&($page == $jml_pages)){$theatable.= '<li><a href="javascript:void(0);" class="gapdot">...</a></li>';}
					if($page == $pages){$theatable.= '<li '.$class.'><a href="javascript:void(0);" id="'.$page.'-'.$GLOBALS['atablenum'].'" onclick="atable_pages(\''.$page.'-'.$GLOBALS['atablenum'].'\');">'.$page.'</a></li>';}
					else{$theatable.= '<li '.$class.'><a href="javascript:void(this);" id="'.$page.'-'.$GLOBALS['atablenum'].'" class="pages" onclick="atable_pages(\''.$page.'-'.$GLOBALS['atablenum'].'\');">'.$page.'</a></li>';}
					$showpg=$page;
				}
			}
			if($pages<$jml_pages){
				$theatable.= '<li '.$class.'><a href="javascript:void(0);" id="'.($pages+1).'-'.$GLOBALS['atablenum'].'" class="pages" onclick="atable_pages(\''.($pages+1).'-'.$GLOBALS['atablenum'].'\');">&raquo;</a></li>';
			}
			$theatable.= '</ul>
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
  	if($this->linkDB=="mysql"){
  		$res = mysql_query($qry);
  		if(!$res){
  			$res = mysql_error();
  		}
  	}else if($this->linkDB=="mysqli"){
  		$res = mysqli_query($this->dbcon,$qry);
  		if(!$res){
  			$res = mysqli_errno($this->dbcon);
  		}
  	}else if($this->linkDB=="pgsql"){
  		$res = pg_query($qry);
  		if(!$res){
  			$res = pg_last_error($this->dbcon);
  		}
  	}else if($this->linkDB=="ci"){
			$this->CI = & get_instance();
			$database=!empty($this->database)?$this->database:"db";
  		$res = $this->CI->$database->query($qry);
  		if(!$res){
  			$res = $this->CI->$database->_error_message();
  		}
  	}
  	return $res;
  }
  function db_fetch_object($qry){
  	$res = "";
  	if($this->linkDB=="mysql"){
  		$res = mysql_fetch_object($qry);
  	}else if($this->linkDB=="mysqli"){
  		$res = mysqli_fetch_object($qry);
  	}else if($this->linkDB=="pgsql"){
  		$res = pg_fetch_object($qry);
  	}else if($this->linkDB=="ci"){
  		//$res = $qry->_fetch_object();
  		$res = $qry->unbuffered_row();
  	}
  	return $res;
  }
  function db_num_rows($qry){
  	$res = "";
  	if($this->linkDB=="mysql"){
  		$res = mysql_num_rows($qry);
  	}else if($this->linkDB=="mysqli"){
  		$res = mysqli_num_rows($qry);
  	}else if($this->linkDB=="pgsql"){
  		$res = pg_num_rows($qry);
  	}else if($this->linkDB=="ci"){
  		$res = $qry->num_rows();
  	}
  	return $res;
  }
}
function atable_init(){
	if(!isset($_POST['fromatable'])){
	echo '<style>
	.atable{display:block;clear:both;margin-top:10px;margin-bottom:80px;}
	.atable .dtatable .table, .atable .jdtatable .table{margin-bottom:0px;}
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
	.atable .paggingfield{
		margin:0px 5px;
	}
	.atable .datainfo{
		left:0px;
		display:block;clear:both;float:left;
		right:0px;margin:20px 5px;position:relative;padding:10px 5px;
	}
	.atable .warningdb{
		position:absolute;
		width:100%;
		padding:25px;
		font-size:18px;
		font-weight:bold;
		text-align:center;
		background:#eee;
		z-index:7;
	}
	.atable .paggingfield, .atable .findfield{
		float:right;
	}
	.atable .colhide{
		display:none;
		position: absolute;
		z-index: 2;
		margin-top: -115px;
		margin-left: 20px;
		background: #fff;
		padding: 10px;
		border-radius: 5px;
		box-shadow: 0px 0px 5px 0px #333;
	}
	.atable table caption {
		color:#000;
		font-size: 1.5em;
		text-align: center;
	}
	@media screen and (max-width: 550px) {
		.atable table{
			border-collapse: collapse;
			table-layout: fixed;
			border: 0;
		}
		.atable table caption {
			font-size: 1.3em;
		}
		.atable table thead {
			border: none;
			clip: rect(0 0 0 0);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
		}
		.atable table tr {
			border-bottom: 3px solid #ddd;
			display: block;
			margin-bottom: .625em;
		}
		.atable table td {
			border-top: 0px solid #ddd;
			display: block;
			font-size: .8em;
			text-align: right !important;
		}
		.atable table td:before {
			content: attr(data-label);
			float: left;
			font-weight: bold;
		}
		.atable table td:last-child {
			border-bottom: 0;
		}
		.atable .paggingfield {
			margin: 40px 5px;
		}
		.atable .paggingfield .pagination>li>a, .pagination>li>span {
			padding: 4px 5px;
		}
		.atable .paggingfield .pagination .gapdot {
			padding: 4px 4px;
		}
	}
	</style>
	<script>
	var xhr;
	var thepage="";
	var datapost={};
	var sortby=[];var ascdsc=[];var numpage=[];var colshowhide=[];
	var atable;var forEach;var atablests=[];
	(function($) {
		$(window).load(function() {});
		$(document).ready(function(e) {
			//declare
			atable = document.querySelectorAll(".dtatable");
			forEach = [].forEach;
			forEach.call(atable, function (el, i) {
				atable[i].insertAdjacentHTML("beforeBegin","");
				atable[i].insertAdjacentHTML("afterEnd","");
				atablests[i]=false;
			});

			$(".txtfind").keyup(function(event){
				//if(event.which == 13){
					xhr.abort();
					var vid = this.id.split("-");

					var v_afind = $("#txtfind-"+vid[1]).val();
					document.getElementById("atablepreloader"+vid[1]).style.display="block";
					document.getElementById("showless-"+vid[1]).style.display="none";
					document.getElementById("showall-"+vid[1]).style.display="inline-block";

					var tbpage = Object.assign({}, datapost);
					numpage[vid[1]]=1;
					tbpage["atabledata"+vid[1]]=true;
					tbpage["sortby"]=sortby[vid[1]];
					tbpage["colshowhide"]=colshowhide[vid[1]];
					tbpage["fromatable"]=true;
					tbpage.afind=v_afind;

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
							atable_hidecol("dtblatable"+vid[1],colshowhide[vid[1]],vid[1]);
						}
					});
				//}
			});

		});
	}) (jQuery);



	function atable_pages(val){
		xhr.abort();
		var vid = val.split("-");
		var v_afind = $("#txtfind-"+vid[1]).val();
		document.getElementById("atablepreloader"+vid[1]).style.display="block";

		var tbpage = Object.assign({}, datapost);
		tbpage.h=vid[0];numpage[vid[1]]=vid[0];
		tbpage["atabledata"+vid[1]]=true;
		tbpage["sortby"]=sortby[vid[1]];
		tbpage["colshowhide"]=colshowhide[vid[1]];
		tbpage["fromatable"]=true;
		tbpage.afind=v_afind;
		//alert(tbpage.toSource());
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
				atable_hidecol("dtblatable"+vid[1],colshowhide[vid[1]],vid[1]);
			}
		});
	};

	function atable_getpage(tableID){
		if(numpage[tableID]==undefined){
			numpage[tableID]=1;
		}
		return numpage[tableID];
	}

	function atable_topage(natbl,page){
		var fn=0;
		if(atablests[natbl]){
			atable_pages(page+"-"+natbl);
			numpage[natbl]=page;
		}
		$(document).ajaxStop(function(){
		  if(fn==0){
				atable_pages(page+"-"+natbl);
		    fn++;
		  }
		});
	}

	function atable_find(natbl,str){
		var fn=0;
		if(atablests[natbl]){
			$("#txtfind-"+natbl).val(str);
			$("#txtfind-"+natbl).keyup();
		}
		$(document).ajaxStop(function(){
		  if(fn==0){
		    $("#txtfind-"+natbl).val(str);
		    $("#txtfind-"+natbl).keyup();
		    fn++;
		  }
		});
	}

	function atable_showall(me){
		xhr.abort();
		var vid = me.id.split("-");
		var v_afind = $("#txtfind-"+vid[1]).val();
		document.getElementById("atablepreloader"+vid[1]).style.display="block";

		var tbpage = Object.assign({}, datapost);
		tbpage.showall=true;
		numpage[vid[1]]=1;
		tbpage["atabledata"+vid[1]]=true;
		tbpage["sortby"]=sortby[vid[1]];
		tbpage["colshowhide"]=colshowhide[vid[1]];
		tbpage["fromatable"]=true;
		tbpage.afind=v_afind;
		//alert(tbpage.toSource());
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
				document.getElementById("showless-"+vid[1]).style.display="inline-block";
				document.getElementById("showall-"+vid[1]).style.display="none";
				atable_hidecol("dtblatable"+vid[1],colshowhide[vid[1]],vid[1]);
			}
		});
	};

	function atable_showless(me){
		xhr.abort();
		var vid = me.id.split("-");
		var v_afind = $("#txtfind-"+vid[1]).val();
		document.getElementById("atablepreloader"+vid[1]).style.display="block";

		var tbpage = Object.assign({}, datapost);
		numpage[vid[1]]=1;
		tbpage["atabledata"+vid[1]]=true;
		tbpage["sortby"]=sortby[vid[1]];
		tbpage["colshowhide"]=colshowhide[vid[1]];
		tbpage["fromatable"]=true;
		tbpage.afind=v_afind;
		//alert(tbpage.toSource());
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
				document.getElementById("showless-"+vid[1]).style.display="none";
				document.getElementById("showall-"+vid[1]).style.display="inline-block";
				atable_hidecol("dtblatable"+vid[1],colshowhide[vid[1]],vid[1]);
			}
		});
	};

	function atable_sortedby(me){
		xhr.abort();
		var vid = me.id.split("-");
		var v_afind = $("#txtfind-"+vid[1]).val();
		document.getElementById("atablepreloader"+vid[1]).style.display="block";
		if(ascdsc[vid[1]]==""){
			sortby[vid[1]] = vid[2]+" ASC";
			ascdsc[vid[1]]="ASC";
		}else if(ascdsc[vid[1]]=="ASC"){
			sortby[vid[1]] = vid[2]+" DESC";
			ascdsc[vid[1]]="DESC";
		}else{
			sortby[vid[1]] = vid[2]+" ASC";
			ascdsc[vid[1]]="ASC";
		}

		var tbpage = Object.assign({}, datapost);
		tbpage["atabledata"+vid[1]]=true;
		tbpage["sortby"]=sortby[vid[1]];
		tbpage["colshowhide"]=colshowhide[vid[1]];
		tbpage["fromatable"]=true;
		tbpage.afind=v_afind;
		//alert(tbpage.toSource());
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
				document.getElementById("showless-"+vid[1]).style.display="none";
				document.getElementById("showall-"+vid[1]).style.display="inline-block";
				atable_hidecol("dtblatable"+vid[1],colshowhide[vid[1]],vid[1]);
			}
		});
	};

	function load_atable(curpage,post){
		thepage = curpage;
		datapost=JSON.parse(post);
		if(datapost.length != 0 && post.toLowerCase().indexOf("toatable") < 0){
			datapost=[];
		}
		var loadtable = Object.assign({}, datapost);
		var atable = document.querySelectorAll(".dtatable");
		var forEach = [].forEach;
		forEach.call(atable, function (el, i) {
			numpage[i]=1;
			sortby[i]="";
			colshowhide[i]=[];
			ascdsc[i]="";
			loadtable["atabledata"+i]=true;
			document.getElementById("atablepreloader"+i).style.display="block";
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

				var atable = document.querySelectorAll(".dtatable");
				var forEach = [].forEach;
				forEach.call(atable, function (el, i) {
					if(data!=""){
						atable[i].innerHTML=atableno[i];
						atablests[i]=true;
					}
					document.getElementById("atablepreloader"+i).style.display="none";
				});
			}
		});
	}

	function atable_reload(vid){
	  var myEle = document.getElementById("atablepreloader"+vid);
	  if(myEle){
		xhr.abort();
			var v_afind = $("#txtfind-"+vid).val();
			document.getElementById("atablepreloader"+vid).style.display="block";
			document.getElementById("showless-"+vid).style.display="none";
			document.getElementById("showall-"+vid).style.display="inline-block";

			var tbpage = Object.assign({}, datapost);
			tbpage["atabledata"+vid]=true;
			tbpage["sortby"]=sortby[vid];
			tbpage["colshowhide"]=colshowhide[vid[1]];
			tbpage["fromatable"]=true;
			tbpage.afind=v_afind;

			xhr = $.ajax({
				type: "POST",
				url: thepage,
				data: tbpage,
				success: function(data){
					document.getElementById("atablepreloader"+vid).style.display="none";
					var atableno=[];
					var htmldata = "<div>"+rbline(data)+"</div>";
					$(htmldata).find(".dtatable").each(function(i, obj){
						atableno[i]=this.innerHTML;
					});

					forEach.call(atable, function (el, i) {
						if(i==vid){
							atable[i].innerHTML=atableno[i];
						}
					});
				}
			});
		}else{
			console.log("aTable "+vid+" not Exist.");
	  }
	}

	function atable_toxls(tableID, filename = ""){
		var downloadLink;
		var dataType = "application/vnd.ms-excel";
		var tableSelect = document.getElementById(tableID);
		var tableHTML = remHiddenTag(tableSelect.outerHTML,"none").replace(/ /g,"%20").replace(/<\/?a[^>]*>/g,"").replace(\'border="0"\',\'border="1"\');
		filename = filename?filename+".xls":"excel_data.xls";
		downloadLink = document.createElement("a");

		document.body.appendChild(downloadLink);

		if(navigator.msSaveOrOpenBlob){
			var blob = new Blob(["\ufeff", tableHTML], {
			type: dataType
			});
			navigator.msSaveOrOpenBlob(blob, filename);
		}else{
			downloadLink.href = "data:"+dataType+", "+tableHTML;
			downloadLink.onclick = atabledestroyClickedElement;
			downloadLink.download = filename;
			downloadLink.click();
		}
	}
	function atabledestroyClickedElement(event){document.body.removeChild(event.target);}

	function atable_hidecol(tblid,arcol,atablenum="") {
		var cols = arcol;
		if(cols.length < 0){
			console.log("Invalid");
			return;
		}else{
			var tbl = document.getElementById(tblid);
			var slctmltp = document.getElementById("slctmltp"+atablenum);
			if (tbl != null) {
				for (var i = 0; i < tbl.rows.length; i++) {
					var ncc=0;
					if(tbl.rows[i].cells.length>1){
						for (var j = 0; j < tbl.rows[i].cells.length; j++) {
							tbl.rows[i].cells[j].style.display = "";
							colspan = tbl.rows[i].cells[j].getAttribute("colspan");
							if(colspan>0){
								 ncc = ncc+parseInt(colspan)-1;
							}
							slctmltp.options[ncc].selected = true;
							if(cols.includes(ncc)){
								tbl.rows[i].cells[j].style.display = "none";
		 						slctmltp.options[ncc].selected = false;
							}
							ncc++;
						}
					}
				}
			}
		}
		colshowhide[atablenum]=arcol;
	}

	function getSelectMultiValues(select) {
		var result = [];
		var options = document.getElementById(select);
		for (var i=0, iLen=options.length; i<iLen; i++) {
			if (!options[i].selected) {result.push(parseInt(options[i].value) || parseInt(options[i].text) || 0);}
		}
		return result;
	}
	function showhide(meid=""){
		var tag = document.getElementById(meid);
		if(tag.style.display === "block"){
			tag.style.display = "none";
		}else{
			tag.style.display = "block";
		}
	}
	function remHiddenTag(html, match) {
	    var container = document.createElement("span");
	    container.innerHTML = html;
	    Array.from(container.querySelectorAll("[style*="+CSS.escape(match)+"]"))
	        .forEach( link => link.parentNode.removeChild(link));
	    return container.innerHTML;
	}
	function rbline(str){var text=str;text = text.replace(/(\r\n|\n|\r)/gm," ");text = text.replace(/\s{2,}/g, " ");return text;}
	</script>';
	$http_s = isset($_SERVER['HTTPS'])?"https://":"http://";
	$this_page = $http_s.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	echo "<script>$(document).ready(function(e) {load_atable('".$this_page."','".json_encode($_POST)."');});</script>";
	}
}
?>
