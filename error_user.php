
<!-- error.html -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Usuario NO Encontrado</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #2c3e50;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    text-align: center;
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #ff4c4c;
    font-size: 24px;
    margin-bottom: 20px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #45a049;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Error Usuario NO Encontrado</h1>
        <button onclick="location.href='index.php'">Regresar a Login</button>
    </div>
</body>
</html>