<?php
include 'conn_ircs.php';
ini_set("memory_limit", "-1");


$start_date = $_GET['search_date_from'];
$end_date = $_GET['search_date_to'];

$date_from = str_replace("T", " ", $start_date);
$date_to = str_replace("T", " ", $end_date);

$filename = 'IRCS-Data-Count-as-of_' . date("Y-m-d") . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');

$f = fopen('php://memory', 'w');
fputs($f, "\xEF\xBB\xBF");

$delimiter = ',';

$headers = array(
    '#',
    'Parts Name',
    'Lot No',
    'IRCS Data Count'
);

fputcsv($f, $headers, $delimiter);

$query = "
    SELECT PARTSNAME, LOT, COUNT(*) AS IRCS_COUNT
    FROM T_PACKINGWK
    WHERE REGISTDATETIME BETWEEN TO_DATE(:date_from, 'YYYY-MM-DD HH24:MI:SS') 
    AND TO_DATE(:date_to, 'YYYY-MM-DD HH24:MI:SS')
    GROUP BY PARTSNAME, LOT
";

$stmt = oci_parse($conn_ircs, $query);
oci_bind_by_name($stmt, ':date_from', $date_from);
oci_bind_by_name($stmt, ':date_to', $date_to);
oci_execute($stmt);

$c = 1;
while ($row = oci_fetch_assoc($stmt)) {
    fputcsv($f, array(
        $c,
        htmlspecialchars($row['PARTSNAME']),
        htmlspecialchars($row['LOT']),
        htmlspecialchars($row['IRCS_COUNT'])
    )
    );
    $c++;
}

if ($c === 1) {
    fputcsv($f, array('NO RECORD FOUND'));
}

fseek($f, 0);
fpassthru($f);

$conn_ircs = null;

?>