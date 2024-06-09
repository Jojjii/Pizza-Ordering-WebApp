<?php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $det = mysqli_real_escape_string($conn, $_POST['det']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $tp = mysqli_real_escape_string($conn, $_POST['tp']);  


    $SQL = "INSERT INTO orders (UserId, DetailID, TotalPrice) VALUES ('$uid', '$det', '$tp')";
    mysqli_query($conn, $SQL);
    $orderId = mysqli_insert_id($conn);

    // $ret = "SELECT OrderID from orders where UserID = $uid and  DetailID = $det and TotalPrice = $tp ";
    // $result = mysqli_query($conn, $ret);

    //     $row = mysqli_fetch_assoc($result);
    
    //     $orderId = $row['OrderID'];
    
        $names = isset($_POST['names']) ? $_POST['names'] : null;
        $quantities = isset($_POST['quans'])  ? $_POST['quans'] : null;;
        $dnames = isset($_POST['dn']) ? $_POST['dn'] : null;
        $dquantities = isset($_POST['dq']) ? $_POST['dq'] : null;

        if($names!==NULL){
    for ($i = 0; $i < count($names); $i++) {

            $name = mysqli_real_escape_string($conn, $names[$i]);
            $quantity = mysqli_real_escape_string($conn, $quantities[$i]);
            $sqlinner = "SELECT ItemID from items where Name = '$name'"; 
            $ret1 = mysqli_query($conn,$sqlinner);
            $rows = mysqli_fetch_assoc($ret1);
            $id = $rows['ItemID'];

            $orderListSQL = "INSERT INTO orderlist (OrderID, ItemID, Quantity) VALUES ('$orderId', '$id', '$quantity')";
           
        mysqli_query($conn, $orderListSQL);
    }}
    if($dnames!==NULL)
{    for ($i = 0; $i < count($dnames); $i++) {

        $dname = mysqli_real_escape_string($conn, $dnames[$i]);
        echo $dname;
        $dquantity = mysqli_real_escape_string($conn, $dquantities[$i]);
        $sqlinner = "SELECT DealID from deals where DealName = '$dname'"; 
        $ret1 = mysqli_query($conn,$sqlinner);
        $rows = mysqli_fetch_assoc($ret1);
        $id = "";
        if ($rows !== null && is_array($rows)) {
        $id = $rows['DealID'];  

        }
         
        $orderListSQL = "INSERT INTO dealorderlist (OrderID, DealID, Quantity) VALUES ('$orderId', '$id', '$dquantity')";
       
    mysqli_query($conn, $orderListSQL);
}}

    echo 'Order placed successfully';
} else {
    echo 'Invalid request';
}
?>
