<?php
include '../private/conn.php';
$moviesid = $_GET['moviesid'];
$roomname = $_GET['roomname'];
$starttime = $_GET['starttime'];
$endtime = $_GET['endtime'];
$roomid = $_GET['roomid'];
$userid = $_SESSION['userid'];


$seats = array();

$sql = "SELECT seats FROM userreserved where moviesid = :moviesid and starttime = :starttime and endtime = :endtime";
$stmt3 = $conn->prepare($sql);
$stmt3->bindParam(':moviesid', $moviesid);
$stmt3->bindParam(':starttime', $starttime);
$stmt3->bindParam(':endtime', $endtime);

$stmt3->execute();
while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
    array_push($seats, $row3['seats']);
}


if (isset($_SESSION['notification'])) {
    echo '<p style = "color:red;">' . $_SESSION['notification'] . '</p>';
    unset($_SESSION['notification']);
}
?>
<?php
$sql = "SELECT * FROM room WHERE roomname = :roomname ";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':roomname', $roomname);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo $roomname;
for ($x = 65, $i = 0;
     $i < $row['rows'];
     $i++, ++$x) { ?>
    <div class="letter" style="float: initial"> <?= chr($x); ?> </div>
    <?php for ($y = 1, $a = 0; $a < $row['colum']; $a++, $y++) {
        $seatnumber = chr($x) . $y ?>

        <div style="float:left">
            <form action="php/reserve.php" method="post">
                <div class="row">


                    <div class="seat" style="color: white"> <?= $seatnumber ?> </div>


                    <input type="checkbox" name="seats[]"
                           value="<?= $seatnumber ?>" <?php if (in_array($seatnumber, $seats)) { ?> checked="checked" disabled <?php } ?>>


                    <input type="hidden" name="roomname" value="<?= $roomname ?>  ">
                    <input type="hidden" name="moviesid" value="<?= $moviesid ?>">


                    <input type="hidden" name="starttime" value="<?= $starttime ?>">
                    <input type="hidden" name="endtime" value="<?= $endtime ?>">

                    <input type="hidden" name="roomid" value="<?= $roomid ?>">

                </div>
        </div>


    <?php }
}

?>

<button name="submit" type="submit" class="btn btn-success">Reserve seats</button>

</form>
