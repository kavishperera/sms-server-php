<?php

include('./app/db.php');
$action = "";
$action = $_GET['api_key'];

if ($action != "") {
    $sql = "
    select 
        ifnull(sum(t_client_leger.debit_amount),0) - ifnull(sum(t_client_leger.cradit_amount),0) as debit_amount
    from 
        m_client
    left join 
        t_client_leger     
    on 
        m_client.index_no = t_client_leger.client
    where
        m_client.api_key = " . $action;
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row["debit_amount"];
        }
    } else {
        echo "0";
    }
    mysqli_close($conn);
} else {
    echo 'api not found';
}
?>
