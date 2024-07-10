<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUDGING SHEET</title>
    <link rel="stylesheet" href="/festive/css/festive.css">
    <style>
        .top10 {
            background-color: blue;
            color: white;
        }

        .judge-signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .judge-signature {
            text-align: center;
            width: 45%;
        }

        .judge-signature .line {
            border-bottom: 1px solid black;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="tnalaklogo">
        <img src="../tnalak.png" alt="t'nalak image">
    </div>
    <div class="emblem">
        <img src="../emblem.png" alt="t'nalak image">
    </div>

    <div class="container">
        <?php

        $judge = isset($_GET['judge']) ? htmlspecialchars($_GET['judge']) : '';


        require('../db/db_connection_sqlite_festive.php'); 


        $query = "SELECT entry_num, festive_spirit, costume_and_props, relevance_to_the_theme FROM scores WHERE judge_name = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
        }

        $stmt->execute([$judge]);


        echo "<table>";
        echo "<thead><tr><th>Entry No.</th><th>Festive Spirit Of Parade Participants (50%) <p>(Festive-feel, Festive-look, Festivity, Color, Use of Liveners, Enthusiasm)</p> </th><th>Costume and Props (30%) <p>(Creativity & Uniqueness)</p> </th><th>Relevance to the Theme (20%) <p>(Theme: Onward South Cotabato: Dreaming Big, Weaving more progress. Rising above challenges)</p> </th><th>Total</th></tr></thead>";
        echo "<tbody>";


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_score = $row['festive_spirit'] + $row['costume_and_props'] + $row['relevance_to_the_theme'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['entry_num']) . "</td>";
            echo "<td>" . htmlspecialchars($row['festive_spirit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['costume_and_props']) . "</td>";
            echo "<td>" . htmlspecialchars($row['relevance_to_the_theme']) . "</td>";
            echo "<td>" . htmlspecialchars($total_score) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";


        $query_judge = "SELECT id FROM user WHERE name = ? AND role = 1";
        $stmt_judge = $conn->prepare($query_judge);

        if ($stmt_judge === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
        }

        $stmt_judge->execute([$judge]);
        $judge_num = $stmt_judge->fetchColumn();

        if ($judge_num !== false) {
            echo "<h2>Judge " . htmlspecialchars($judge_num) . ": <u>" . htmlspecialchars($judge) . "</u></h2>";
        } else {
            echo "<h2>Judge: <u>" . htmlspecialchars($judge) . "</u></h2>";
        }


        $conn = null;
        ?>
    </div>
</body>

</html>
