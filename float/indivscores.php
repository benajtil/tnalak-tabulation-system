<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUDGING SHEET</title>
    <link rel="stylesheet" href="/float/css/float.css?v=1.0">
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


        require('../db/db_connection_sqlite.php');


        $query = "SELECT entry_num, overall_appearance, artistry_design, craftsmanship, relevance_theme FROM scores WHERE judge_name = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
        }
        $stmt->execute([$judge]);

        echo "<table>";
        echo "<thead><tr><th>Entry No.</th><th>Overall Appearance and Impact (30%) <p>(Overall look, aesthetic value, and attractiveness of the float)</p> </th><th>Artistry/Design (20%) <p>(Concept and artistic merits of the design and costumes if there is/are any taking into account balance, proportion, emphasis, harmony as primarily reflected in shapes/image and colors)</p> </th><th>Craftsmanship (30%) <p>(This pertains to how the design is realized and how the float is made. Such factors to be considered are the quality of the craftsmanship, stability of structure and decoration, choice, and creative use of materials)</p> </th><th>Relevance to the Festive Theme (20%) <p>Onward South Cotabato: Dreaming Big, Weaving more progress. Rising above challenges</p> </th><th>Total</th></tr></thead>";
        echo "<tbody>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_score = $row['overall_appearance'] + $row['artistry_design'] + $row['craftsmanship'] + $row['relevance_theme'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['entry_num']) . "</td>";
            echo "<td>" . htmlspecialchars($row['overall_appearance']) . "</td>";
            echo "<td>" . htmlspecialchars($row['artistry_design']) . "</td>";
            echo "<td>" . htmlspecialchars($row['craftsmanship']) . "</td>";
            echo "<td>" . htmlspecialchars($row['relevance_theme']) . "</td>";
            echo "<td>" . htmlspecialchars($total_score) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "<h2><u>$judge</u></h2>";
        
        $conn = null;
        ?>
        <h3>Judge</h3>
        
    </div>
</body>

</html>
