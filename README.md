# atable
Ajax data table with php<br>
This atable is automatic creating ajax table that data get from database
(Automatic Pagination, Sorting and Searching)
The atable can include parameter from outside variable
Support join table

# Using
$atable['limit']      // number limit data select (default 10)
$atable['limitfind']  // number limit data after find (default 300)
$atable['query']      // sql query string parameter (required)
$atable['where']      // where string after query parameter (default empty)
$atable['orderby']    // order by string after query parameter (default empty)
$atable['groupby']    // group by string after query parameter (default empty)
$atable['col']        // column name table in database (required)
$atable['colv']       // column name view (visible in webpage as table column header) (required)
$atable['param']      // php string inside one single quote ('') will be eval to atable
$atable['colsize']    // column size of table as style (default empty)
$atable['tdalign']    // column align table as style (default align left)
$atable['showsql']    // show query result
$atable['style']      // modify table style (default using bootstrap 3)

# Example
// ======================================================================================
// creating ajax atable
$atablea['limit'] = 10;
$atablea['query'] = "SELECT m.nrp, m.nama, j.kode_jurusan, j.nama_jurusan, m.tahun_masuk FROM mahasiswa m, jurusan j";
$atablea['orderby'] = "m.tahun_masuk DESC, m.nama ASC";
$atablea['where'] = "m.kode_jurusan=j.kode_jurusan and m.tahun_masuk='$tahun'";
$atablea['col'] = array('nrp', 'nama', 'jurusan', 'tahun_masuk', '$td;');
$atablea['colv'] = array('NRP', 'NAMA', 'JURUSAN', 'TH MASUK', 'TES');
$atablea['colsize'] = array('100px', '', '','90px');
$atablea['tdalign'] = array('R', 'L', 'L','R','L');
$atablea['style'] = 'table table-hover';

$atablea['param'] = '$td="From param var";';

$table1 = the_atable($atablea);

// ======================================================================================
// creating Second ajax atable
$atableb['limit'] = 5;
$atableb['query'] = "SELECT * FROM mahasiswa";
$atableb['col'] = array('nrp', 'nama', 'kelamin');
$atableb['colv'] = array('NRP', 'NAMA', 'JK');
$atableb['colsize'] = array('110px', '', '20px');
$atableb['showsql'] = TRUE;

$table2 = the_atable($atableb);

// ===== Write Atable
echo $table1;
echo $table2;
