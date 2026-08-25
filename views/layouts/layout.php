<?php
$titulo = $titulo ?? 'CineBox';
$contenido = $contenido ?? '<p>Bienvenido a CineBox</p>';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="icon" href="https://firebasestorage.googleapis.com/v0/b/videotecacinebox.firebasestorage.app/o/Logos%2FcineBox_logo2.png?alt=media&token=25bcc6e4-890b-4557-95e0-07444f5d81f4" 
    type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Estilos globales -->
    <style>
      body {
        background-color: #121212;
        color: #e0e0e0;
      }
      main {
        flex: 1 0 auto;
        background-color: #3c3e40; /* gris oscuro */
        color: #f8f9fa;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
      .btn-warning:hover {
        background-color: #e0a800;
    }
    .page-link {
        background-color: #212529;
        color: #ffc107;
        border-color: #ffc107;
    }

    .page-item.active .page-link {
        background-color: #ffc107;
        color: #000;
        border-color: #ffc107;
    }
      .poster-img {
        height: 680px;
        object-fit: cover;
    }
    .skeleton-card {
    overflow: hidden;
  }

  .skeleton-img {
      height: 320px;
      background: linear-gradient(
          90deg,
          #2b2b2b 25%,
          #3a3a3a 37%,
          #2b2b2b 63%
      );
      background-size: 400% 100%;
      animation: skeleton-loading 1.4s ease infinite;
  }
  .skeleton-line {
      height: 14px;
      margin-bottom: 10px;
      border-radius: 4px;
      background: #444;
  }

  .skeleton-line.title {
      height: 18px;
      width: 70%;
  }

  .skeleton-line.short {
      width: 40%;
  }

  .skeleton-btn {
      height: 32px;
      width: 90px;
      margin: auto;
      border-radius: 4px;
      background: #444;
  }

  @keyframes skeleton-loading {
      0% {
          background-position: 100% 50%;
      }
      100% {
          background-position: 0 50%;
      }
  }
  .fav-container {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
  }

    .btn-fav {
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 50%;
  }
  .card {
    transition: transform .2s ease, box-shadow .2s ease;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 0 18px rgba(255,193,7,.25);
}
.carousel-caption {
    backdrop-filter: blur(4px);
}
.star {
    font-size: 26px;
    cursor: pointer;
    color: #ffc107;
}

.star.active {
    color: #ffc107;
}
.btn-fav {
    width: 38px;
    height: 38px;
    padding: 0;
}

.btn-fav i {
    font-size: 1.1rem;
}

/* Estado NO favorito */
.btn-fav-outline {
    background: transparent;
    border: 1px solid #ffc107;
    color: #ffc107;
}

/* Estado favorito */
.btn-fav-active {
    background: #ffc107;
    border: 1px solid #ffc107;
    color: #000;
}

/* Hover invertido */
.btn-fav-outline:hover {
    background: #ffc107;
    color: #000;
}

.btn-fav-active:hover {
    background: transparent;
    color: #ffc107;
}
/* ===============================
   SWITCH WARNING PERSONALIZADO
   =============================== */

   .switch-warning .form-check-input {
    background-color: #3c3e40;
    border-color: #ffc107;
    cursor: pointer;
}

.switch-warning .form-check-input:checked {
    background-color: #ffc107;
    border-color: #ffc107;
}

.switch-warning .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(255,193,7,.25);
}
.switch-warning .form-check-input {
    transition: all 0.2s ease;
}
.filtros-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  align-items: center;
}

/* ITEM GENERAL */
.filtro-item {
  background: #1c1f23;
  border: 1px solid #ffc107;
  border-radius: 8px;
  padding: 6px 10px;
  display: flex;
  align-items: center;
}

/* BUSCADOR */
.filtro-busqueda {
  flex: 1;
  min-width: 250px;
}

.filtro-busqueda input {
  background: transparent;
  border: none;
  color: white;
  outline: none;
  width: 100%;
}

.icono {
  margin-right: 8px;
  color: #ffc107;
}

/* SELECTS */
.filtro-item select {
  background: transparent;
  border: none;
  color: white;
  outline: none;
}

/* SWITCHES COMO CHIPS */
.filtros-switches {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.switch-chip {
  display: flex;
  align-items: center;
  gap: 5px;
  background: #2a2f35;
  border-radius: 20px;
  padding: 5px 10px;
  cursor: pointer;
  transition: 0.2s;
}

.switch-chip:hover {
  background: #3a4047;
}

.switch-chip input {
  display: none;
}

.switch-chip span {
  font-size: 0.85rem;
  color: #ffc107;
}

/* ACTIVO */
.switch-chip input:checked + span {
  color: #fff;
}

.filtro-item select {
  background-color: #1c1f23;
  color: white;
  border: none;
}

/* Opciones */
.filtro-item select option {
  background-color: #1c1f23;
  color: white;
}

.filtro-item select {
  color-scheme: dark;
}
.btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.btn-fav {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
    </style>
  </head>
  <body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <?php include __DIR__ . '/../partials/navbar.php';?>
    <!-- CONTENIDO PRINCIPAL -->
    <main>
      <div class="container">
        <?= $contenido ?>
      </div>
    </main>
    <!-- FOOTER -->
    <?php include __DIR__ . '/../partials/footer.php';?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>