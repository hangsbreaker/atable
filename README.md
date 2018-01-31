# atable
Ajax data table with php<br>
This atable is automatic creating ajax table that data get from database<br>
(Automatic Pagination, Sorting and Searching)<br>
The atable can include parameter from outside variable<br>
Support join table

# Using
$atable['limit']      // number limit data select (default 10)<br>
$atable['limitfind']  // number limit data after find (default 300)<br>
$atable['query']      // sql query string parameter (required)<br>
$atable['where']      // where string after query parameter (default empty)<br>
$atable['orderby']    // order by string after query parameter (default empty)<br>
$atable['groupby']    // group by string after query parameter (default empty)<br>
$atable['col']        // column name table in database (required)<br>
$atable['colv']       // column name view (visible in webpage as table column header) (required)<br>
$atable['param']      // php string inside one single quote ('') will be eval to atable<br>
$atable['colsize']    // column size of table as style (default empty)<br>
$atable['tdalign']    // column align table as style (default align left)<br>
$atable['showsql']    // show query result<br>
$atable['style']      // modify table style (default using bootstrap 3)<br>

# Example
// ======================================================================================<br>
// creating ajax atable<br>
$atablea['limit'] = 10;<br>
$atablea['query'] = "SELECT m.nrp, m.nama, j.kode_jurusan, j.nama_jurusan, m.tahun_masuk FROM mahasiswa m, jurusan j";<br>
$atablea['orderby'] = "m.tahun_masuk DESC, m.nama ASC";<br>
$atablea['where'] = "m.kode_jurusan=j.kode_jurusan and m.tahun_masuk='$tahun'";<br>
$atablea['col'] = array('nrp', 'nama', 'jurusan', 'tahun_masuk', '$td;');<br>
$atablea['colv'] = array('NRP', 'NAMA', 'JURUSAN', 'TH MASUK', 'TES');<br>
$atablea['colsize'] = array('100px', '', '','90px');<br>
$atablea['tdalign'] = array('R', 'L', 'L','R','L');<br>
$atablea['style'] = 'table table-hover';<br><br>

$atablea['param'] = '$td="From param var";';<br><br>

$table1 = the_atable($atablea);<br><br>

// ======================================================================================<br>
// creating Second ajax atable<br>
$atableb['limit'] = 5;<br>
$atableb['query'] = "SELECT * FROM mahasiswa";<br>
$atableb['col'] = array('nrp', 'nama', 'kelamin');<br>
$atableb['colv'] = array('NRP', 'NAMA', 'JK');<br>
$atableb['colsize'] = array('110px', '', '20px');<br>
$atableb['showsql'] = TRUE;<br><br>

$table2 = the_atable($atableb);<br><br>

// ===== Write Atable<br>
echo $table1;<br>
echo $table2;<br>
