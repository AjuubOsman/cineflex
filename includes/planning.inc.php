<?php
include '../private/conn.php';

$sql = "SELECT m.moviesid, m.title,c.moviesid,c.starttime,c.endtime,r.roomname,c.roomid
FROM movies m 
left join calendar c on m.moviesid = c.moviesid
left join room r on c.roomid = r.roomid

WHERE m.moviesid = c.moviesid";
$stmt = $conn->prepare($sql);
$stmt->execute();


if (isset($_SESSION['notification'])) {
    echo '<p style = "color:red;">' . $_SESSION['notification'] . '</p>';
    unset($_SESSION['notification']);
}

?>

<div class="table-responsive">

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'worker') { ?>
        <button type="button" class="btn btn-primary" onclick="window.location.href='index.php?page=planfilm'">
            Plan a film
        </button>
    <?php } ?>
    <table class="table">
        <thead>
        <div class='container'>
            <table class='table table-bordered'>
                <tr>
                    <th>Title</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>End time</th>
                    <th>Film details</th>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer') { ?>
                        <th>Reserve a seat</th>
                    <?php }
                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'worker') { ?>
                        <th>Unplan film</th>
                    <?php } ?>
                </tr>
                </thead>
                <?php if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {?>
                        <tbody>
                        <tr>
                            <td><?= $row["title"] ?></td>
                            <td><?= $row["roomname"] ?></td>
                            <td><?= $row["starttime"] ?> </td>
                            <td><?= $row["endtime"] ?> </td>
                            <td>
                                <button onclick="window.location.href='index.php?page=filmdetails&moviesid=<?= $row["moviesid"] ?>'">
                                    Film details
                                </button>
                            </td>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer' ) { ?>

                                <td>
                                    <button onclick="window.location.href='index.php?page=reserve&moviesid=<?= $row["moviesid"] ?>&roomname=<?= $row['roomname'] ?>&starttime=<?= $row['starttime'] ?>&endtime=<?= $row['endtime'] ?>&roomid=<?=$row['roomid']?>'">
                                        Reserve
                                    </button>
                                </td>

                            <?php }
                            if (isset($_SESSION['role']) && $_SESSION['role'] == 'worker') { ?>
                                <td>
                                    <button class="btn btn-danger"
                                            onclick=" if(confirm('Are you sure you want to unplan this film?'))window.location.href='php/unplan.php?moviesid=<?= $row["moviesid"] ?>&starttime=<?= $row['starttime'] ?>&endtime=<?= $row['endtime'] ?>&room=<?= $row['roomid'] ?>'">
                                        Unplan film
                                    </button>
                                </td>
                            <?php } ?>


                        </tr>
                        </tbody>
                    <?php }
                } ?>
            </table>
        </div>
</div>





