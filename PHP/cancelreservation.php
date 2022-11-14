<?php
session_start();
include '../../private/conn.php';

$userid= $_SESSION['userid'];

$moviesid = $_GET['moviesid'];
$starttime = $_GET['starttime'];
$endtime = $_GET['endtime'];
$room = $_GET['room'];



    $stmtcalendar = $conn->prepare("DELETE FROM userreserved WHERE moviesid = :moviesid and starttime = :starttime and endtime = :endtime and roomid = :roomid");
    $stmtcalendar->bindParam(':moviesid', $moviesid);
    $stmtcalendar->bindParam(':starttime', $starttime);
    $stmtcalendar->bindParam(':endtime', $endtime);
    $stmtcalendar->bindParam(':roomid', $room);
    $stmtcalendar->execute();


$stmt = $conn->prepare("UPDATE calendar  SET reserved = 'notreserved' WHERE moviesid = :moviesid and starttime = :starttime and endtime = :endtime and roomid = :roomid ");
$stmt->bindParam(':moviesid', $moviesid);
$stmt->bindParam(':roomid', $room);
$stmt->bindParam(':starttime', $starttime);
$stmt->bindParam(':endtime', $endtime);
$stmt->execute();


    header('location: ../index.php?page=reservations&userid=' . $userid);



