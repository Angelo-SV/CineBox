let peliculaAlquilerId = null;

/* ===============================
   ABRIR MODAL ALQUILER
=============================== */
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

            document.getElementById('alqImagen').src =
                p.IMAGEN;

            document.getElementById('alqTitulo').innerText =
                p.TITULO;

            document.getElementById('alqGenero').innerText =
                p.GENERO;

            document.getElementById('alqDirector').innerText =
                p.DIRECTOR;

            document.getElementById('alqPrecio').innerText =
                p.PRECIO;

            document.getElementById('alquilerLoader')
                .classList.add('d-none');

            document.getElementById('alquilerContenido')
                .classList.remove('d-none');

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
   CONFIRMAR ALQUILER
=============================== */
const btnConfirmar = document.getElementById('btnConfirmarAlquiler');

if (btnConfirmar) {

    btnConfirmar.addEventListener('click', () => {

        const btn = document.getElementById('btnConfirmarAlquiler');

        btn.disabled = true;
        btn.innerHTML = "Procesando...";

        const metodo =
            document.getElementById('alqMetodo').value;

        fetch(window.BASE_PATH + '/alquilar', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({

                    pelicula_id: peliculaAlquilerId,
                    metodo_pago: metodo

                })

            })
            .then(r => r.json())
            .then(data => {

                btn.disabled = false;
                btn.innerHTML = "Confirmar alquiler";

                const modal =
                    bootstrap.Modal.getInstance(
                        document.getElementById('modalAlquiler')
                    );

                modal.hide();

                if (data.ok) {

                    const exito = new bootstrap.Modal(
                        document.getElementById(
                            'modalAlquilerExito'
                        )
                    );

                    exito.show();

                } else {

                    document.getElementById(
                        'errorAlquilerTexto'
                    ).innerText = data.msg;

                    const error = new bootstrap.Modal(
                        document.getElementById(
                            'modalAlquilerError'
                        )
                    );

                    error.show();

                }

            });

    });

}