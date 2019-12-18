<?php

include('../db.php');

$action = $_GET['action'];
//save
if ($action == 'f1') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    //$index_no = $request->index_no;
    $name = $request->name;
    $user_name = $request->user_name;
    $password = $request->password;
    $api_key = $request->api_key;
    $sender_id = $request->sender_id;
    //$status = $request->status;

    $sql = "INSERT INTO m_client (`name`, `user_name`, `password`, `api_key`,`sender_id`, `status`) VALUES ('$name', '$user_name', '$password', '$api_key','$sender_id', 'ACTIVE');";
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        echo $last_id;
    } else {
        echo("Error description: " . mysqli_error($con));
    }

    $conn->close();
    //view
} else if ($action == 'f2') {
    $showData = "SELECT * FROM m_client";
    $data = array();
    $result = mysqli_query($conn, $showData);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } else {
        echo "0 results";
    }
    echo json_encode($data);
    mysqli_close($conn);
    //update
} else if ($action == 'f3') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $index_no = $request->index_no;
    $name = $request->name;
    $user_name = $request->user_name;
    $password = $request->password;
    $api_key = $request->api_key;
    $sender_id = $request->sender_id;
    $status = $request->status;

    $sql = "UPDATE m_client set name = '$name',user_name = '$user_name',password = '$password',sender_id = '$sender_id' WHERE index_no = '$index_no'";
    if ($conn->query($sql) === TRUE) {
        echo TRUE;
    } else {
        echo FALSE;
    }
    //delete
} else if ($action == 'f4') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $sql = "DELETE FROM m_client WHERE index_no = $request";
    if ($conn->query($sql) === TRUE) {
        echo $request;
    } else {
        echo FALSE;
    }
    $conn->close();
    //update active in-active status
} else if ($action == 'f5') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $index_no = $request->index_no;
    $status = $request->status;

    $sql = "Update m_client set status = '$status' where index_no ='$index_no'";
    if ($conn->query($sql) === TRUE) {
        echo TRUE;
    } else {
        echo FALSE;
    }
    $conn->close();
    //find m_client by index_no
} else if ($action == 'f6') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $showData = "SELECT * FROM m_client WHERE index_no = '$request'";
    $data = array();
    $result = mysqli_query($conn, $showData);

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