<?php
require_once '../../includes/conexion.php';
session_start();

// Le decimos al navegador que responderemos en formato JSON para que JavaScript lo entienda
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_ejercicio = $_POST['id_ejercicio'] ?? null;
        $orden_usuario = $_POST['orden_usuario'] ?? '';
        
        // Verificamos si hay un usuario logueado en la sesión
        $user_id = $_SESSION['user_id'] ?? null; 

        // ----- LÓGICA DEL EJERCICIO 1: PROMEDIO -----
        if ($id_ejercicio == 1) {
            
            // 1. Orden lógico correcto
            $orden_correcto = "INICIO|LEER n1, n2, n3|promedio = (n1 + n2 + n3) / 3|SI promedio >= 7 ENTONCES|IMPRIMIR \"Aprobado\"|SINO|IMPRIMIR \"Reprobado\"|FIN SI|FIN";

            // 2. Comparamos
            $es_correcto = (trim($orden_usuario) === trim($orden_correcto));

            // 3. Preparamos la retroalimentación
            $mensaje = $es_correcto
                ? "¡Excelente estructura! Has respetado correctamente las fases de Entrada, Proceso y Salida."
                : "Hay un error en tu secuencia. Recuerda la estructura básica: INICIO -> Leer variables -> Cálculo -> Condición -> Resultados -> FIN.";

            // 4. SOLO GUARDAMOS EN LA BD SI EL USUARIO HA INICIADO SESIÓN
            if ($user_id !== null) {
                $sql = "INSERT INTO Intento (IdUsuario, IdEjercicio, RespuestaUsuario, Resultado) VALUES (?, ?, ?, ?)";
                $ins = $pdo->prepare($sql);
                $ins->execute([$user_id, $id_ejercicio, "Intento de Pseudocódigo Ej1", $es_correcto]);
            }

            // 5. Respondemos a la interfaz
            echo json_encode([
                'status' => $es_correcto ? 'success' : 'error',
                'message' => $mensaje
            ]);
            exit();
        }
        
        // ----- LÓGICA DEL EJERCICIO 2: HORAS EXTRAS -----
        elseif ($id_ejercicio == 2) {
            
            // 1. Orden lógico correcto para el Ejercicio 2
            $orden_correcto = "INICIO|LEER horas_trabajadas, tarifa|SI horas_trabajadas <= 40 ENTONCES|sueldo = horas_trabajadas * tarifa|SINO|horas_extra = horas_trabajadas - 40|sueldo = (40 * tarifa) + (horas_extra * tarifa * 2)|FIN SI|IMPRIMIR \"El sueldo es:\", sueldo|FIN";

            // 2. Comparamos
            $es_correcto = (trim($orden_usuario) === trim($orden_correcto));

            // 3. Preparamos la retroalimentación
            $mensaje = $es_correcto
                ? "¡Excelente! Has estructurado perfectamente la condicional compuesta (SI-SINO) para el cálculo de horas extras."
                : "Hay un error en tu secuencia. Recuerda: INICIO -> LEER variables -> Condición SI (menor o igual a 40) -> cálculo normal -> SINO -> cálculo extra -> FIN SI -> IMPRIMIR -> FIN.";

            // 4. Guardamos en BD si hay sesión
            if ($user_id !== null) {
                $sql = "INSERT INTO Intento (IdUsuario, IdEjercicio, RespuestaUsuario, Resultado) VALUES (?, ?, ?, ?)";
                $ins = $pdo->prepare($sql);
                $ins->execute([$user_id, $id_ejercicio, "Intento de Pseudocódigo Ej2", $es_correcto]);
            }

            // 5. Respondemos a la interfaz
            echo json_encode([
                'status' => $es_correcto ? 'success' : 'error',
                'message' => $mensaje
            ]);
            exit();
            
        } 
        
        // ----- SI EL EJERCICIO NO EXISTE -----
        else {
             echo json_encode([
                'status' => 'error',
                'message' => 'Este reto de pseudocódigo aún no está disponible para validación.'
            ]);
            exit();
        }
    }
} catch (PDOException $e) {
    // Si hay un error de base de datos, no damos error 500, devolvemos el error ordenadamente
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de Base de Datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Cualquier otro error general
    echo json_encode([
        'status' => 'error',
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>