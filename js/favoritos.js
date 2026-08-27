/* ===============================
   FAVORITOS (vista "Mi Lista")
   Requiere shared.js (mostrarModalCarrito, actualizarBurbuja) y
   alquiler-simple.js (alquilarPelicula).
=============================== */
function toggleFavoritoLista(idPelicula, boton) {
    const card = boton.closest(".col-md-3");
    boton.disabled = true;
    boton.innerHTML =
        `<span class="spinner-border spinner-border-sm"></span>`;
    fetch(`${window.BASE_PATH}/favoritos-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) return;
            if (!data.favorito) {
                document.getElementById("favMensaje")
                    .innerText = "❌ Eliminado de tu lista";
                const modal = new bootstrap.Modal(
                    document.getElementById('modalFav')
                );
                modal.show();
                card.style.transition = "all .3s ease";
                card.style.opacity = "0";
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll(".col-md-3").length === 0)
                        location.reload();
                }, 300);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error actualizando favoritos");
        });
}

/* ===============================
   CARRITO
=============================== */
function toggleCarritoFavoritos(idPelicula, boton) {
    const original = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML =
        `<span class="spinner-border spinner-border-sm"></span>`;
    fetch(`${window.BASE_PATH}/carrito-toggle?id=${idPelicula}`)
        .then(r => r.json())
        .then(data => {
            boton.disabled = false;
            if (!data.ok) {
                boton.innerHTML = original;
                mostrarModalCarrito("Error procesando carrito", false);
                return;
            }
            if (data.en_carrito) {
                boton.classList.remove("btn-outline-warning");
                boton.classList.add("btn-warning");
                boton.innerHTML = `
                    <i class="bi bi-cart-fill"></i>
                    En carrito
                `;
                mostrarModalCarrito(
                    "Película añadida al carrito",
                    true
                );
            } else {
                boton.classList.remove("btn-warning");
                boton.classList.add("btn-outline-warning");
                boton.innerHTML = `
                    <i class="bi bi-cart"></i>
                    Agregar al Carrito
                `;
                mostrarModalCarrito(
                    "Película eliminada del carrito",
                    false
                );
            }
            if (data.cantidad !== undefined)
                actualizarBurbuja(data.cantidad);
        })
        .catch(err => {
            console.error(err);
            boton.disabled = false;
            boton.innerHTML = original;
            mostrarModalCarrito("Error de conexión", false);
        });
}
