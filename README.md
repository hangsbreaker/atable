# aTable
Responsive Ajax data table with php<br>
This atable is automatic creating ajax table that data get from database<br>
(Automatic Pagination, Sorting and Searching)<br>
The atable can include parameter from outside variable<br>
Support database (mysql, mysqli, pgsql) and joining table

# Initial atable
atable_init();

# Using
$atable = new Atable();<br>
$atable->limit&emsp;&emsp;&emsp;&emsp;// number limit data select (default 10)<br>
$atable->limitfind&emsp;&emsp;&ensp;// number limit data after find (default 300)<br>
$atable->query&emsp;&emsp;&emsp;&nbsp;// sql query string parameter (required)<br>
$atable->where&emsp;&emsp;&emsp;// where string after query parameter (default empty)<br>
$atable->orderby&emsp;&emsp;&ensp;// order by string after query parameter (default empty)<br>
$atable->groupby&emsp;&emsp;&nbsp;// group by string after query parameter (default empty)<br>
$atable->col&emsp;&emsp;&emsp;&emsp;&ensp;// column name table in database (required)<br>
$atable->colv&emsp;&emsp;&emsp;&emsp;// column name view (visible in webpage as table column header) (required)<br>
$atable->param&emsp;&emsp;&emsp;// php string inside one single quote ('') will be eval to atable<br>
$atable->colnumber&emsp;// show/hide column number boolean (default TRUE)<br>
$atable->colsize&emsp;&emsp;&ensp;&nbsp;// column size of table as style (default empty)<br>
$atable->colalign&emsp;&emsp;&ensp;// column align table as style L(left),R(right),C(center) (default align left)<br>
$atable->showsql&emsp;&emsp;&nbsp;// show query result boolean (default FALSE)<br>
$atable->style&emsp;&emsp;&emsp;&ensp;&nbsp;// modify table css class (default using bootstrap 3)<br>
$atable->caption&emsp;&emsp;&ensp;// title of table (default empty)<br>
$atable->reload=TRUE;<br>
$atable->datainfo=FALSE;<br>
$atable->paging=FALSE;<br>
$atable->debug=FALSE;<br>
<br>
echo $atable->load();<br>
<br>Tips:<br>
- For search can use quotation. eg: "build 23"<br>
- Use table column as variable in param then column can be sort<br>

# Example
// need jquery<br>
require atable.php // include or require the atable script<br>
// ======================================================================================<br>
// creating ajax atable<br>
$atablea = new Atable();<br>
$atablea->limit = 10;<br>
$atablea->query = "SELECT m.nrp, m.nama, j.kode_jurusan, j.nama_jurusan, m.tahun_masuk FROM mahasiswa m, jurusan j";<br>
$atablea->orderby = "m.tahun_masuk DESC, m.nama ASC";<br>
$atablea->where = "m.kode_jurusan=j.kode_jurusan and m.tahun_masuk='$tahun'";<br>
$atablea->col = "['nrp', 'nama', 'jurusan', 'tahun_masuk', '$td;']";<br>
$atablea->colv = "['NRP', 'NAMA', 'JURUSAN', 'TH MASUK', 'TES']";<br>
$atablea->colsize = "['100px', '', '','90px']";<br>
$atablea->tdalign = "['R', 'L', 'L','R','L']";<br>
$atablea->style = 'table table-hover';<br><br>

$atablea->param = '$td="From param var";';<br><br>

echo $atablea->load();<br><br>

// ======================================================================================<br>
// creating Second ajax atable<br>
$atableb = new Atable();<br>
$atableb->limit = 5;<br>
$atableb->query = "SELECT * FROM mahasiswa";<br>
$atableb->col = "['nrp', 'nama', 'kelamin']";<br>
$atableb->colv = "['NRP', 'NAMA', 'JK']";<br>
$atableb->colsize = "['110px', '', '20px']";<br>
$atableb->showsql = TRUE;<br><br>

echo $atableb->load();<br><br>


/** NOTE:<br>
** Please asign $_POST->databases before call atable.php if the Atable Unkown Database<br>
** Example:<br>
** $_POST->databases='mysql'; // for mysql database<br>
** $_POST->databases='mysqli'; // for mysqli database<br>
** $_POST->databases='pgsql'; // for pgsql database<br>
** ===================================================<br>
** $_POST["toatable"]=TRUE; // add this for send parameter to atable<br>
**/
