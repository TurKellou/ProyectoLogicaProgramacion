<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM Ejercicio WHERE IdEjercicio = ?");
$stmt->execute([$id]);
$ej = $stmt->fetch();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fw-bold">Reto #<?php echo htmlspecialchars($ej['IdEjercicio']); ?></h3>
                    <a href="index.php" class="btn btn-outline-light btn-sm">Salir</a>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="mb-4">
                    <h4 class="text-primary fw-bold"><?php echo htmlspecialchars($ej['Titulo']); ?></h4>
                    <p class="fs-5 text-dark bg-light p-3 rounded-3 border-start border-4 border-primary">
                        <?php echo htmlspecialchars($ej['Enunciado']); ?>
                    </p>
                </div>

                <form id="formPractica">
                    <input type="hidden" name="id_ejercicio" value="<?php echo $id; ?>">
                    
                    <h5 class="fw-bold mb-3">1. Ingresa los datos de prueba:</h5>
                    <div class="row g-3 bg-light p-3 rounded mb-4">
                        <?php if ($id == 1): // Inputs para Promedio ?>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nota 1</label>
                                <input type="number" name="n1" class="form-control" step="0.1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nota 2</label>
                                <input type="number" name="n2" class="form-control" step="0.1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nota 3</label>
                                <input type="number" name="n3" class="form-control" step="0.1" required>
                            </div>
                        <?php elseif ($id == 2): // Inputs para Horas Extras ?>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Horas Trabajadas</label>
                                <input type="number" name="horas" class="form-control" step="0.5" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tarifa por Hora ($)</label>
                                <input type="number" name="tarifa" class="form-control" step="0.1" required>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h5 class="fw-bold mb-3">2. ¿Cuál crees que es el resultado según tu algoritmo?</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <?php if ($id == 1): ?>
                                <label class="form-label">Escribe el estado del alumno (Exactamente: "Aprobado" o "Reprobado")</label>
                                <input type="text" name="respuesta_usuario" class="form-control form-control-lg border-primary" required>
                            <?php elseif ($id == 2): ?>
                                <label class="form-label">Escribe el Sueldo Total numérico (Ej: 450.50)</label>
                                <input type="number" name="respuesta_usuario" class="form-control form-control-lg border-primary" step="0.01" required>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                            <i class="bi bi-play-fill"></i> Evaluar Mi Lógica
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formPractica').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('procesar.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if(!res.ok) throw new Error('Error en la red');
        return res.json();
    })
    .then(data => {
        Swal.fire({
            title: data.status === 'success' ? '¡Excelente Trabajo!' : 'Hay un error en tu lógica',
            html: data.message + '<br><br><strong>Sugerencia:</strong> ' + data.recomendacion,
            icon: data.status,
            confirmButtonColor: data.status === 'success' ? '#198754' : '#dc3545'
        });
    })
    .catch(error => {
        Swal.fire('Error del Servidor', 'El archivo procesar.php no devolvió un JSON válido. Revisa el código PHP.', 'error');
        console.error(error);
    });
});
</script>

<?php require_once '../../includes/footer.php'; ?>