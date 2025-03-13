<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "miniprojet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT t, x, y, z FROM code ORDER BY t";
$result = $conn->query($sql);

$dataPointsX = array();
$dataPointsY = array();
$dataPointsZ = array();
$categories = array();
$data = array();

if ($result) {
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $categories[] = $row["t"];
            $dataPointsX[] = $row["x"];
            $dataPointsY[] = $row["y"];
            $dataPointsZ[] = $row["z"];
            $data[] = array(
                't' => htmlspecialchars($row['t']),
                'x' => htmlspecialchars($row['x']),
                'y' => htmlspecialchars($row['y']),
                'z' => htmlspecialchars($row['z'])
            );
        }
    } else {
        echo "0 results";
    }
    $result->free();
} else {
    echo "Error executing query: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        #chart {
            width: 100%; 
            max-width: none;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .data-table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table-container thead {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .data-table-container th,
        .data-table-container td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table-container tbody tr:hover {
            background-color: #f5f5f5;
        }

        .data-table-container th {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>
    <nav>
        <a href="login.php">Déconnexion</a>
    </nav>

    <div id="chart"></div>

    <div class="data-table-container">
        <h2>Tableau des données</h2>
        <table>
            <thead>
                <tr>
                    <th>Date et Heure</th>
                    <th>X</th>
                    <th>Y</th>
                    <th>Z</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['t']); ?></td>
                        <td><?php echo htmlspecialchars($row['x']); ?></td>
                        <td><?php echo htmlspecialchars($row['y']); ?></td>
                        <td><?php echo htmlspecialchars($row['z']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'X',
                    data: <?php echo json_encode($dataPointsX); ?>
                }, {
                    name: 'Y',
                    data: <?php echo json_encode($dataPointsY); ?>
                }, {
                    name: 'Z',
                    data: <?php echo json_encode($dataPointsZ); ?>
                }],
                chart: {
                    height: 350,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    categories: <?php echo json_encode($categories); ?>
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>

</body>
</html>
