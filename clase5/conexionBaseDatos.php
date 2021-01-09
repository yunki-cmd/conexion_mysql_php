<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
$conn = mysqli_connect(
    'localhost',
    'root',
    '',
    'mercado_online'
);

$consulta = "SELECT * FROM productos";
$resultado = mysqli_query($conn, $consulta);
$obj = $resultado->fetch_assoc();
    
while($row = mysqli_fetch_array($resultado)){
    print_r($row['id_productos']);
    echo '<br><br>';
}

$conn->close();
?>
</body>

</html>