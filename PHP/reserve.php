<?php
session_start();
include '../../private/conn.php';

$userid = $_SESSION['userid'];
$moviesid = $_POST['moviesid'];
$roomname = $_POST['roomname'];
$seats = $_POST['seats'];
$starttime = $_POST['starttime'];
$endtime = $_POST['endtime'];
$roomid = $_POST['roomid'];

if (isset($_POST['seats'])) {
    $count = count($seats);

    if ($count >= 6) {

        $_SESSION['notification'] = 'You have chosen 6 or more seats, please contacts us for your reservation';
        header('location: ../index.php?page=reserve&moviesid=' . $moviesid . '&roomname=' . $roomname);

    } else {

        foreach ($seats as $seatid) {
            $stmt2 = $conn->prepare("INSERT INTO userreserved  (userid,moviesid,roomid,seats)
                                VALUES(:userid,:moviesid,:room,:seats)");
            $stmt2->bindParam(':userid', $userid);
            $stmt2->bindParam(':moviesid', $moviesid);
            $stmt2->bindParam(':room', $roomid);
            $stmt2->bindParam(':seats', $seatid);
            $stmt2->execute();
            $_SESSION['notification'] = 'You have chosen 6 or more seats, please contacts us for your reservation';
            header('location: ../index.php?page=reservations&useridid=' . $userid );


        }
    }

} else {
    $_SESSION['notification'] = 'Please select a seat.';
    header('location: ../index.php?page=reserve&moviesid=' . $moviesid . '&roomname=' . $roomname);


}

