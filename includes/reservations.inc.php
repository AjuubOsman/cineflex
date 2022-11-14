<?php
include '../private/conn.php';

$userid = $_SESSION['userid'];
?>

<table class="table">
    <thead>
    <tr>
        <th scope="col">Picture</th>
        <th scope="col">Titel</th>
        <th scope="col">Seats</th>
        <th scope="col">Starttime</th>
        <th scope="col">Endtime</th>
        <th scope="col">Cancel reservation</th>
    </tr>
    </thead>
    <?php


    $sql = "SELECT moviesid
        FROM userreserved 
where userid = :userid group by moviesid ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    //echo '<pre>'; print_r($row); echo '</pre>';


    $sql = "SELECT *
        FROM movies 
where moviesid = :moviesid ";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bindParam(':moviesid', $row['moviesid']);
    $stmt2->execute();

    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    //echo '<pre>'; print_r($row2); echo '</pre>';

    $sql = "SELECT starttime,endtime
        FROM calendar 
where moviesid = :moviesid and reserved = 'reserved' ";
    $stmttest = $conn->prepare($sql);
    $stmttest->bindParam(':moviesid', $row['moviesid']);
    $stmttest->execute();
    while ($rowtest = $stmttest->fetch(PDO::FETCH_ASSOC)){
    //echo '<pre>'; print_r($rowtest); echo '</pre>';

    $sql = "SELECT  seats,moviesid,starttime,endtime
        FROM userreserved 
where userid = :userid and moviesid = :moviesid and starttime = :starttime and endtime = :endtime ";
    $stmt3 = $conn->prepare($sql);
    $stmt3->bindParam(':userid', $userid);
    $stmt3->bindParam(':moviesid', $row2['moviesid']);
    $stmt3->bindParam(':starttime', $rowtest['starttime']);
    $stmt3->bindParam(':endtime', $rowtest['endtime']);
    $stmt3->execute();
    $row3 = $stmt3->fetchAll();

    $sql = "SELECT *
        FROM userreserved 
where userid = :userid and moviesid = :moviesid and starttime = :starttime and endtime = :endtime  ";
    $stmt10 = $conn->prepare($sql);
    $stmt10->bindParam(':userid', $userid);
    $stmt10->bindParam(':moviesid', $row2['moviesid']);
    $stmt10->bindParam(':starttime', $rowtest['starttime']);
    $stmt10->bindParam(':endtime', $rowtest['endtime']);
    $stmt10->execute();
    $row10 = $stmt10->fetch(PDO::FETCH_ASSOC)



    // Alleen nog fixen dat als je seats reserveerd van dezelfde film op andere datum, dan moet dat op een andere row worden laten zien.
    // Voor de rest is het denk ik wel gefixt.


    ?>

    <tbody>
    <tr>
        <td><img src="data:image/png;base64,<?= $row2['picture'] ?>" width="200px" height="200px"></td>
        <td><?= $row2['title'] ?></td>
        <td><?php foreach ($row3 as $value) {
                echo $value['seats'] ?> <?= '<br/>';
            } ?></td>
        <td><?= $rowtest['starttime'] ?></td>
        <td><?= $rowtest['endtime'] ?>
        <td>
            <button class="btn btn-danger"
                    onclick=" if(confirm('Are you sure you want to cancel this reservation?'))window.location.href='php/cancelreservation.php?moviesid=<?= $row10["moviesid"] ?> & starttime=<?= $row10['starttime'] ?> & endtime=<?= $row10['endtime'] ?> & room=<?= $row10['roomid'] ?> '">
                Cancel reservation
            </button>
        </td>

        <?php }
        }
        }?>
    </tr>
    </tbody>
</table>