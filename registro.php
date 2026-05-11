<?php
require_once '../../includes/conexion.php';
session_start();

// OBLIGATORIO: Asegurarnos de que PHP solo devuelva JSON, nada de HTML
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_ejercicio = $_POST['id_ejercicio'] ?? null;
        $respuesta_usuario = trim($_POST['respuesta_usuario'] ?? '');
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$id_ejercicio) {
            echo json_encode(['status' => 'error', 'message' => 'ID de ejercicio no recibido.', 'recomendacion' => 'Intenta de nuevo.']);
            exit();
        }

        $es_correcto = false;
        $mensaje = "";
        $recomendacion = "";

        // ----- LÓGICA DEL EJERCICIO 1: PROMEDIO -----
        if ($id_ejercicio == 1) {
            $n1 = (float)$_POST['n1'];
            $n2 = (float)$_POST['n2'];
            $n3 = (float)$_POST['n3'];
            
            $promedio = ($n1 + $n2 + $n3) / 3;
            $respuesta_real = ($promedio >= 7) ? "Aprobado" : "Reprobado";
            
            // Comparamos la lógica del usuario con la real (ignorando mayúsculas)
            $es_correcto = (strtolower($respuesta_usuario) === strtolower($respuesta_real));
            
            $mensaje = "El promedio real es " . round($promedio, 2) . " y el estado es <b>" . $respuesta_real . "</b>. Tu respuesta fue: " . $respuesta_usuario;
            $recomendacion = $es_correcto ? "Comprendes bien el uso de condicionales." : "Revisa la condición SI promedio >= 7.";
        }
        
        // ----- LÓGICA DEL EJERCICIO 2: HORAS EXTRAS -----
        elseif ($id_ejercicio == 2) {
            $horas = (float)$_POST['horas'];
            $tarifa = (float)$_POST['tarifa'];
            
            if ($horas <= 40) {
                $sueldo_real = $horas * $tarifa;
            } else {
                $horas_base = 40;
                $horas_extra = $horas - 40;
                $sueldo_real = ($horas_base * $tarifa) + ($horas_extra * ($tarifa * 2));
            }

            // Comparamos numéricamente
            $es_correcto = (abs((float)$respuesta_usuario - $sueldo_real) < 0.01);
            
            $mensaje = "El sueldo real calculado es <b>$" . number_format($sueldo_real, 2) . "</b>. Tu respuesta fue: $" . $respuesta_usuario;
            $recomendacion = $es_correcto ? "Excelente manejo de bifurcaciones lógicas y matemáticas." : "Recuerda que solo las horas POR ENCIMA de 40 se multiplican por el doble de la tarifa.";
        } 
        
        // ----- SI EL EJERCICIO NO EXISTE -----
        else {
            echo json_encode(['status' => 'error', 'message' => 'Ejercicio no programado aún.', 'recomendacion' => 'Contacta al administrador.']);
            exit();
        }

        // Guardamos el intento SOLO si el usuario está logueado
        if ($user_id !== null) {
            $sql = "INSERT INTO Intento (IdUsuario, IdEjercicio, RespuestaUsuario, Resultado) VALUES (?, ?, ?, ?)";
            $ins = $pdo->prepare($sql);
            $ins->execute([$user_id, $id_ejercicio, $respuesta_usuario, $es_correcto]);
        }

        // Devolvemos la respuesta final
        echo json_encode([
            'status' => $es_correcto ? 'success' : 'error',
            'message' => $mensaje,
            'recomendacion' => $recomendacion
        ]);
        exit();
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno del servidor: ' . $e->getMessage(),
        'recomendacion' => 'Revisa la consola.'
    ]);
}
?>