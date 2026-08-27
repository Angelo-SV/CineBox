/* ===============================
   FLUJO DE ALQUILER (versión simple)
   Usado por las páginas de favoritos e historial: abre el modal de
   alquiler con los datos de la película y confirma el alquiler.
   Requiere que la vista tenga los mismos IDs que el modal de alquiler
   estándar (modalAlquiler, alqImagen, alqTitulo, ..., btnConfirmarAlquiler).
   =============================== */
let peliculaAlquilerId = null;

function alquilarPelicula(id) {
    peliculaAlquilerId = id;
    const modal = new bootstrap.Modal(
        document.getElementById('modalAlquiler')
    );
    modal.show();
    fetch(`${window.BASE_PATH}/pelicula-json?id=${id}`)
        .then(r => r.json())
        .then(data => {
            if (!data.ok) throw "error";
            const p = data.pelicula;
            document.getElementById('alqImagen').src = p.IMAGEN;
            document.getElementById('alqTitulo').innerText = p.TITULO;
            document.getElementById('alqGenero').innerText = p.GENERO;
            document.getElementById('alqDirector').innerText = p.DIRECTOR;
            document.getElementById('alqPrecio').innerText = p.PRECIO;
            document.getElementById('alquilerLoader').classList.add('d-none');
            document.getElementById('alquilerContenido').classList.remove('d-none');
        });
}

document.addEventListener('change', e => {
    if (e.target.id === 'alqMetodo') {
        const box = document.getElementById('alqTarjetaBox');
        box.style.display = (e.target.value == 1) ? 'block' : 'none';
    }
});

const btnConfirmarAlquilerSimple = document.getElementById('btnConfirmarAlquiler');
if (btnConfirmarAlquilerSimple) {
    btnConfirmarAlquilerSimple.addEventListener('click', () => {
        const btn = document.getElementById('btnConfirmarAlquiler');
        btn.disabled = true;
        btn.innerHTML = "Procesando...";
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
                btn.innerHTML = "Confirmar alquiler";
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('modalAlquiler')
                );
                modal.hide();
                if (data.ok) {
                    new bootstrap.Modal(
                        document.getElementById('modalAlquilerExito')
                    ).show();
                } else {
                    document.getElementById('errorAlquilerTexto').innerText = data.msg;
                    new bootstrap.Modal(
                        document.getElementById('modalAlquilerError')
                    ).show();
                }
            });
    });
}
