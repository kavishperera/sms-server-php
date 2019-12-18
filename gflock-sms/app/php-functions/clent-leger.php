<?php

include('../db.php');

$action = $_GET['action'];
//get client leger history
if ($action == 'f1') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $sql = "select 
            cast(ifnull(sum(debit_amount),0.0) as decimal(10,2)) - cast(ifnull(sum(cradit_amount),0.0) as decimal(10,2)) as credit_amount
        from 
            t_client_leger where client = " . $request;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row["credit_amount"];
        }
    } else {
        echo "0";
    }
    $conn->close();
} else if ($action == 'f2') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $sql = "select * from t_client_leger where cradit_amount is null and client = " . $request;
    $data = array();
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "0 results";
    }
    echo json_encode($data);
    mysqli_close($conn);

    //client leger add debit amount
} else if ($action == 'f3') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $index_no = $request->index_no;
    $date = $request->date;
    $client = $request->client;
    $debit_amount = $request->debit_amount;

    $sql = "INSERT INTO t_client_leger (`date`, `client`, `debit_amount`,`trasaction_type`) VALUES ('$date', '$client', '$debit_amount','RELOAD SMS');";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo $last_id;
    } else {
        echo("Error description: " . mysqli_error($con));
    }

    $conn->close();
} else if ($action == 'f4') {
    $sql = "select * from t_client_leger where cradit_amount is null";
    $data = array();
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "0 results";
    }
    echo json_encode($data);
    mysqli_close($conn);
} else if ($action == 'f5') {
    $sql = "
    select 
	m_client.index_no as client,
        ifnull(sum(t_client_leger.debit_amount),0) - ifnull(sum(t_client_leger.cradit_amount),0) as debit_amount
    from 
        m_client
    left join 
        t_client_leger     
    on 
        m_client.index_no = t_client_leger.client
    group by
        m_client.index_no";
    $data = array();
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "0 results";
    }
    echo json_encode($data);
    mysqli_close($conn);
} else if ($action == "f6") {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $sql = "select 
    cast(ifnull(sum(debit_amount),0.0) as decimal(10,2)) as debit_amount,
    cast(ifnull(sum(cradit_amount),0.0) as decimal(10,2)) as credit_amount
        from 
    t_client_leger where client = " . $request;
    $data = array();
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "0 results";
    }
    echo json_encode($data);
    mysqli_close($conn);
}
?>
