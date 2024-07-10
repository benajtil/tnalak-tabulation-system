<?php
require('../db/db_connection_sqlite_festive.php');

$scores = [];
$all_scores = [];
$judges = [];

// Fetch average scores and deductions for each entry
$sql = "SELECT entry_num, 
               AVG(festive_spirit) AS avg_fsp, 
               AVG(costume_and_props) AS avg_cap, 
               AVG(relevance_to_the_theme) AS avg_rt, 
               IFNULL(MAX(deduction), 0) AS max_deduction,
               (AVG(festive_spirit) + AVG(costume_and_props) + AVG(relevance_to_the_theme) - IFNULL(MAX(deduction), 0)) AS avg_total 
        FROM scores 
        GROUP BY entry_num 
        ORDER BY avg_total DESC";

try {
    $result = $conn->query($sql);

    if ($result) {
        $ranking = 1;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $row['ranking'] = $ranking++;
            $scores[$row['entry_num']] = $row;
        }
    } else {
        echo "Error fetching scores: " . $conn->errorInfo()[2];
    }
} catch (PDOException $e) {
    echo "Error fetching scores: " . $e->getMessage();
}

// Fetch scores by judges
$score_query = "SELECT entry_num, judge_name, total_score FROM scores";
try {
    $score_result = $conn->query($score_query);

    if ($score_result) {
        while ($score_row = $score_result->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($all_scores[$score_row['entry_num']])) {
                $all_scores[$score_row['entry_num']] = [];
            }
            $all_scores[$score_row['entry_num']][$score_row['judge_name']] = $score_row['total_score'];
        }
    } else {
        echo "Error fetching judge scores: " . $conn->errorInfo()[2];
    }
} catch (PDOException $e) {
    echo "Error fetching judge scores: " . $e->getMessage();
}

// Fetch judges
$judge_query = "SELECT id, name FROM user WHERE role = 1 ORDER BY id";
try {
    $judge_result = $conn->query($judge_query);

    if ($judge_result) {
        while ($judge_row = $judge_result->fetch(PDO::FETCH_ASSOC)) {
            $judges[] = $judge_row;
        }
    } else {
        echo "Error fetching judges: " . $conn->errorInfo()[2];
    }
} catch (PDOException $e) {
    echo "Error fetching judges: " . $e->getMessage();
}

// Close the SQLite connection
$conn = null;
?>
