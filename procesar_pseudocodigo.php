<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

// Consultar ejercicios uniendo con la tabla Tema para saber a qué unidad pertenecen
$sql = "SELECT e.*, t.NombreTema, t.Unidad 
        FROM Ejercicio e 
        INNER JOIN Tema t ON e.IdTema = t.IdTema 
        ORDER BY t.Unidad ASC, e.Dificultad ASC";
$stmt = $pdo->query($sql);
$ejercicios = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-success border-bottom pb-2">Ejercicios Resueltos</h2>
        <p class="text-muted">Analiza casos de la vida real resueltos paso a paso antes de poner a prueba tu lógica.</p>
    </div>
</div>

<div class="row g-4">
    <?php if (count($ejercicios) > 0): ?>
        <?php foreach ($ejercicios as $ejercicio): ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($ejercicio['Categoria']); ?></span>
                            <?php 
                                $colorDif = $ejercicio['Dificultad'] == 'Básica' ? 'success' : ($ejercicio['Dificultad'] == 'Intermedia' ? 'warning' : 'danger');
                            ?>
                            <span class="badge bg-<?php echo $colorDif; ?>"><?php echo $ejercicio['Dificultad']; ?></span>
                        </div>
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($ejercicio['Titulo']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($ejercicio['Enunciado']); ?></p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="detalle.php?id=<?php echo $ejercicio['IdEjercicio']; ?>" class="btn btn-outline-success w-100">Ver Solución Paso a Paso</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">Aún no hay ejercicios resueltos disponibles.</div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>