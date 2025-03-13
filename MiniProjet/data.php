<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';
header('Content-Type: application/json');

$data = ['series' => [], 'categories' => []];

try {
    $query = "SELECT date_mesure, x_value, y_value, z_value FROM code ORDER BY date_mesure";
    $stmt = $pdo->query($query);
    $series = [
        ['name' => 'x', 'data' => []],
        ['name' => 'y', 'data' => []],
        ['name' => 'z', 'data' => []]
    ];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data['categories'][] = $row['date_mesure'];
        $series[0]['data'][] = (float)$row['x_value'];
        $series[1]['data'][] = (float)$row['y_value'];
        $series[2]['data'][] = (float)$row['z_value'];
    }

    $data['series'] = $series;

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
