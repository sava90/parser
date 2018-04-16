<html>
<head>
    <title>Report <?php echo $view_date; ?></title>
    <meta charset="UTF-8">
</head>
<body>
    <table>
        <tr>
            <th>Link</th>
            <th>Total tag img</th>
            <th>Time (sec.)</th>
        </tr>
        <?php foreach($view_reportsData as $result): ?>
            <tr>
                <td><?php echo $result[0]; ?></td>
                <td><?php echo $result[1]; ?></td>
                <td><?php echo $result[2]; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>