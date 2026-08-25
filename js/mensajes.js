//Login usuario y/o contraseña inválidos
function modalLoginError() {
    $('#modalLoginError').modal('show');
}

function modalLoginError2() {
    $('#modalLoginError2').modal('show');
}

//Registro usuario nuevo (error correo ya existente)
function modalRegistroError($modal) {
    $('#modalRegistroError').modal('show');
}

function modalRegistroExito($modal) {
    $('#modalRegistroExito').modal('show');
}

//Actualizacion Perfil Usuario
function modalUserExito() {
    $('#modalUserExito').modal('show');
}

function modalUserError() {
    $('#modalUserError').modal('show');
}

function modalUserContrasena() {
    $('#modalUserContrasena').modal('show');
}

//Registro de pelicula
function modalPeliculaError($modal) {
    $('#modalPeliculaError').modal('show');
}

function modalPeliculaExito($modal) {
    $('#modalPeliculaExito').modal('show');
}

//Registro de compra
function modalCompraError() {
    $('#modalCompraError').modal('show');
}

function modalCompraExito() {
    $('#modalCompraExito').modal('show');
}


