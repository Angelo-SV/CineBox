/* =========================================
   peliculas-public.js
   Carga pública de películas (AJAX)
   ========================================= */
document.addEventListener('DOMContentLoaded', () => {
    cargarFiltros();
    cargarPeliculas();
    const buscador = document.getElementById('buscadorTitulo');
    if (buscador) {
        buscador.addEventListener('input', debounce(() => {
            paginaActual = 1;
            cargarPeliculas();
        }, 400));
    }
});
/* ===============================
   CONFIGURACIÓN
   =============================== */
const LIMITE = 6;
let paginaActual = 1;

function debounce(fn, delay) {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
    };
}
/* ===============================
   LOADER
   =============================== */
function mostrarSkeletons() {
    const contenedor = document.getElementById('contenedorPeliculas');
    contenedor.innerHTML = '';
    for (let i = 0; i < 6; i++) {
        contenedor.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card bg-dark border-warning h-100 shadow skeleton-card">
                    <div class="skeleton-img"></div>
                    <div class="card-body">
                        <div class="skeleton-line title"></div>
                        <div class="skeleton-line"></div>
                        <div class="skeleton-line short"></div>
                    </div>
                    <div class="card-footer border-warning">
                        <div class="skeleton-btn"></div>
                    </div>

                </div>
            </div>
        `;
    }
}
/* ===============================
   FORMATOS
   =============================== */
function formatoCRC(valor) {
    return new Intl.NumberFormat('es-CR', {
        style: 'currency',
        currency: 'CRC',
        minimumFractionDigits: 2
    }).format(valor);
}
/* ===============================
   FAVORITOS (con loader)
=============================== */
function toggleFavorito(idPelicula, boton) {
    const iconoOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
    `;
    fetch(`${window.BASE_PATH}/favoritos-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) {
                boton.innerHTML = iconoOriginal;
                return;
            }
            if (data.favorito) {
                boton.classList.remove('btn-fav-outline');
                boton.classList.add('btn-fav-active');
                boton.innerHTML = '<i class="bi bi-heart-fill"></i>';
            } else {
                boton.classList.remove('btn-fav-active');
                boton.classList.add('btn-fav-outline');
                boton.innerHTML = '<i class="bi bi-heart"></i>';
            }
            const msg = data.favorito ?
                "❤️ Añadido a tu lista" :
                "❌ Eliminado de tu lista";
            document.getElementById("favMensaje").innerText = msg;
            const modal = new bootstrap.Modal(
                document.getElementById('modalFav')
            );
            modal.show();
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.innerHTML = iconoOriginal;
            document.getElementById("favMensaje").innerText =
                "Error de conexión";
            const modal = new bootstrap.Modal(
                document.getElementById('modalFav')
            );
            modal.show();
        });
}
/* ===============================
   CARRITO TOGGLE (con loader)
=============================== */
function toggleCarrito(idPelicula, boton) {
    const iconoOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
    `;
    fetch(`${window.BASE_PATH}/carrito-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) {
                boton.innerHTML = iconoOriginal;
                mostrarModalCarrito("Error procesando carrito", false);
                return;
            }
            /* ===============================
               ACTUALIZAR BURBUJA
            =============================== */
            actualizarBurbuja(data.cantidad);
            /* ===============================
               CAMBIO VISUAL DEL BOTÓN
            =============================== */
            if (data.en_carrito) {
                boton.classList.remove('btn-outline-warning');
                boton.classList.add('btn-warning');
                boton.innerHTML = '<i class="bi bi-cart-fill"></i>';
                mostrarModalCarrito(
                    "Película añadida al carrito correctamente",
                    true
                );
            } else {

                boton.classList.remove('btn-warning');
                boton.classList.add('btn-outline-warning');
                boton.innerHTML = '<i class="bi bi-cart"></i>';
                mostrarModalCarrito(
                    "Película eliminada del carrito",
                    false
                );
            }
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.innerHTML = iconoOriginal;
            mostrarModalCarrito("Error de conexión", false);
        });
}

function actualizarBurbuja(cantidad) {
    const cartCount = document.getElementById("cart-count");
    if (!cartCount) return;
    cartCount.textContent = cantidad;
    cartCount.style.display = cantidad == 0 ? "none" : "inline-block";
}
/* ===============================
   CARGAR PELÍCULAS
   =============================== */
function cargarPeliculas() {
    mostrarSkeletons();
    const generoSelect = document.getElementById('filtroGenero');
    const estudioSelect = document.getElementById('filtroEstudio');
    const buscador = document.getElementById('buscadorTitulo');
    const switchLista = document.getElementById('switchMiLista');
    const switchAlquiladas = document.getElementById('switchAlquiladas');
    const switchCarrito = document.getElementById('switchCarrito');
    const texto = buscador ? buscador.value.trim() : '';
    const genero = generoSelect ? generoSelect.value : 0;
    const estudio = estudioSelect ? estudioSelect.value : 0;
    const soloLista = switchLista && switchLista.checked ? 1 : 0;
    const soloAlquiladas = switchAlquiladas && switchAlquiladas.checked ? 1 : 0;
    const soloCarrito = switchCarrito && switchCarrito.checked ? 1 : 0;
    fetch(`${window.BASE_PATH}/peliculas-publicas?pagina=${paginaActual}` +
            `&genero=${genero}` +
            `&estudio=${estudio}` +
            `&q=${encodeURIComponent(texto)}` +
            `&solo_lista=${soloLista}` +
            `&solo_alquiladas=${soloAlquiladas}` +
            `&solo_carrito=${soloCarrito}`)
        .then(r => r.json())
        .then(data => {
            renderPeliculas(data.peliculas, texto);
            renderPaginacion(data.total);
        })
        .catch(err => {
            console.error(err);
            mostrarError('Error cargando películas');
        });
}
/* ===============================
   RENDER PELÍCULAS
   =============================== */
function renderPeliculas(peliculas, textoBusqueda = '') {
    console.log(peliculas);
    const contenedor = document.getElementById('contenedorPeliculas');
    contenedor.innerHTML = '';
    if (!peliculas || peliculas.length === 0) {
        contenedor.innerHTML = `
            <div class="col-12 text-center text-light">
                No hay coincidencias para: "<strong>${textoBusqueda}</strong>"
            </div>`;
        return;
    }
    peliculas.forEach(p => {
                /* ===============================
                   BOTÓN FAVORITOS
                   =============================== */
                let botonFavorito;
                if (!window.usuarioLogueado) {
                    botonFavorito = `
                   <button class="btn btn-sm btn-outline-secondary btn-fav"
                           disabled>
                       <i class="bi bi-heart"></i>
                   </button>`;
                } else {
                    const esFav = Number(p.ES_FAVORITO) === 1;
                    botonFavorito = `
                   <button class="btn btn-sm btn-fav ${esFav ? 'btn-fav-active' : 'btn-fav-outline'}"
                           onclick="toggleFavorito(${p.ID_PELICULA}, this)">
                       <i class="bi ${esFav ? 'bi-heart-fill' : 'bi-heart'}"></i>
                   </button>`;
                }
                /* ===============================
                BOTÓN CARRITO
                =============================== */
                let botonCarrito = '';
                if (window.usuarioLogueado && Number(p.ES_ALQUILADA) !== 1) {
                    const enCarrito = Number(p.EN_CARRITO) === 1;
                    botonCarrito = `
                    <button class="btn btn-sm ${enCarrito ? 'btn-warning' : 'btn-outline-warning'}"
                            onclick="toggleCarrito(${p.ID_PELICULA}, this)">
                        <i class="bi ${enCarrito ? 'bi-cart-fill' : 'bi-cart'}"></i>
                    </button>`;
                }
                contenedor.innerHTML += `
            <div class="col-md-4 mb-4">
                <div class="card bg-dark border-warning h-100 shadow position-relative">
                ${Number(p.ES_ALQUILADA) === 1 ? `
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                        🎟️ Alquilada
                    </span>
                ` : ''}
                    <!-- FAVORITO -->
                    <div class="fav-container">
                        ${botonFavorito}
                    </div>
                    <!-- POSTER -->
                    <img src="${p.IMAGEN || 'img/no-image.jpg'}"
                         class="card-img-top poster-img"
                         alt="${p.TITULO}">
                    <!-- BODY -->
                    <div class="card-body">
                        <h5 class="card-title text-warning">
                            ${p.TITULO}
                        </h5>
                        <p class="card-text text-light small mb-2">
                            <strong>Género:</strong> ${p.GENERO}<br>
                            <strong>Estudio:</strong> ${p.ESTUDIO}
                        </p>
                        <p class="fw-bold text-warning fs-5">
                            ${formatoCRC(p.PRECIO)}
                        </p>
                    </div>
                    <!-- FOOTER -->
                    <div class="card-footer text-center border-warning d-flex gap-2 justify-content-center">
                        <a href="pelicula?id=${p.ID_PELICULA}"
                        class="btn btn-outline-warning btn-sm">
                            Ver detalle
                        </a>
                        ${botonCarrito}
                        ${window.usuarioLogueado ? (
                            Number(p.ES_ALQUILADA) === 1 && p.TRANSACCION_ACTIVA
                                ? `
                                <a href="ver/${p.TRANSACCION_ACTIVA}"
                                class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-play-fill"></i>Ver
                                </a>`
                                : `
                                <button class="btn btn-warning btn-sm"
                                        onclick="abrirModalAlquiler(${p.ID_PELICULA})">
                                    <i class="bi bi-ticket-perforated-fill"></i> Alquilar
                                </button>`
                        ) : ''}

                    </div>
                </div>
            </div>
        `;
    });
}
/* ===============================
   PAGINACIÓN
   =============================== */
function renderPaginacion(total) {
    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(total / LIMITE);
    if (totalPaginas <= 1) return;
    for (let i = 1; i <= totalPaginas; i++) {
        paginacion.innerHTML += `
            <li class="page-item ${i === paginaActual ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cambiarPagina(${i})">
                    ${i}
                </a>
            </li>
        `;
    }
}
function cambiarPagina(pagina) {
    paginaActual = pagina;
    cargarPeliculas();
}
/* ===============================
   FILTROS
   =============================== */
function aplicarFiltros() {
    paginaActual = 1;
    cargarPeliculas();
}
/* ===============================
   ERROR UI
   =============================== */
function mostrarError(mensaje) {
    const contenedor = document.getElementById('contenedorPeliculas');
    contenedor.innerHTML = `
        <div class="col-12 text-center text-danger">
            ${mensaje}
        </div>`;
}
function cargarFiltros() {
    fetch(window.BASE_PATH + '/peliculas-filtros')
        .then(r => r.json())
        .then(data => {
            if (!data.ok) return;
            const genero = document.getElementById('filtroGenero');
            const estudio = document.getElementById('filtroEstudio');
            data.generos.forEach(g => {
                genero.innerHTML += `<option value="${g.ID_GENERO}">${g.DESCRIPCION}</option>`;
            });
            data.estudios.forEach(e => {
                estudio.innerHTML += `<option value="${e.ID_ESTUDIO}">${e.NOMBRE}</option>`;
            });
        });
}
/* ===============================
   MODAL MENSAJE CARRITO
=============================== */
function mostrarModalCarrito(mensaje, agregado) {
    const texto = document.getElementById('carritoMensajeTexto');
    texto.innerHTML = mensaje;
    const btnIr = document.getElementById('btnIrCarrito');
    if (agregado) {
        btnIr.style.display = 'inline-block';
    } else {
        btnIr.style.display = 'none';
    }
    const modal = new bootstrap.Modal(
        document.getElementById('modalCarritoMsg')
    );
    modal.show();
}
let peliculaAlquilerId = null;
/* ===============================
   LIMPIAR MODAL TRANSACCIÓN
=============================== */
function limpiarModalAlquiler() {
    document.getElementById('alquilerLoader').classList.remove('d-none');
    document.getElementById('alquilerContenido').classList.add('d-none');
    document.getElementById('alqImagen').src = '';
    document.getElementById('alqTitulo').innerText = '';
    document.getElementById('alqGenero').innerText = '';
    document.getElementById('alqDirector').innerText = '';
    document.getElementById('alqPrecio').innerText = '';
    document.getElementById('alqMetodo').value = 1;
    document.getElementById('alqTarjetaBox').style.display = 'block';
    const btn = document.getElementById('btnConfirmarAlquiler');
    btn.disabled = false;
    btn.innerHTML = 'Confirmar alquiler';
    btn.classList.remove('d-none');
}
/* ===============================
   ABRIR MODAL ALQUILER
=============================== */
function abrirModalAlquiler(id) {
    peliculaAlquilerId = id;
    limpiarModalAlquiler();
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquiler')
    );
    modal.show();
    fetch(`${window.BASE_PATH}/pelicula-json?id=${id}`)
        .then(r => {
            if (!r.ok) throw new Error('HTTP error');
            return r.json();
        })
        .then(data => {
            if (!data.ok || !data.pelicula) {
                throw new Error('Respuesta inválida');
            }
            const p = data.pelicula;
            document.getElementById('alqImagen').src =
                p.IMAGEN || 'img/no-image.jpg';
            document.getElementById('alqTitulo').innerText = p.TITULO;
            document.getElementById('alqGenero').innerText = p.GENERO;
            document.getElementById('alqDirector').innerText = p.DIRECTOR;
            document.getElementById('alqPrecio').innerText = formatoCRC(p.PRECIO);
            document.getElementById('alquilerLoader').classList.add('d-none');
            document.getElementById('alquilerContenido').classList.remove('d-none');
        })
        .catch(err => {
            console.error(err);
            cerrarModalAlquiler();
            mostrarModalError(
                "No se pudo cargar la información de la película"
            );
        });
}
/* ===============================
   CAMBIO MÉTODO PAGO
=============================== */
document.addEventListener('change', e => {

    if (e.target.id === 'alqMetodo') {
        const box = document.getElementById('alqTarjetaBox');
        box.style.display = (e.target.value == 1) ? 'block' : 'none';
    }
});
/* ===============================
   CERRAR MODAL TRANSACCIÓN
=============================== */
function cerrarModalAlquiler() {
    const el = document.getElementById('modalAlquiler');
    const modal = bootstrap.Modal.getInstance(el);
    if (modal) modal.hide();
}
/* ===============================
   MOSTRAR MODAL ÉXITO
=============================== */
function mostrarModalExito() {
    limpiarModalAlquiler();
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquilerExito')
    );
    modal.show();
}
/* ===============================
   MOSTRAR MODAL ERROR
=============================== */
function mostrarModalError(msg) {
    limpiarModalAlquiler();
    document.getElementById('errorAlquilerTexto').innerText = msg;
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquilerError')
    );
    modal.show();
}
/* ===============================
   CONFIRMAR ALQUILER
=============================== */
document.getElementById('btnConfirmarAlquiler')
.addEventListener('click', () => {
    const btn = document.getElementById('btnConfirmarAlquiler');
    btn.disabled = true;
    btn.innerHTML = 'Procesando...';
    const metodo = document.getElementById('alqMetodo').value;
    fetch(window.BASE_PATH + '/alquilar', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            pelicula_id: peliculaAlquilerId,
            metodo_pago: metodo
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = 'Confirmar alquiler';
        cerrarModalAlquiler();
        if (data.ok) {
            mostrarModalExito();
        } else {
            mostrarModalError(
                data.msg || data.error || 'Ocurrió un error procesando el alquiler'
            );
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = 'Confirmar alquiler';
        cerrarModalAlquiler();
        mostrarModalError('Error de conexión con el servidor');
    });
});
/* ===============================
   RECARGAR TARJETAS AL CERRAR ÉXITO
=============================== */
document.addEventListener('DOMContentLoaded', () => {
    const modalExito = document.getElementById('modalAlquilerExito');
    if (modalExito) {
        modalExito.addEventListener('hidden.bs.modal', () => {
            cargarPeliculas();
        });
    }
});