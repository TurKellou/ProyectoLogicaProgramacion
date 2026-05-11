<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

// Consultar todos los temas ordenados por Unidad
$stmt = $pdo->query("SELECT * FROM Tema ORDER BY Unidad ASC, IdTema ASC");
$temas = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary border-bottom pb-2">Unidades de Estudio</h2>
        <p class="text-muted">Selecciona un tema para revisar la teoría, el análisis algorítmico y los fundamentos de programación.</p>
    </div>
</div>

<div class="row g-4">
    <?php if (count($temas) > 0): ?>
        <?php foreach ($temas as $tema): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 border-top border-primary border-3">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($tema['Unidad']); ?></span>
                        <h5 class="card-title text-dark fw-bold"><?php echo htmlspecialchars($tema['NombreTema']); ?></h5>
                        <p class="card-text text-muted small">
                            <?php echo substr(strip_tags($tema['Descripcion']), 0, 100) . '...'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 text-end">
                        <a href="detalle.php?id=<?php echo $tema['IdTema']; ?>" class="btn btn-outline-primary btn-sm">Leer Teoría &rarr;</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">Aún no hay temas registrados en el sistema.</div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>