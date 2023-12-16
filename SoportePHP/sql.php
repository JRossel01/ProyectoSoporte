<?php
require_once 'database.php';

// Definir la instrucción SQL para borrar las tablas existentes
$sqlDropTables = "
    DROP TABLE IF EXISTS reviews, questions, products
";

// Ejecutar la instrucción SQL para borrar las tablas existentes
$resultDropTables = pg_query($dbconn, $sqlDropTables);

if (!$resultDropTables) {
    echo "Ocurrió un error al borrar las tablas existentes.\n";
} else {
    echo "Tablas existentes eliminadas con éxito.\n";

    // Definir la instrucción SQL para crear la tabla products
    $sqlProducts = "
        CREATE TABLE IF NOT EXISTS products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10, 2) NOT NULL
        )
    ";

    // Ejecutar la instrucción SQL para products
    $resultProducts = pg_query($dbconn, $sqlProducts);

    if (!$resultProducts) {
        echo "Ocurrió un error al crear la tabla products.\n";
    } else {
        echo "La tabla products se creó con éxito.\n";

        // Insertar productos
        $insertProduct1 = "INSERT INTO products (name, price) VALUES ('1080TI', 599.99)";
        $resultInsert1 = pg_query($dbconn, $insertProduct1);

        $insertProduct2 = "INSERT INTO products (name, price) VALUES ('3060TI', 499.99)";
        $resultInsert2 = pg_query($dbconn, $insertProduct2);

        $insertProduct3 = "INSERT INTO products (name, price) VALUES ('1650TI', 399.99)";
        $resultInsert3 = pg_query($dbconn, $insertProduct3);

        if ($resultInsert1 && $resultInsert2 && $resultInsert3) {
            echo "Productos insertados con éxito.\n";
        } else {
            echo "Ocurrió un error al insertar productos.\n";
        }
    }
}

// Definir la instrucción SQL para crear la tabla reviews
$sqlReviews = "
    CREATE TABLE IF NOT EXISTS reviews (
        id SERIAL PRIMARY KEY,
        message TEXT NOT NULL,
        feedback INT,
        date DATE NOT NULL,
        hour TIME NOT NULL
    )
";

// Ejecutar la instrucción SQL para reviews
$resultReviews = pg_query($dbconn, $sqlReviews);

if (!$resultReviews) {
    echo "Ocurrió un error al crear la tabla reviews.\n";
} else {
    echo "La tabla reviews se creó con éxito.\n";
}

// Definir la instrucción SQL para crear la tabla questions
$sqlQuestions = "
    CREATE TABLE IF NOT EXISTS questions (
        id SERIAL PRIMARY KEY,
        mensaje TEXT NOT NULL,
        date DATE NOT NULL,
        hour TIME NOT NULL,
        producto_id INTEGER REFERENCES products(id)
    )
";

// Ejecutar la instrucción SQL para questions
$resultQuestions = pg_query($dbconn, $sqlQuestions);

if (!$resultQuestions) {
    echo "Ocurrió un error al crear la tabla questions.\n";
} else {
    echo "La tabla questions se creó con éxito.\n";
}

// Cerrar la conexión
pg_close($dbconn);


