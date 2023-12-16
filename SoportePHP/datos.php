<?php
require_once 'database.php';

// Archivo a procesar
$archivo = "C:/Users/JORGE/Downloads/Soporte/part-r-00000";

// Abrir el archivo
$file = fopen($archivo, "r");

if ($file) {
    // Recorrer el archivo línea por línea
    while (($line = fgets($file)) !== false) {
        // Dividir la línea en partes usando el separador |
        $parts = explode(" | ", $line);

        // Obtener los valores
        $fecha = $parts[0];
        $hora = $parts[1];
        $mensaje = $parts[2];
        $tipo = $parts[3];

        // Realizar la inserción según el tipo
        if ($tipo === "Reseña" || $tipo === "Resenha") {
            $feedback = $parts[4];

            // Inserción en la tabla reviews (evitar duplicados)
            $sqlInsertReview = "
                INSERT INTO reviews (message, feedback, date, hour)
                SELECT '$mensaje', '$feedback', '$fecha', '$hora'
                WHERE NOT EXISTS (
                    SELECT 1 FROM reviews
                    WHERE message = '$mensaje' AND date = '$fecha' AND hour = '$hora'
                )
            ";

            $resultInsertReview = pg_query($dbconn, $sqlInsertReview);

            if (!$resultInsertReview) {
                echo "Error al insertar en la tabla reviews.\n";
            }
        } elseif ($tipo === "Producto") {
            $nombreProducto = trim($parts[4]); // Eliminar espacios en blanco al inicio y al final
            $nombreProducto = strtolower($nombreProducto); // Convertir a minúsculas

            // Eliminar puntos al final del nombre del producto
            $nombreProducto = rtrim($nombreProducto, '.');

            // Verificar si el producto existe en la tabla products (insensible a mayúsculas y minúsculas)
            $sqlCheckProduct = "SELECT id FROM products WHERE LOWER(name) = LOWER('$nombreProducto')";
            $resultCheckProduct = pg_query($dbconn, $sqlCheckProduct);

            if (!$resultCheckProduct || pg_num_rows($resultCheckProduct) == 0) {
                // Si no existe, insertarlo en la tabla products
                $sqlInsertProduct = "INSERT INTO products (name, price) VALUES ('$nombreProducto', 0.0)";
                $resultInsertProduct = pg_query($dbconn, $sqlInsertProduct);

                if (!$resultInsertProduct) {
                    echo "Error al insertar en la tabla products.\n";
                }
            }

            // Obtener el ID del producto
            $sqlGetProductId = "SELECT id FROM products WHERE LOWER(name) = LOWER('$nombreProducto')";
            $resultGetProductId = pg_query($dbconn, $sqlGetProductId);
            $rowProductId = pg_fetch_assoc($resultGetProductId);
            $productoId = $rowProductId['id'];

            // Inserción en la tabla questions (evitar duplicados)
            $sqlInsertQuestion = "
                INSERT INTO questions (mensaje, date, hour, producto_id)
                SELECT '$mensaje', '$fecha', '$hora', $productoId
                WHERE NOT EXISTS (
                    SELECT 1 FROM questions
                    WHERE mensaje = '$mensaje' AND date = '$fecha' AND hour = '$hora' AND producto_id = $productoId
                )
            ";

            $resultInsertQuestion = pg_query($dbconn, $sqlInsertQuestion);

            if (!$resultInsertQuestion) {
                echo "Error al insertar en la tabla questions.\n";
            }
        }
    }

    // Cerrar el archivo
    fclose($file);
} else {
    echo "Error al abrir el archivo.\n";
}

// Cerrar la conexión
pg_close($dbconn);

echo "Procesamiento completado con éxito. Las inserciones en la base de datos se realizaron correctamente.\n";