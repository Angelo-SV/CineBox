<?php
$titulo = 'Nosotros - CineBox';
ob_start();
?>
<div class="container aboutus-container py-5">
  <div class="row featurette align-items-center">
    <!-- Texto principal -->
    <div class="col-md-7 order-md-2 text-container">
      <h2 class="featurette-heading fw-bold text-warning">CineBox</h2>
      <p class="lead text-light">
        <strong>CineBox</strong> es una plataforma digital de streaming que te permite <b>rentar películas</b>
        desde la comodidad de tu hogar. Nuestro objetivo es ofrecer una experiencia moderna y accesible,
        con una amplia selección de títulos que van desde los clásicos del cine hasta los estrenos más recientes.
      </p>
      <p class="lead text-light">
        Inspirados en la evolución de la industria cinematográfica, buscamos combinar la nostalgia del videoclub
        con la tecnología actual, brindándote un servicio rápido, seguro y disponible en cualquier dispositivo.
      </p>
      <h4 class="mt-4 text-warning">Desarrollador</h4>
      <p class="lead text-light">Angelo Sotomayor Vargas</p>
    </div>
    <!-- Logo con fondo negro -->
    <div class="col-md-5 order-md-1 text-center">
      <div class="bg-dark p-4 rounded shadow d-inline-block">
        <img src="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo.png?alt=media&token=d1752ade-a672-4ef1-9e69-e44bcd26ee8c" 
        alt="Logo CineBox"
             class="img-fluid" style="max-width: 250px;">
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const btn = document.getElementById("myBtn");
    const modal = new bootstrap.Modal(document.getElementById("modalMapa"));
    btn?.addEventListener("click", () => modal.show());
  });
</script>
<?php
$contenido = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>