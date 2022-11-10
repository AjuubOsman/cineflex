<?php
session_start();
include '../../private/conn.php';

$userid = $_SESSION['userid'];
$moviesid = $_POST['moviesid'];
$roomname = $_POST['roomname'];
$seats= $_POST['seats'];
$starttime = $_POST['starttime'];
$endtime = $_POST['endtime'];


if (isset($_POST['seats'])) {
    $count = count($seats);
    echo $count;


    if ($count >=6){

        $_SESSION['notification']= 'You have chosen 6 or more seats, please contacts us for your reservation';
        header('location: ../index.php?page=reserve&moviesid=' . $moviesid . '&roomname=' . $roomname);

    }else{

        foreach ($seats as $seatid) {

            $stmt2 = $conn->prepare("INSERT INTO userreserved  (userid,moviesid,room,seat)
                        VALUES(:userid,:moviesid,:room,:seat)");
            $stmt2->bindParam(':userid', $userid);
            $stmt2->bindParam(':moviesid', $moviesid);
            $stmt2->bindParam(':room', $roomname);
            $stmt2->bindParam(':seat', $seatid);
            $stmt2->execute();



        }

        // WRM DE FUCK WERKT DEZE QUERY NIETTTTTT ??!?!?!!?!?!?
        echo $roomname;
        $stmt3 = $conn->prepare("UPDATE room SET seats = seats - $count  where roomname = :roomname");
        $stmt3->bindParam(':roomname', $roomname);
        $stmt3->execute();
        //header('location: ../index.php?page=profile&userid=' . $userid);
    }

} else {
    $_SESSION['notification']= 'Please select a seat.';
    header('location: ../index.php?page=reserve&moviesid=' . $moviesid . '&roomname=' . $roomname);


}












