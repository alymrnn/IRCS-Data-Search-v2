<?php
include 'conn_ircs.php';

$method = $_POST['method'];

function count_ircs_list($conn_ircs, $start_date, $end_date)
{
    $date_from = str_replace("T", " ", $start_date);
    $date_to = str_replace("T", " ", $end_date);

    $query = "
        SELECT COUNT(*)
        FROM (
            SELECT PARTSNAME, LOT
            FROM T_PACKINGWK
            WHERE REGISTDATETIME BETWEEN TO_DATE(:date_from, 'YYYY-MM-DD HH24:MI:SS') 
                                    AND TO_DATE(:date_to, 'YYYY-MM-DD HH24:MI:SS')
            GROUP BY PARTSNAME, LOT
        )
    ";

    try {
        $stmt = oci_parse($conn_ircs, $query);

        oci_bind_by_name($stmt, ':date_from', $date_from);
        oci_bind_by_name($stmt, ':date_to', $date_to);

        oci_execute($stmt);

        $total = 0;
        if ($row = oci_fetch_assoc($stmt)) {
            $total = $row['COUNT(*)']; 
        }

        oci_free_statement($stmt);

    } catch (Exception $e) {
        echo 'Query failed: ' . $e->getMessage();
        $total = 0;
    }
    return $total;
}


if ($method == 'count_ircs_list') {
    $start_date = isset($_POST['search_date_from']) ? $_POST['search_date_from'] : '';
    $end_date = isset($_POST['search_date_to']) ? $_POST['search_date_to'] : '';

    echo count_ircs_list($conn_ircs, $start_date, $end_date);
}

if ($method == 'ircs_list_pagination') {
    $start_date = isset($_POST['search_date_from']) ? $_POST['search_date_from'] : '';
    $end_date = isset($_POST['search_date_to']) ? $_POST['search_date_to'] : '';

    $results_per_page = 1000;

    $number_of_result = intval(count_ircs_list($conn_ircs, $start_date, $end_date));

    $number_of_page = ceil($number_of_result / $results_per_page);

    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<option value="' . $page . '">' . $page . '</option>';
    }
}

if ($method == 'ircs_list_last_page') {
    $start_date = isset($_POST['search_date_from']) ? $_POST['search_date_from'] : '';
    $end_date = isset($_POST['search_date_to']) ? $_POST['search_date_to'] : '';

    $results_per_page = 1000;
    $number_of_result = intval(count_ircs_list($conn_ircs, $start_date, $end_date));

    $number_of_page = ceil($number_of_result / $results_per_page);

    echo $number_of_page;
}


if ($method == 'search_ircs_data_count') {
    $start_date = isset($_POST['search_date_from']) ? $_POST['search_date_from'] : '';
    $end_date = isset($_POST['search_date_to']) ? $_POST['search_date_to'] : '';

    $date_from = str_replace("T", " ", $start_date);
    $date_to = str_replace("T", " ", $end_date);

    $current_page = isset($_POST['current_page']) ? max(1, intval($_POST['current_page'])) : 1;
    $results_per_page = 1000;
    $page_first_result = ($current_page - 1) * $results_per_page;
    $page_last_result = $page_first_result + $results_per_page;

    $query = "
        SELECT * FROM (
            SELECT PARTSNAME, LOT, COUNT(*) AS IRCS_COUNT,
                   ROW_NUMBER() OVER (ORDER BY PARTSNAME, LOT) AS rn
            FROM T_PACKINGWK
            WHERE REGISTDATETIME BETWEEN TO_DATE(:date_from, 'YYYY-MM-DD HH24:MI:SS') 
                                    AND TO_DATE(:date_to, 'YYYY-MM-DD HH24:MI:SS')
            GROUP BY PARTSNAME, LOT
        ) WHERE rn > :page_first_result AND rn <= :page_last_result
    ";

    try {
        $stmt = oci_parse($conn_ircs, $query);

        oci_bind_by_name($stmt, ':date_from', $date_from);
        oci_bind_by_name($stmt, ':date_to', $date_to);
        oci_bind_by_name($stmt, ':page_first_result', $page_first_result, -1, SQLT_INT);
        oci_bind_by_name($stmt, ':page_last_result', $page_last_result, -1, SQLT_INT);

        oci_execute($stmt);

        $c = $page_first_result + 1; 
        if ($row = oci_fetch_assoc($stmt)) {
            oci_execute($stmt);

            while ($row = oci_fetch_assoc($stmt)) {
                echo '<tr>';
                echo '<td style="text-align:center;width:25%;">' . $c . '</td>';
                echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['PARTSNAME']) . '</td>';
                echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['LOT']) . '</td>';
                echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['IRCS_COUNT']) . '</td>';
                echo '</tr>';
                $c++;
            }
        } else {
            echo '<tr>';
            echo '<td colspan="4" style="text-align:center; color:red;">No Record Found</td>';
            echo '</tr>';
        }

        oci_free_statement($stmt);

    } catch (Exception $e) {
        echo 'Query failed: ' . $e->getMessage();
    }

    oci_close($conn_ircs);
}



// if ($method == 'load_defect_list') {
//     $start_date = '2024-06-01T06:00:00';
//     $end_date = '2024-08-22T05:59:59';

//     $date_from = str_replace("T", " ", $start_date);
//     $date_to = str_replace("T", " ", $end_date);

//     $query = "
//         SELECT PARTSNAME, LOT, COUNT(*) AS IRCS_COUNT
//         FROM T_PACKINGWK
//         WHERE REGISTDATETIME BETWEEN TO_DATE('$date_from', 'YYYY-MM-DD HH24:MI:SS') 
//                                 AND TO_DATE('$date_to', 'YYYY-MM-DD HH24:MI:SS')
//         GROUP BY PARTSNAME, LOT
//         FETCH FIRST 50 ROWS ONLY
//     ";

//     try {
//         $stmt = oci_parse($conn_ircs, $query);
//         oci_execute($stmt);

//         $c = 1;
//         if (oci_fetch_assoc($stmt)) {
//             oci_execute($stmt);
//             while ($row = oci_fetch_assoc($stmt)) {
//                 echo '<tr>';
//                 echo '<td style="text-align:center;width:25%;">' . $c . '</td>';
//                 echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['PARTSNAME']) . '</td>';
//                 echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['LOT']) . '</td>';
//                 echo '<td style="text-align:center;width:25%;">' . htmlspecialchars($row['IRCS_COUNT']) . '</td>';
//                 echo '</tr>';
//                 $c++;
//             }
//         } else {
//             echo '<tr>';
//             echo '<td colspan="4" style="text-align:center; color:red;">No Record Found</td>';
//             echo '</tr>';
//         }

//         oci_free_statement($stmt);

//     } catch (Exception $e) {
//         echo 'Query failed: ' . $e->getMessage();
//     }

//     // Close the connection
//     oci_close($conn_ircs);
// }


?>