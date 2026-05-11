<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

// Validar que exista el ID en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger mt-5'>Error: Tema no especificado o inválido.</div>";
    require_once '../../includes/footer.php';
    exit();
}

$idTema = $_GET['id'];

// Consultar el tema específico
$stmt = $pdo->prepare("SELECT * FROM Tema WHERE IdTema = ?");
$stmt->execute([$idTema]);
$tema = $stmt->fetch();

// Si no existe el tema en la BD
if (!$tema) {
    echo "<div class='alert alert-warning mt-5'>El tema solicitado no existe.</div>";
    require_once '../../includes/footer.php';
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        
        <a href="index.php" class="btn btn-sm btn-outline-secondary mb-4">&larr; Volver a Unidades</a>
        
        <div class="bg-primary text-white p-4 rounded-3 shadow-sm mb-4">
            <span class="badge bg-light text-primary mb-2"><?php echo htmlspecialchars($tema['Unidad']); ?></span>
            <h1 class="display-6 fw-bold mb-0"><?php echo htmlspecialchars($tema['NombreTema']); ?></h1>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5 contenido-teoria">
                <?php echo $tema['Descripcion']; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-secondary">Ejercicios Resueltos de este tema</h4>
        </div>
        <div class="alert alert-light border border-info border-start-5 text-dark">
            <p class="mb-0">Próximamente: Aquí conectaremos el módulo de ejercicios resueltos paso a paso para aplicar lo aprendido en esta unidad.</p>
        </div>

    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>