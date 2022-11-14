<?php
include '../private/conn.php';

$userid = $_SESSION['userid'];

//echo '<pre>'; print_r($row); echo '</pre>';
?>

<table class="table">
    <thead>
    <tr>
        <th scope="col">Picture</th>
        <th scope="col">Titel</th>
        <th scope="col">Seats</th>
        <th scope="col">Starttime</th>
        <th scope="col">Endtime</th>
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
        $sql = "SELECT *
        FROM movies 
where moviesid = :moviesid ";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bindParam(':moviesid', $row['moviesid']);
        $stmt2->execute();

        while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {

    $sql = "SELECT seats,moviesid
        FROM userreserved 
where userid = :userid and moviesid = :moviesid  ";
    $stmt3 = $conn->prepare($sql);
    $stmt3->bindParam(':userid', $userid);
    $stmt3->bindParam(':moviesid', $row2['moviesid']);
    $stmt3->execute();
    $row3 = $stmt3->fetchAll();
    // Alleen nog fixen dat als je seats reserveerd van dezelfde film op andere datum, dan moet dat op een andere row worden laten zien.
    // Voor de rest is het denk ik wel gefixt.
    ?>
            <tbody>
            <tr>
                <td><img src="data:image/png;base64,<?=$row2['picture'] ?>" width="200px" height="200px"></td>
                <td><?= $row2['title']?></td>
                <td><?php foreach ($row3 as $value ){ echo $value['seats'];} ?></td>
                <?php }}?>
            </tr>
            </tbody>
            </table>






