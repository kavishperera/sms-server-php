<?php

include('./app/db.php');
date_default_timezone_set('Asia/Calcutta');

//url get data variables
$api_key = $number = $message = "";

//another varibals
$client = $client_sender_id = $cradit_amount = $response = $master_url = $token = "";

//url get data variables
$character_count = $sms_count = $sms_max_character_count_160 = $master_api_key_160 = $master_api_key_320 = 0;

//settings
$sms_max_character_count_160 = 160;

//get values
$api_key = $_GET['api_key'];
$number = $_GET['number'];
$message = $_GET['message'];
$date = date("Y-m-d H:i:s");

$sql = "select * from m_token where date = curdate()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $token = $row["token"];
    }
} else {

    $sql = "select * from m_configuration where activation = true";
    $result = $conn->query($sql);
    $api_username = $api_password = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $api_username = $row["api_username"];
            $api_password = $row["api_password"];
        }

        $url = 'https://digitalreachapi.dialog.lk/refresh_token.php';

        // DATA JASON ENCODED
        $data = array("u_name" => $api_username, "passwd" => $api_password);
        $data_json = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // DATA ARRAY
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        } else {
            $request = json_decode($response);
            $access_token = $request->access_token;

            $sql = "INSERT INTO m_token (`date`, `token`) VALUES ('$date', '$access_token');";
            if ($conn->multi_query($sql) === TRUE) {
                $token = $access_token;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        curl_close($ch);
    }
}

$sql = "SELECT * FROM m_client WHERE api_key = $api_key";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        //check client activeted
        if ($row["status"] == "ACTIVE") {
            $client = $row["index_no"];
            $client_sender_id = $row["sender_id"];

            //check client amount
            $sql = "select 
                        cast(ifnull(sum(debit_amount),0.0) as decimal(10,2)) - cast(ifnull(sum(cradit_amount),0.0) as decimal(10,2)) as credit_amount
                    from 
                        t_client_leger where client = $client";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $cradit_amount = $row["credit_amount"];
                    if ($cradit_amount > 0.0) {

                        //sms check sms count
                        $character_count = strlen(urldecode($message));
                        $sms_count = ceil($character_count / $sms_max_character_count_160);

                        //client amount and sms cost check
                        if ($cradit_amount > $sms_count) {

                            if ($sms_count == 1) {

                                $url = 'https://digitalreachapi.dialog.lk/camp_req.php';

                                // DATA JASON ENCODED
                                $data = array(
                                    "msisdn" => $number,
                                    "channel" => "1",
                                    "mt_port" => $client_sender_id,
                                    "s_time" => date("Y-m-d H:i:s"),
                                    "e_time" => date('Y-m-d H:i:s', strtotime('1 hour')),
                                    "msg" => $message,
                                    "callback_url" => "https://digitalreachapi.dialog.lk//call_back.php"
                                );
                                $data_json = json_encode($data);

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);

                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization:' . $token));
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                                // DATA ARRAY
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($ch);

                                if ($response === false) {
                                    $response = curl_error($ch);
                                } else {
                                    //echo $response;
                                    $request = json_decode($response);
                                    $error = $request->error;
                                    if ($error == "0") {
                                        $camp_id = $request->camp_id;
                                        $ref_id = $request->ref_id;

                                        //message details table insert data
                                        $sql = "INSERT INTO t_message_details (`client`, `receiver_number`, `message`, `date`, `sms_count`,`characters_count`,`camp_id`,`ref_id`,`token`) VALUES ($client, '$number', '$message', '$date', 1,'$character_count','$camp_id','$ref_id','$token');";
                                        if ($conn->multi_query($sql) === TRUE) {
                                            $last_id = $conn->insert_id;
                                            //save client leger table insert data
                                            $sql = "INSERT INTO t_client_leger (`date`, `client`, `cradit_amount`,`trasaction`,`trasaction_type`) VALUES ('$date', '$client', 1,'$last_id','SENT_MESSAGE');";
                                            if ($conn->multi_query($sql) === TRUE) {
                                                echo '0';
                                            } else {
                                                echo "Error: " . $sql . "<br>" . $conn->error;
                                            }
                                        } else {
                                            echo "Error: " . $sql . "<br>" . $conn->error;
                                        }
                                    } else if ($error == "101") {
                                        echo 'error in parameter';
                                    } else if ($error == "102") {
                                        echo 'global throttle exceeds';
                                    } else if ($error == "103") {
                                        echo 'user wise throttle exceeds';
                                    } else if ($error == "104") {
                                        echo 'Invalid token -> request for new token';
                                    } else if ($error == "105") {
                                        echo 'User is blocked';
                                    } else if ($error == "106") {
                                        echo 'Invalid channel type';
                                    } else if ($error == "107") {
                                        echo 'Invalid mt_port (Number Mask)';
                                    } else if ($error == "108") {
                                        echo 'error in time frame';
                                    } else if ($error == "109") {
                                        echo 'Insufficient balance';
                                    } else if ($error == "110") {
                                        echo 'Invalid number';
                                    } else if ($error == "111") {
                                        echo 'Invalid message type';
                                    } else if ($error == "112") {
                                        echo 'max ad length allowed for selected channel exceed';
                                    }
                                }
                                curl_close($ch);
                            } else if ($sms_count == 2) {
                                $url = 'https://digitalreachapi.dialog.lk/camp_req.php';

                                // DATA JASON ENCODED
                                $data = array(
                                    "msisdn" => $number,
                                    "channel" => "9",
                                    "mt_port" => $client_sender_id,
                                    "s_time" => date("Y-m-d H:i:s"),
                                    "e_time" => date('Y-m-d H:i:s', strtotime('1 hour')),
                                    "msg" => $message,
                                    "callback_url" => "https://digitalreachapi.dialog.lk//call_back.php"
                                );
                                $data_json = json_encode($data);

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);

                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization:' . $token));
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                                // DATA ARRAY
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $response = curl_exec($ch);

                                if ($response === false) {
                                    $response = curl_error($ch);
                                } else {
                                    //echo $response;
                                    $request = json_decode($response);
                                    $error = $request->error;
                                    if ($error == "0") {
                                        $camp_id = $request->camp_id;
                                        $ref_id = $request->ref_id;

                                        //message details table insert data
                                        $sql = "INSERT INTO t_message_details (`client`, `receiver_number`, `message`, `date`, `sms_count`,`characters_count`,`camp_id`,`ref_id`,`token`) VALUES ($client, '$number', '$message', '$date', 1,'$character_count','$camp_id','$ref_id','$token');";
                                        if ($conn->multi_query($sql) === TRUE) {
                                            $last_id = $conn->insert_id;
                                            //save client leger table insert data
                                            $sql = "INSERT INTO t_client_leger (`date`, `client`, `cradit_amount`,`trasaction`,`trasaction_type`) VALUES ('$date', '$client', 1,'$last_id','SENT_MESSAGE');";
                                            if ($conn->multi_query($sql) === TRUE) {
                                                echo '0';
                                            } else {
                                                echo "Error: " . $sql . "<br>" . $conn->error;
                                            }
                                        } else {
                                            echo "Error: " . $sql . "<br>" . $conn->error;
                                        }
                                    } else if ($error == "101") {
                                        echo 'error in parameter';
                                    } else if ($error == "102") {
                                        echo 'global throttle exceeds';
                                    } else if ($error == "103") {
                                        echo 'user wise throttle exceeds';
                                    } else if ($error == "104") {
                                        echo 'Invalid token -> request for new token';
                                    } else if ($error == "105") {
                                        echo 'User is blocked';
                                    } else if ($error == "106") {
                                        echo 'Invalid channel type';
                                    } else if ($error == "107") {
                                        echo 'Invalid mt_port (Number Mask)';
                                    } else if ($error == "108") {
                                        echo 'error in time frame';
                                    } else if ($error == "109") {
                                        echo 'Insufficient balance';
                                    } else if ($error == "110") {
                                        echo 'Invalid number';
                                    } else if ($error == "111") {
                                        echo 'Invalid message type';
                                    } else if ($error == "112") {
                                        echo 'max ad length allowed for selected channel exceed';
                                    }
                                }
                                curl_close($ch);
                            } else {
                                echo 'lnvalid message length';
                            }
                        } else {
                            echo 'client sms credit ' . $cradit_amount . '  - but - this sms cost ' . $sms_count;
                        }
                    } else {
                        echo 'client account has no sms credit';
                    }
                }
            }
        } else {
            echo 'client is deactivated';
        }
    }
} else {
    echo "api-key not found";
}
$conn->close();
?>
