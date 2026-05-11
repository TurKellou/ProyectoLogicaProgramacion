<?php
require_once '../../includes/conexion.php';
require_once '../../includes/header.php';

$id = $_GET['id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM Ejercicio WHERE IdEjercicio = ?");
$stmt->execute([$id]);
$ej = $stmt->fetch();

// PROTECCIÓN CONTRA EL ERROR NULL:
// Si LineasPseudocodigo es nulo o está vacío, asignamos un string vacío.
$lineas_raw = $ej['LineasPseudocodigo'] ?? '';

if (empty(trim($lineas_raw))) {
    $lineas = []; // Si está vacío, la lista queda sin elementos
} else {
    // Si hay datos, los separamos y desordenamos
    $lineas = explode('|', $lineas_raw);
    shuffle($lineas);
}
?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-success text-white p-4">
                <h3 class="fw-bold mb-0"><i class="bi bi-diagram-3"></i> Desafío de Lógica: Ordenar Algoritmo</h3>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <strong>Reto:</strong> <?php echo htmlspecialchars($ej['Enunciado']); ?>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold text-secondary mb-3">Instrucciones desordenadas</h5>
                        <div id="lista-codigo" class="list-group shadow-sm">
                            <?php foreach ($lineas as $linea): ?>
                                <div class="list-group-item list-group-item-action py-3 cursor-move border-start border-4 border-success" data-id="<?php echo htmlspecialchars($linea); ?>">
                                    <code class="fs-5"><?php echo htmlspecialchars($linea); ?></code>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="h-100 p-4 bg-light rounded-3 border-dashed">
                            <h5 class="fw-bold text-dark">¿Cómo funciona?</h5>
                            <p class="text-muted">Arrastra las líneas de la izquierda para cambiar su posición hasta que el algoritmo tenga sentido lógico (Entrada → Proceso → Salida).</p>
                            <button onclick="validarAlgoritmo()" class="btn btn-success btn-lg w-100 mt-4 shadow">
                                <i class="bi bi-check2-circle"></i> Verificar Mi Algoritmo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Inicializar el ordenamiento visual (Mantenemos esto)
    var el = document.getElementById('lista-codigo');
    var sortable = Sortable.create(el, {
        animation: 150,
        ghostClass: 'bg-light-blue'
    });

    function validarAlgoritmo() {
        // 1. Obtenemos el orden actual y el ID del ejercicio
        const ordenActual = Array.from(el.children).map(item => item.getAttribute('data-id').trim()).join('|');
        const idEjercicio = <?php echo $id; ?>;
        
        // 2. Mostramos una animación de carga
        Swal.fire({
            title: 'Analizando lógica...',
            text: 'El sistema está evaluando tu algoritmo.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 3. Preparamos los datos a enviar
        const formData = new FormData();
        formData.append('id_ejercicio', idEjercicio);
        formData.append('orden_usuario', ordenActual);

        // 4. Enviamos al servidor vía AJAX
        fetch('procesar_pseudocodigo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // 5. Mostramos la retroalimentación real
            if(data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Lógica Correcta!',
                    text: data.message,
                    confirmButtonColor: '#198754'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Secuencia Incorrecta',
                    text: data.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
        });
    }
</script>

<style>
    .cursor-move { cursor: grab; }
    .cursor-move:active { cursor: grabbing; }
    .border-dashed { border: 2px dashed #ccc; }
    .bg-light-blue { background-color: #e7f1ff !important; border: 2px solid #0d6efd !important; }
</style>

<?php require_once '../../includes/footer.php'; ?>