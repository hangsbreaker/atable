// JSON aTable ==================================================================
var aTable={};
aTable.table = function(){
	function tdata(n,t,p){this.n=n; this.id=t; this.p=p; this.init();}
	tdata.prototype.init=function(){
		this.sorttype = '';
		this.sortfield = '';
		this.tableId = this.id+'Rec';
		this.tbdata = this.p.data;
		this.columnSet = [];
		if(this.p.colnumber==undefined){this.p.colnumber=true;}
		if(this.p.limit==undefined){
			this.dtlimit=10;
			if(this.tbdata.length < this.dtlimit){
				this.dtlimit=this.tbdata.length;
			}
		}else{this.dtlimit=this.p.limit;}
		if(this.p.style==undefined){this.p.style='';}

		this.pagen = 1;
		$('#'+this.id).html('<div class="atable"><div class="atablepreloader" id="atablepreloader'+this.tableId+'">Loading ....</div><table id="'+this.tableId+'" class="'+this.p.style+'">'+(this.p.caption!=undefined?'<caption>'+this.p.caption+'</caption>':'')+'</table><div id="info'+this.tableId+'"></div></div>');
		$("#"+this.tableId).before('<div class="col-xs-2 findfield" style="margin-bottom: 10px;padding:0px 5px;min-width:200px;"><div class="input-group"><input type="text" class="jtxtcari form-control" name="cari" placeholder="Find" id="cari'+this.tableId+'" onkeyup="the_atable[\''+this.id+'\'].search(this);"><span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span></div></div>');
		this.thead();
	};
	tdata.prototype.thead=function(){
		$('#'+this.tableId+' > thead').remove();
		if(this.columnSet.length==0){this.columnSet = [];}
		var tHead$ = $('<thead/>');
		var headerTr$ = $('<tr/>');
		var iconsort = '';
		var nocol = 0;
		if(this.p.colnumber){
			headerTr$.append($('<th width="1px"/>').html('<a href="javascript:the_atable[\''+this.id+'\'].sorts(\'\')">#</a>'));
		}

		if(this.columnSet.length==0){
		for (var i = 0 ; i < this.tbdata.length ; i++) {
			var rowHash = this.tbdata[i];
			for (var key in rowHash) {
				if ($.inArray(key, this.columnSet) == -1){
					this.columnSet.push(key);
					if(this.p.colv==undefined){
						if(this.sortfield==key){
							if(this.sorttype=='asc'){
								iconsort = '<span class="icon-chevron-down" aria-hidden="true"></span>';
							}else{
								iconsort = '<span class="icon-chevron-up" aria-hidden="true"></span>';
							}
						}else{
							iconsort = '';
						}
						var csize='';
						if(this.p.colsize!=undefined){if(this.p.colsize[nocol]!=''){csize=' width="'+this.p.colsize[nocol]+'"';}}
						var calign='';
						if(this.p.colalign!=undefined){
							if(this.p.colalign[nocol]!=''){
								calign=' style="text-align:'+(this.p.colalign[nocol]=='R'?'right':(this.p.colalign[nocol]=='C'?'center':'left'))+';"';
							}
						}
						var chide=false;
						if(this.p.colhide!=undefined){if(this.p.colhide.indexOf(nocol)>-1){chide=true;}}
						var trparam=csize+calign;
						if(chide){trparam=' style="display:none;"';}
						headerTr$.append($('<th'+trparam+'/>').html('<a href="javascript:the_atable[\''+this.id+'\'].sorts(\''+key+'\')">'+iconsort+'&nbsp;'+key+'</a>'));
						nocol++;
					}
				}
			}
		}
		}else{
			if(this.p.colv==undefined){
				for (var i = 0 ; i < this.columnSet.length ; i++) {
					if(this.sortfield==this.columnSet[i]){
						if(this.sorttype=='asc'){
							iconsort = '<span class="icon-chevron-down" aria-hidden="true"></span>';
						}else{
							iconsort = '<span class="icon-chevron-up" aria-hidden="true"></span>';
						}
					}else{
						iconsort = '';
					}
					var csize='';
					if(this.p.colsize!=undefined){if(this.p.colsize[nocol]!=''){csize=' width="'+this.p.colsize[nocol]+'"';}}
					var calign='';
					if(this.p.colalign!=undefined){
						if(this.p.colalign[nocol]!=''){
							calign=' style="text-align:'+(this.p.colalign[nocol]=='R'?'right':(this.p.colalign[nocol]=='C'?'center':'left'))+';"';
						}
					}
					var chide=false;
					if(this.p.colhide!=undefined){if(this.p.colhide.indexOf(nocol)>-1){chide=true;}}
					var trparam=csize+calign;
					if(chide){trparam=' style="display:none;"';}
					headerTr$.append($('<th'+trparam+'/>').html('<a href="javascript:the_atable[\''+this.id+'\'].sorts(\''+this.columnSet[i]+'\')">'+iconsort+'&nbsp;'+this.columnSet[i]+'</a>'));
					nocol++;
				}
			}
		}

		iconsort = '';
		if(this.p.colv!=undefined){
			for (var i = 0 ; i < this.p.colv.length ; i++) {
				if(this.sortfield==this.columnSet[i]){
					if(this.sorttype=='asc'){
						iconsort = '<span class="icon-chevron-down" aria-hidden="true"></span>';
					}else{
						iconsort = '<span class="icon-chevron-up" aria-hidden="true"></span>';
					}
				}else{
					iconsort = '';
				}
				var csize='';
				if(this.p.colsize!=undefined){if(this.p.colsize[i]!=''){csize=' width="'+this.p.colsize[i]+'"';}}
				var calign='';
				if(this.p.colalign!=undefined){
					if(this.p.colalign[i]!=''){
						calign=' style="text-align:'+(this.p.colalign[i]=='R'?'right':(this.p.colalign[i]=='C'?'center':'left'))+';"';
					}
				}
				var chide=false;
				if(this.p.colhide!=undefined){if(this.p.colhide.indexOf(i)>-1){chide=true;}}
				var trparam=csize+calign;
				if(chide){trparam=' style="display:none;"';}
				headerTr$.append($('<th'+trparam+'/>').html('<a href="javascript:the_atable[\''+this.id+'\'].sorts(\''+this.columnSet[i]+'\')">'+iconsort+'&nbsp;'+this.p.colv[i]+'</a>'));
			}
		}
		tHead$.append(headerTr$);
		$("#"+this.tableId).append(tHead$);
		this.tbody();
	};
	tdata.prototype.tbody=function(){
		var maxnum=0;
		var collabel=[];
		$('#'+this.tableId+' > tbody').remove();
		var rowbody$ = $('<tbody/>');
		if(((this.pagen-1)*this.dtlimit)+this.dtlimit > this.tbdata.length){
			maxnum = this.tbdata.length;
		}else{
			maxnum = ((this.pagen-1)*this.dtlimit)+this.dtlimit;
		}

		collabel=this.columnSet;
		if(this.p.colv!=undefined){collabel=this.p.colv;}
		for (var i = ((this.pagen-1)*this.dtlimit) ; i < maxnum ; i++) {
			var row$ = $('<tr/>');
			if(this.p.colnumber){
				row$.append($('<td data-label="No"/>').html(i+1));
			}
			for (var colIndex = 0 ; colIndex < this.columnSet.length ; colIndex++) {
				var cellValue = this.tbdata[i][this.columnSet[colIndex]];
				if (cellValue == null || cellValue == undefined) { cellValue = "&nbsp;"; }
				var chide=false;
				if(this.p.colhide!=undefined){
					if(this.p.colhide.indexOf(colIndex)>-1){chide=true;}
				}
				var calign='';
				if(this.p.colalign!=undefined){
					if(this.p.colalign[colIndex]!=''){
						calign=' style="text-align:'+(this.p.colalign[colIndex]=='R'?'right':(this.p.colalign[colIndex]=='C'?'center':'left'))+';"';
					}
				}
				var trparam=calign;
				if(chide){trparam=' style="display:none;"';}
				row$.append($('<td data-label="'+collabel[colIndex]+'"'+trparam+'/>').html(cellValue));
			}
			// add column
			if(this.p.addcol!=undefined){
				for(var ac=0;ac<this.p.addcol.length;ac++){
					var calign='';
					if(this.p.colalign!=undefined){
						if(this.p.colalign[colIndex+ac]!=''){
							calign=' style="text-align:'+(this.p.colalign[colIndex+ac]=='R'?'right':(this.p.colalign[colIndex+ac]=='C'?'center':'left'))+';"';
						}
					}
					row$.append($('<td data-label="'+this.p.addcol[ac][0]+'"'+calign+'/>').html(this.p.addcol[ac][1]));
				}
			}
			rowbody$.append(row$);
		}
		$("#"+this.tableId).append(rowbody$);

		var showpg="";var pgclass="";
		this.numpage = Math.ceil(this.tbdata.length/this.dtlimit);
		var pagination = '<div class="paggingfield"><ul class="pagination">';
		if(this.pagen>1){
			pagination+= '<li '+pgclass+'><a href="javascript:the_atable[\''+this.id+'\'].move('+this.pagen+'-1);" id="'+(this.pagen-1)+'" class="jhalaman">&laquo;</a></li>';
		}
		for(var page = 1;page <= this.numpage;page++){
			pgclass = ((page == this.pagen)?pgclass='class="active"':pgclass='');
			if(((page >= this.pagen-2) && (page <= this.pagen +2)) || (page==1) || (page==this.numpage)){
				if((showpg==1)&&(page !=2 )){pagination+= '<li><a href="#!" class="gapdot">...</a></li>';}
				if((showpg!=(this.numpage-1))&&(page == this.numpage)){pagination+= '<li><a href="#" class="gapdot">...</a></li>';}
				if(page == this.pagen){pagination+= '<li '+pgclass+'><a href="#!" id="'+page+'" onclick="">'+page+'</a></li>';}
				else{pagination+= '<li '+pgclass+'><a href="javascript:the_atable[\''+this.id+'\'].move('+page+');" id="'+page+'" class="jhalaman" onclick="">'+page+'</a></li>';}
				showpg=page;
			}
		}
		if(this.pagen<this.numpage){
			pagination+= '<li '+pgclass+'><a href="javascript:the_atable[\''+this.id+'\'].move('+this.pagen+'+1);" id="'+this.pagen+'" class="jhalaman">&raquo;</a></li>';
		}
		pagination += '</ul></div>';

		$("#info"+this.tableId).html('<div class="datainfo">'+(((this.pagen-1)*this.dtlimit)+1)+' to '+maxnum+' of '+this.tbdata.length+' data&nbsp;&nbsp;<a href="javascript:the_atable[\''+this.id+'\'].showall();" id="jshowall" class="jshowall">Show All</a><a href="javascript:the_atable[\''+this.id+'\'].showaless();" id="jshowless" class="jshowless" style="display:none;">Show Less</a></div>'+pagination);
	};
	tdata.prototype.search=function(c){
		var q = $('#'+c.id).val();
		var threshold = 1;
		if(this.p.data.length>500){threshold = 3;}
		if(q.length>=threshold || q.length==0){
			var mtc= q.match(/\"/g)?q.match(/"/g).length:0;
			if(mtc == 2){
				q = q.toLowerCase().replace(/"/g, '');
			}else{
				q = q.replace(/ /g, '.*').toLowerCase();
			}

			var re = new RegExp(q, "g");
			var filter=[];
			for (var i = 0 ; i < this.p.data.length ; i++) {
				var rowobj = this.p.data[i];
				var row = Object.keys(rowobj).map(function(key) {return rowobj[key];}).join();
				row = row.toLowerCase().replace(/,/g, ' ');
				if(Boolean(row.match(re))){filter.push(this.p.data[i]);}
			}

			if(q!=''){
				this.pagen=1;
				this.tbdata=filter;
				this.dtlimit=filter.length;
			}else{
				this.pagen=1;
				this.tbdata=this.p.data;
				this.dtlimit=this.p.limit;
			}
			this.tbody();
		}
	};
	
	tdata.prototype.sort_by = function(field, ascdsc){
		var reverse = false;
		if(ascdsc=='desc'){var reverse = true;}
		var primer = function(a){a=a===undefined?' ':a;return a === parseInt(a, 10)?parseInt:a.toUpperCase();};
		var key = primer ?
		function(x) {return primer(x[field])} :
		function(x) {return x[field]};
		reverse = !reverse ? 1 : -1;
		return function (a, b) {return a = key(a), b = key(b), reverse * ((a > b) - (b > a));}
	}
	tdata.prototype.sorts=function(field){
		this.sortfield = field;
		if(this.sorttype==''){
			this.sorttype = 'asc';
		}else if(this.sorttype=='asc'){
			this.sorttype = 'desc';
		}else if(this.sorttype=='desc'){
			this.sorttype = 'asc';
		}
		console.log(field);
		if(field!=undefined && field!=''){
			this.thead();
			this.tbdata.sort(this.sort_by(this.sortfield, this.sorttype));
		}
	};

	tdata.prototype.showall=function(){
		var load=true;
		if(this.p.data.length>500){
			load=confirm("Load data?");
		}
		if(load){
			this.pagen=1;
			this.tbdata=this.p.data;
			this.dtlimit=this.tbdata.length;
			this.tbody();
			$("#info"+this.tableId+" > .datainfo > #jshowall").css('display','none');
			$("#info"+this.tableId+" > .datainfo > #jshowless").css('display','inline-block');
		}
	};
	tdata.prototype.showaless=function(){
		if(this.p.limit==undefined){
			this.dtlimit=10;
			if(this.p.data.length < this.dtlimit){
				this.dtlimit=this.p.data.length;
			}
		}else{this.dtlimit=this.p.limit;}
		this.tbody();
		$("#info"+this.tableId+" > .datainfo > #jshowless").css('display','none');
		$("#info"+this.tableId+" > .datainfo > #jshowall").css('display','inline-block');
	};
	tdata.prototype.move=function(n){
		this.pagen=n;
		this.tbody();
	};

	tdata.prototype.datarow=function(tr){
		var rows=[];
		$(tr).parents('tr').each(function( i ) {
		  $("td", this).each(function( j ) {rows.push($(this).text());});
		});
		return rows;
	};
	return{tdata:tdata}
}();

var the_atable = [];
function atable_build(table,param){
	the_atable[table] = new aTable.table.tdata('tdata['+table+']',table,param);
}
// END JSON aTable ==============================================================
/** Atable v4 Copyright @ 2018 Rachmadany **/
