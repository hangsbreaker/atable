// JSON aTable ==================================================================
var aTable = {};
aTable.table = (function() {
	function tdata(n, t, p) {
		this.n = n;
		this.id = t;
		this.p = p;
		this.init();
	}
	tdata.prototype.init = function() {
		this.sorttype = "";
		this.sortfield = "";
		this.tableId = this.id + "Rec";
		this.tbdata = this.p.data;
		this.columnSet = [];
		this.colshowhide = [];
		this.lblcol = [];
		this.numdata = 0;
		if (this.p.colnumber == undefined) {
			this.p.colnumber = true;
		}
		if (this.p.limit == undefined) {
			this.dtlimit = 10;
			if (this.tbdata.length < this.dtlimit) {
				this.dtlimit = this.tbdata.length;
			}
		} else {
			this.dtlimit = this.p.limit;
		}
		if (this.p.style == undefined) {
			this.p.style = "";
		}
		if (this.p.collist == undefined) {
			this.collist = false;
		} else {
			this.collist = this.p.collist;
		}
		if (this.p.xls == undefined) {
			this.xls = false;
		} else {
			this.xls = this.p.xls;
		}

		this.pagen = 1;
		$("#" + this.id).html(
			'<div class="atable"><div class="atablepreloader" id="atablepreloader' +
				this.tableId +
				'">Loading ....</div><div class="dtatable" id="dtatable' +
				this.tableId +
				'"><div class="atablewrap" id="atablewrap' +
				this.tableId +
				'"><table id="' +
				this.tableId +
				'" class="' +
				this.p.style +
				'">' +
				(this.p.caption != undefined
					? "<caption>" + this.p.caption + "</caption>"
					: "") +
				'</table></div></div><div id="info' +
				this.tableId +
				'"></div></div>'
		);
		$("#dtatable" + this.tableId).before(
			'<div class="findfield" style="margin-bottom: 10px;padding:0px 5px;min-width:200px;"><input type="text" class="txtfind" name="find" placeholder="Search" id="txtfind-' +
				this.tableId +
				'" onkeyup="the_atable[\'' +
				this.id +
				'\'].searching(this);"><div class="fndclear" onclick="the_atable[\'' +
				this.id +
				"'].clearsrc('" +
				this.tableId +
				"')\">&times;</div></div>"
		);
		this.thead();
	};
	tdata.prototype.thead = function() {
		$("#" + this.tableId + " > thead").remove();
		if (this.columnSet.length == 0) {
			this.columnSet = [];
		}
		var tHead$ = $("<thead/>");
		var headerTr$ = $("<tr/>");
		var iconsort = "";
		var nocol = 0;
		if (this.p.colnumber) {
			headerTr$.append(
				$('<th width="1px"/>').html(
					"<a href=\"javascript:the_atable['" + this.id + "'].sorts('')\">#</a>"
				)
			);
		}

		if (this.columnSet.length == 0) {
			for (var i = 0; i < this.tbdata.length; i++) {
				var rowHash = this.tbdata[i];
				for (var key in rowHash) {
					if (key == "subtitle" || key == "foottitle") {
					} else {
						if ($.inArray(key, this.columnSet) == -1) {
							this.columnSet.push(key);
							if (this.p.colv == undefined) {
								if (this.sortfield == key) {
									if (this.sorttype == "asc") {
										iconsort =
											'<span class="icon-chevron-down" aria-hidden="true"></span>&nbsp;';
									} else {
										iconsort =
											'<span class="icon-chevron-up" aria-hidden="true"></span>&nbsp;';
									}
								} else {
									iconsort = "";
								}
								var csize = "";
								if (this.p.colsize != undefined) {
									if (
										this.p.colsize[nocol] != "" &&
										this.p.colsize[nocol] != undefined
									) {
										csize = ' width="' + this.p.colsize[nocol] + '"';
									}
								}
								var calign = "";
								if (this.p.colalign != undefined) {
									if (this.p.colalign[nocol] != "") {
										calign =
											"text-align:" +
											(this.p.colalign[nocol] == "R"
												? "right"
												: this.p.colalign[nocol] == "C"
												? "center"
												: "left") +
											";";
									}
								}
								var chide = false;
								if (this.p.colhide != undefined) {
									if (this.p.colhide.indexOf(nocol) > -1) {
										chide = true;
									}
								}
								var tcolhide = "";
								if (chide) {
									tcolhide = "display:none;";
								}

								var hname =
									"<a href=\"javascript:the_atable['" +
									this.id +
									"'].sorts('" +
									key +
									"')\">" +
									iconsort +
									key +
									"</a>";
								if (this.p.sort != undefined) {
									if (this.p.sort[nocol] == "F") {
										hname = key;
									}
								}
								var trparam = "";
								if (calign != "" || tcolhide != "") {
									trparam = ' style="' + calign + tcolhide + '"';
								}
								headerTr$.append($("<th" + trparam + csize + "/>").html(hname));
								this.lblcol[nocol] = hname;
								nocol++;
							}
						}
					}
				}
			}
		} else {
			if (this.p.colv == undefined) {
				for (var i = 0; i < this.columnSet.length; i++) {
					if (this.sortfield == this.columnSet[i]) {
						if (this.sorttype == "asc") {
							iconsort =
								'<span class="icon-chevron-down" aria-hidden="true"></span>&nbsp;';
						} else {
							iconsort =
								'<span class="icon-chevron-up" aria-hidden="true"></span>&nbsp;';
						}
					} else {
						iconsort = "";
					}
					var csize = "";
					if (this.p.colsize != undefined) {
						if (
							this.p.colsize[nocol] != "" &&
							this.p.colsize[nocol] != undefined
						) {
							csize = ' width="' + this.p.colsize[nocol] + '"';
						}
					}
					var calign = "";
					if (this.p.colalign != undefined) {
						if (this.p.colalign[nocol] != "") {
							calign =
								"text-align:" +
								(this.p.colalign[nocol] == "R"
									? "right"
									: this.p.colalign[nocol] == "C"
									? "center"
									: "left") +
								";";
						}
					}
					var chide = false;
					if (this.p.colhide != undefined) {
						if (this.p.colhide.indexOf(nocol) > -1) {
							chide = true;
						}
					}
					var tcolhide = "";
					if (chide) {
						tcolhide = "display:none;";
					}

					var hname =
						"<a href=\"javascript:the_atable['" +
						this.id +
						"'].sorts('" +
						this.columnSet[i] +
						"')\">" +
						iconsort +
						this.columnSet[i] +
						"</a>";
					if (this.p.sort != undefined) {
						if (this.p.sort[nocol] == "F") {
							hname = this.columnSet[i];
						}
					}
					var trparam = "";
					if (calign != "" || tcolhide != "" || csize != "") {
						trparam = ' style="' + calign + tcolhide + '"';
					}
					headerTr$.append($("<th" + trparam + csize + "/>").html(hname));
					this.lblcol[nocol] = hname;
					nocol++;
				}
			}
		}

		iconsort = "";
		if (this.p.colv != undefined) {
			for (var i = 0; i < this.p.colv.length; i++) {
				if (this.sortfield == this.columnSet[i]) {
					if (this.sorttype == "asc") {
						iconsort =
							'<span class="icon-chevron-down" aria-hidden="true"></span>&nbsp;';
					} else {
						iconsort =
							'<span class="icon-chevron-up" aria-hidden="true"></span>&nbsp;';
					}
				} else {
					iconsort = "";
				}
				var csize = "";
				if (this.p.colsize != undefined) {
					if (this.p.colsize[i] != "" && this.p.colsize[i] != undefined) {
						csize = ' width="' + this.p.colsize[i] + '"';
					}
				}
				var calign = "";
				if (this.p.colalign != undefined) {
					if (this.p.colalign[i] != "") {
						calign =
							"text-align:" +
							(this.p.colalign[i] == "R"
								? "right"
								: this.p.colalign[i] == "C"
								? "center"
								: "left") +
							";";
					}
				}
				var chide = false;
				if (this.p.colhide != undefined) {
					if (this.p.colhide.indexOf(i) > -1) {
						chide = true;
					}
				}
				var tcolhide = "";
				if (chide) {
					tcolhide = "display:none;";
				}

				var hname =
					"<a href=\"javascript:the_atable['" +
					this.id +
					"'].sorts('" +
					this.columnSet[i] +
					"')\">" +
					iconsort +
					this.p.colv[i] +
					"</a>";
				if (this.p.sort != undefined) {
					if (this.p.sort[i] == "F") {
						hname = this.p.colv[i];
					}
				}
				var trparam = "";
				if (calign != "" || tcolhide != "" || csize != "") {
					trparam = ' style="' + calign + tcolhide + '"';
				}
				headerTr$.append($("<th" + trparam + csize + "/>").html(hname));
				this.lblcol[i] = hname;
			}
		}
		tHead$.append(headerTr$);
		$("#" + this.tableId).append(tHead$);
		this.tbody();
	};
	tdata.prototype.tbody = function() {
		var maxnum = 0;
		var collabel = [];
		$("#" + this.tableId + " > tbody").remove();
		var rowbody$ = $("<tbody/>");
		if ((this.pagen - 1) * this.dtlimit + this.dtlimit > this.tbdata.length) {
			maxnum = this.tbdata.length;
		} else {
			maxnum = (this.pagen - 1) * this.dtlimit + this.dtlimit;
		}

		collabel = this.columnSet;
		if (this.p.colv != undefined) {
			collabel = this.p.colv;
		}
		var no = 1;
		for (var i = (this.pagen - 1) * this.dtlimit; i < maxnum; i++) {
			var row$ = $("<tr/>");
			if (this.tbdata[i]["subtitle"] !== undefined) {
				row$.append(
					$(
						'<td colspan="' +
							(this.columnSet.length + (this.p.colnumber ? 1 : 0)) +
							'"/>'
					).html(this.tbdata[i]["subtitle"])
				);
			} else {
				if (this.p.colnumber) {
					row$.append($('<td data-label="No"/>').html(no));
				}
				for (var colIndex = 0; colIndex < this.columnSet.length; colIndex++) {
					var cellValue = this.tbdata[i][this.columnSet[colIndex]];
					if (cellValue == null || cellValue == undefined || cellValue == "") {
						cellValue = "&nbsp;";
					}
					var chide = false;
					if (this.p.colhide != undefined) {
						if (this.p.colhide.indexOf(colIndex) > -1) {
							chide = true;
						}
					}
					var calign = "";
					if (this.p.colalign != undefined) {
						if (this.p.colalign[colIndex] != "") {
							calign =
								"text-align:" +
								(this.p.colalign[colIndex] == "R"
									? "right"
									: this.p.colalign[colIndex] == "C"
									? "center"
									: "left") +
								";";
						}
					}
					var tcolhide = "";
					var trparam = "";
					if (chide) {
						tcolhide = "display:none;";
					}
					if (calign != "" || tcolhide != "") {
						trparam = ' style="' + calign + tcolhide + '"';
					}
					row$.append(
						$(
							'<td data-label="' + collabel[colIndex] + '"' + trparam + "/>"
						).html(
							typeof cellValue == "object"
								? JSON.stringify(cellValue)
								: cellValue
						)
					);
				}
				no++;
			}

			// add column
			if (this.p.addcol != undefined) {
				for (var ac = 0; ac < this.p.addcol.length; ac++) {
					var calign = "";
					if (this.p.colalign != undefined) {
						if (this.p.colalign[colIndex + ac] != "") {
							calign =
								' style="text-align:' +
								(this.p.colalign[colIndex + ac] == "R"
									? "right"
									: this.p.colalign[colIndex + ac] == "C"
									? "center"
									: "left") +
								';"';
						}
					}
					row$.append(
						$(
							'<td data-label="' + this.p.addcol[ac][0] + '"' + calign + "/>"
						).html(this.p.addcol[ac][1])
					);
				}
			}

			rowbody$.append(row$);

			if (this.tbdata[i]["foottitle"] !== undefined) {
				var row$ = $("<tr/>");
				row$.append(
					$(
						'<td colspan="' +
							(this.columnSet.length + (this.p.colnumber ? 1 : 0)) +
							'"/>'
					).html(this.tbdata[i]["foottitle"])
				);
				rowbody$.append(row$);
			}
		}
		$("#" + this.tableId).append(rowbody$);

		var showpg = "";
		var pgclass = "";
		this.numpage = Math.ceil(this.tbdata.length / this.dtlimit);
		var pagination = '<div class="paggingfield"><ul class="pagination">';
		if (this.pagen > 1) {
			pagination +=
				"<li " +
				pgclass +
				"><a href=\"javascript:the_atable['" +
				this.id +
				"'].move(" +
				this.pagen +
				'-1);" id="' +
				(this.pagen - 1) +
				'" class="jpages">&laquo;</a></li>';
		}
		for (var page = 1; page <= this.numpage; page++) {
			pgclass =
				page == this.pagen ? (pgclass = 'class="active"') : (pgclass = "");
			if (
				(page >= this.pagen - 2 && page <= this.pagen + 2) ||
				page == 1 ||
				page == this.numpage
			) {
				if (showpg == 1 && page != 2) {
					pagination +=
						'<li><a href="javascript:void(0);" class="gapdot">...</a></li>';
				}
				if (showpg != this.numpage - 1 && page == this.numpage) {
					pagination +=
						'<li><a href="javascript:void(0);" class="gapdot">...</a></li>';
				}
				if (page == this.pagen) {
					pagination +=
						"<li " +
						pgclass +
						'><a href="javascript:void(0);" id="' +
						page +
						'" onclick="">' +
						page +
						"</a></li>";
				} else {
					pagination +=
						"<li " +
						pgclass +
						"><a href=\"javascript:the_atable['" +
						this.id +
						"'].move(" +
						page +
						');" id="' +
						page +
						'" class="jpages" onclick="">' +
						page +
						"</a></li>";
				}
				showpg = page;
			}
		}
		if (this.pagen < this.numpage) {
			pagination +=
				"<li " +
				pgclass +
				"><a href=\"javascript:the_atable['" +
				this.id +
				"'].move(" +
				this.pagen +
				'+1);" id="' +
				this.pagen +
				'" class="jpages">&raquo;</a></li>';
		}
		pagination += "</ul></div>";

		var colhide =
			'<div class="colhide" id="colhide' +
			this.tableId +
			'"><div style="margin-bottom:6px;"><select multiple="multiple" style="width:250px;min-height:83px;max-height:120px;" id="slctmltp' +
			this.tableId +
			'" class="form-control">';
		if (this.p.colnumber) {
			colhide = colhide + '<option value="0" selected="selected">No</option>';
		}
		for (var nk = 0; nk < this.lblcol.length; nk++) {
			colhide =
				colhide +
				'<option value="' +
				(this.p.colnumber ? nk + 1 : nk) +
				'" selected="selected">' +
				this.lblcol[nk] +
				"</option>";
		}

		colhide =
			colhide +
			'</select></div><button type="button" class="btn btn-default btn-sm" id="colhidecancel" style="float:right" onclick="the_atable[\'' +
			this.id +
			"'].showhide('colhide" +
			this.tableId +
			'\');">Cancel</button><button type="button" onclick="the_atable[\'' +
			this.id +
			"'].atable_hidecol('" +
			this.tableId +
			"',the_atable['" +
			this.id +
			"'].getSelectMultiValues('slctmltp" +
			this.tableId +
			"'),'" +
			this.tableId +
			"');the_atable['" +
			this.id +
			"'].showhide('colhide" +
			this.tableId +
			'\');" class="btn btn-default btn-sm" id="colhideok" style="float:right">Ok</button></div>';

		var btncollist = "";
		var btnxls = "";
		if (this.collist) {
			btncollist =
				'<button type="button" onclick="the_atable[\'' +
				this.id +
				"'].showhide('colhide" +
				this.tableId +
				'\');" class="btn btn-default btn-xs" title="Column" id="dtlist" style="font-size:18px;height:30px;">&#8862;</button>&nbsp;';
		}
		if (this.xls) {
			var titlexls = "Data_export";
			if (this.p.caption != undefined) {
				titlexls = this.p.caption.replace(/ /g, "_");
			}

			btnxls =
				'<button type="button" onclick="atable_toexcel(\'' +
				this.tableId +
				"','" +
				titlexls +
				'\')" class="btn btn-success btn-sm" title="Export to Excel" id="dtxls">xls</button>&nbsp;';
		}

		$("#info" + this.tableId).html(
			colhide +
				'<div class="datainfo">' +
				btncollist +
				btnxls +
				((this.pagen - 1) * this.dtlimit + 1) +
				" to " +
				maxnum +
				" of " +
				this.tbdata.length +
				" data&nbsp;&nbsp;<a href=\"javascript:the_atable['" +
				this.id +
				'\'].showall();" id="jshowall" class="jshowall">Show All</a><a href="javascript:the_atable[\'' +
				this.id +
				'\'].showaless();" id="jshowless" class="jshowless" style="display:none;">Show Less</a></div>' +
				pagination
		);

		this.atable_hidecol(this.tableId, this.colshowhide, this.tableId);
	};
	tdata.prototype.searching = function(c) {
		this.search($("#" + c.id).val());
	};
	tdata.prototype.search = function(q = "") {
		var threshold = 1;
		if (this.p.data.length > 500) {
			threshold = 3;
		}
		if (q.length >= threshold || q.length == 0) {
			var mtc = q.match(/\"/g) ? q.match(/"/g).length : 0;
			if (mtc == 2) {
				q = q.toLowerCase().replace(/"/g, "");
			} else {
				q = q.replace(/ /g, ".*").toLowerCase();
			}

			var re = new RegExp(q, "g");
			var filter = [];
			for (var i = 0; i < this.p.data.length; i++) {
				var rowobj = this.p.data[i];
				var row = Object.keys(rowobj)
					.map(function(key) {
						return rowobj[key];
					})
					.join();
				row = row.toLowerCase().replace(/,/g, " ");
				if (Boolean(row.match(re))) {
					filter.push(this.p.data[i]);
				}
			}

			if (q != "") {
				this.pagen = 1;
				this.tbdata = filter;
				this.dtlimit = filter.length;
			} else {
				this.pagen = 1;
				this.tbdata = this.p.data;
				this.dtlimit = this.p.limit;
			}
			this.tbody();
		}
	};

	tdata.prototype.clearsrc = function(natbl) {
		$("#txtfind-" + natbl).val("");
		$("#txtfind-" + natbl).keyup();
		$("#txtfind-" + natbl).focus();
	};

	tdata.prototype.sort_by = function(field, ascdsc) {
		var reverse = false;
		if (ascdsc == "desc") {
			var reverse = true;
		}
		var primer = function(a) {
			a = a === undefined || a == null ? " " : a;
			return a === parseInt(a, 10) ? parseInt(a) : a.toUpperCase();
		};
		var key = primer
			? function(x) {
					return primer(x[field]);
			  }
			: function(x) {
					return x[field];
			  };
		reverse = !reverse ? 1 : -1;
		return function(a, b) {
			return (a = key(a)), (b = key(b)), reverse * ((a > b) - (b > a));
		};
	};
	tdata.prototype.sorts = function(field) {
		this.sortfield = field;
		if (this.sorttype == "") {
			this.sorttype = "asc";
		} else if (this.sorttype == "asc") {
			this.sorttype = "desc";
		} else if (this.sorttype == "desc") {
			this.sorttype = "asc";
		}

		if (field != undefined && field != "") {
			this.thead();
			this.tbdata.sort(this.sort_by(this.sortfield, this.sorttype));
		}
	};

	tdata.prototype.showall = function() {
		var load = true;
		if (this.p.data.length > 500) {
			load = confirm("Load data?");
		}
		if (load) {
			this.pagen = 1;
			this.tbdata = this.p.data;
			this.dtlimit = this.tbdata.length;
			this.tbody();
			$("#info" + this.tableId + " > .datainfo > #jshowall").css(
				"display",
				"none"
			);
			$("#info" + this.tableId + " > .datainfo > #jshowless").css(
				"display",
				"inline-block"
			);
		}
	};
	tdata.prototype.showaless = function() {
		if (this.p.limit == undefined) {
			this.dtlimit = 10;
			if (this.p.data.length < this.dtlimit) {
				this.dtlimit = this.p.data.length;
			}
		} else {
			this.dtlimit = this.p.limit;
		}
		this.tbody();
		$("#info" + this.tableId + " > .datainfo > #jshowless").css(
			"display",
			"none"
		);
		$("#info" + this.tableId + " > .datainfo > #jshowall").css(
			"display",
			"inline-block"
		);
	};

	tdata.prototype.move = function(n) {
		this.pagen = n;
		this.tbody();
	};

	tdata.prototype.atable_hidecol = function(tblid, arcol, atablenum = "") {
		var cols = arcol;
		if (cols.length < 0) {
			console.log("Invalid");
			return;
		} else {
			var tbl = document.getElementById(tblid);
			var slctmltp = document.getElementById("slctmltp" + atablenum);
			if (tbl != null) {
				for (var i = 0; i < tbl.rows.length; i++) {
					var ncc = 0;
					for (var j = 0; j < tbl.rows[i].cells.length; j++) {
						tbl.rows[i].cells[j].style.display = "";
						colspan = tbl.rows[i].cells[j].getAttribute("colspan");
						if (colspan > 0) {
							ncc = ncc + parseInt(colspan) - 1;
						}

						slctmltp.options[ncc].selected = true;
						if (this.p.colhide != undefined) {
							if (this.p.colhide.indexOf(ncc) > -1) {
								slctmltp.options[ncc].selected = false;
								cols.push(ncc);
							}
						}
						if (cols.includes(ncc)) {
							tbl.rows[i].cells[j].style.display = "none";
							slctmltp.options[ncc].selected = false;
						}
						ncc++;
					}
				}
			}
		}
		this.colshowhide = arcol;
	};
	tdata.prototype.getSelectMultiValues = function(select) {
		var result = [];
		var options = document.getElementById(select);
		for (var i = 0, iLen = options.length; i < iLen; i++) {
			if (!options[i].selected) {
				result.push(
					parseInt(options[i].value) || parseInt(options[i].text) || 0
				);
			}
		}
		return result;
	};
	tdata.prototype.showhide = function(meid = "") {
		var tag = document.getElementById(meid);
		if (tag.style.display === "block") {
			tag.style.display = "none";
		} else {
			tag.style.display = "block";
		}
	};

	tdata.prototype.datarow = function(tr) {
		var rows = [];
		$(tr)
			.parents("tr")
			.each(function(i) {
				$("td", this).each(function(j) {
					rows.push($(this).html());
				});
			});
		return rows;
	};
	return { tdata: tdata };
})();

function atable_toexcel(tableID = "", filename = "") {
	var downloadLink;
	var dataType = "application/vnd.ms-excel";
	var tableSelect = document.getElementById(tableID);
	var tableHTML = remHiddenTag(tableSelect.outerHTML, "none")
		.replace(/ /g, "%20")
		.replace(/<\/?a[^>]*>/g, "")
		.replace('border="0"', 'border="1"');
	filename = filename ? filename + ".xls" : "excel_data.xls";
	downloadLink = document.createElement("a");

	document.body.appendChild(downloadLink);

	if (navigator.msSaveOrOpenBlob) {
		var blob = new Blob(["\ufeff", tableHTML], {
			type: dataType
		});
		navigator.msSaveOrOpenBlob(blob, filename);
	} else {
		downloadLink.href = "data:" + dataType + ", " + tableHTML;
		downloadLink.onclick = atabledestroyClickedElement;
		downloadLink.download = filename;
		downloadLink.click();
	}
}

function atabledestroyClickedElement(event) {
	document.body.removeChild(event.target);
}

function remHiddenTag(html, match) {
	var container = document.createElement("span");
	container.innerHTML = html;
	Array.from(
		container.querySelectorAll("[style*=" + CSS.escape(match) + "]")
	).forEach(link => link.parentNode.removeChild(link));
	return container.innerHTML;
}

var the_atable = [];
function atable_build(table, param) {
	the_atable[table] = new aTable.table.tdata(
		"tdata[" + table + "]",
		table,
		param
	);
}
// END JSON aTable ==============================================================
