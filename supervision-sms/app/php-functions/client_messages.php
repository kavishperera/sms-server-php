<?php

include('../db.php');

$action = $_GET['action'];
//get client leger history
if ($action == 'f1') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $sql = "select * from t_message_details where client = " . $request;
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
if ($action == 'f2') {
    ini_set('memory_limit', '-1');
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $client = $request->client;
    $fromDate = $request->fromDate;
    $toDate = $request->toDate;
    $receiveNumber = $request->receiveNumber;
    $charactorCount = $request->charactorCount;
    $smsCount = $request->smsCount;

    $sql = "select * from t_message_details where 1 = 1";

    if ($client) {
        $sql .= " AND client = '$client'";
    }

    if ($receiveNumber) {
        $sql .= " AND receiver_number = '$receiveNumber'";
    }

    if ($charactorCount) {
        $sql .= " AND characters_count = '$charactorCount'";
    }

    if ($smsCount) {
        $sql .= " AND sms_count = '$smsCount'";
    }

    if ($fromDate && $toDate) {
        $sql .= " AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate'";
    }

    $sql .= " order by date DESC";
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