/* ===============================
   FLUJO DE ALQUILER (versión con loader y manejo de errores)
   Usado por el catálogo público y el detalle de película. Requiere
   formatoCRC (shared.js) y los mismos IDs del modal de alquiler
   estándar. Cada página decide qué hacer cuando el modal de éxito se
   cierra (recargar el catálogo, recargar la página, etc.) — engancha
   tu propio listener a 'hidden.bs.modal' sobre #modalAlquilerExito.
   =============================== */
let peliculaAlquilerId = null;

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

function cerrarModalAlquiler() {
    const el = document.getElementById('modalAlquiler');
    const modal = bootstrap.Modal.getInstance(el);
    if (modal) modal.hide();
}

function mostrarModalExito() {
    limpiarModalAlquiler();
    new bootstrap.Modal(document.getElementById('modalAlquilerExito')).show();
}

function mostrarModalError(msg) {
    limpiarModalAlquiler();
    document.getElementById('errorAlquilerTexto').innerText = msg;
    new bootstrap.Modal(document.getElementById('modalAlquilerError')).show();
}

function abrirModalAlquiler(id) {
    peliculaAlquilerId = id;
    limpiarModalAlquiler();
    const modal = new bootstrap.Modal(document.getElementById('modalAlquiler'));
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
            document.getElementById('alqImagen').src = p.IMAGEN || 'img/no-image.jpg';
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
            mostrarModalError("No se pudo cargar la información de la película");
        });
}

document.addEventListener('change', e => {
    if (e.target.id === 'alqMetodo') {
        const box = document.getElementById('alqTarjetaBox');
        box.style.display = (e.target.value == 1) ? 'block' : 'none';
    }
});

const btnConfirmarAlquilerModal = document.getElementById('btnConfirmarAlquiler');
if (btnConfirmarAlquilerModal) {
    btnConfirmarAlquilerModal.addEventListener('click', () => {
        const btn = document.getElementById('btnConfirmarAlquiler');
        btn.disabled = true;
        btn.innerHTML = 'Procesando...';
        const metodo = document.getElementById('alqMetodo').value;
        fetch(window.BASE_PATH + '/alquilar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
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
}
