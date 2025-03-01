<?php
    $dayOfWeek = (int)date('w');

    $johnWorkingTime = in_array($dayOfWeek, [1, 3, 5]) ? "8:00 - 12:00" : "Not working day";
    $janeWorkingTime = in_array($dayOfWeek, [2, 4, 6]) ? "12:00 - 16:00" : "Not working day";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table</title>
</head>
<body>
    <table border="1" width="100%" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th>№</th>
                <th>Фамилия Имя</th>
                <th>График работы</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>John Styles</td>
                <td><?php echo $johnWorkingTime ?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane Doe</td>
                <td><?php echo $janeWorkingTime ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

<?php

$a = 0;
$b = 0;
// For loop
for ($i = 0; $i < 5; $i++) {
    $a += 10;
    $b += 5;
    echo "Iteration $i: \$a $a - $b <br>";
}

echo "End of the loop: a = $a, b = $b <br><br>";

$i = 0;

// While loop
while ($i < 5) {
    $a += 10;
    $b += 5;
    echo "Iteration $i: \$a $a - $b <br>";
    $i++;
}
echo "<br>End of the loop: a = $a, b = $b <br><br>";

// Do-while loop

$i = 0;
do {
    $a += 10;
    $b += 5;
    echo "Iteration $i: \$a $a - $b <br>";
    $i++;
} while ($i < 5);

echo "End of the loop: a = $a, b = $b";

