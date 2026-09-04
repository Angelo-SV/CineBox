/* ===============================
   MOSTRAR/OCULTAR CONTRASEÑA
   Se aplica automáticamente a todo input[type=password] de la página
   (login, registro, alta/edición de usuarios en el panel admin).

   No se envuelve el input en un contenedor nuevo: varios formularios del
   proyecto ubican el mensaje de error (.invalid-feedback) usando
   input.nextElementSibling, así que insertar el botón justo después del
   input rompería esa relación. En vez de eso, el botón se agrega como
   último hijo del contenedor (después del .invalid-feedback existente) y
   se posiciona con JS encima del input.
   =============================== */
document.addEventListener('DOMContentLoaded', () => {
    const pares = [];

    document.querySelectorAll('input[type="password"]').forEach(input => {
        const parent = input.parentElement;
        if (!parent) return;

        if (getComputedStyle(parent).position === 'static') {
            parent.style.position = 'relative';
        }
        input.style.paddingRight = '2.5rem';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-toggle-password';
        btn.setAttribute('aria-label', 'Mostrar contraseña');
        btn.innerHTML = '<i class="bi bi-eye"></i>';

        btn.addEventListener('click', () => {
            const mostrar = input.type === 'password';
            input.type = mostrar ? 'text' : 'password';
            btn.innerHTML = mostrar
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
            btn.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });

        parent.appendChild(btn);
        pares.push({ input, btn });
    });

    if (pares.length === 0) return;

    function reposicionar() {
        pares.forEach(({ input, btn }) => {
            btn.style.top = (input.offsetTop + input.offsetHeight / 2) + 'px';
        });
    }

    reposicionar();
    window.addEventListener('resize', reposicionar);
    /* Los campos dentro de modales de Bootstrap (alta/edición de usuario)
       están ocultos con display:none hasta que se abren, así que su
       posición no se puede medir bien al cargar la página — se recalcula
       cada vez que un modal se muestra. */
    document.addEventListener('shown.bs.modal', reposicionar);
});
