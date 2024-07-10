<?php
require('fetch_scores.php'); // Ensure this path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Float Competition</title>
    <link rel="stylesheet" href="css/float.css">
</head>
<body>
<div class="tnalaklogo">
    <img src="../tnalak.png" alt="t'nalak image">
</div>
<div class="emblem">
    <img src="../emblem.png" alt="t'nalak image">
</div>

<div class="container">
    <h1>FLOAT PARADE COMPETITION</h1>
    <h1>TABULATION SHEET</h1>
    <table>
        <thead>
            <tr>
                <th>Entry No.</th>
                <?php foreach ($judges as $index => $judge) : ?>
                    <th>Judge <?php echo htmlspecialchars($index + 1); ?></th>
                <?php endforeach; ?>
                <th>Total</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($scores as $entry_num => $score) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry_num); ?></td>
                    <?php foreach ($judges as $judge) : ?>
                        <td>
                            <?php
                            echo htmlspecialchars($all_scores[$entry_num][$judge['name']] ?? 'N/A');
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td><?php echo htmlspecialchars(number_format($score['avg_total'], 4)); ?></td> 
                    <td><?php echo htmlspecialchars($score['ranking']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
