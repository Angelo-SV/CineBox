# CineBox

**CineBox** es una aplicación web de alquiler de películas, construida con una arquitectura MVC en PHP y una base de datos Oracle Autonomous, desplegada en un servidor en la nube real.

---

## Sobre el proyecto

CineBox simula un servicio de alquiler de películas ("videoteca"): los visitantes exploran un catálogo público, los usuarios registrados pueden armar un carrito, alquilar películas por tiempo limitado, dejar reseñas y mantener una lista de favoritos, mientras que los administradores gestionan todo el catálogo (películas, actores, directores, estudios, géneros, proveedores) y los usuarios desde un panel dedicado.

Casi toda la lógica de negocio (expiración de alquileres, consolidación del carrito, filtrado del catálogo, reportes de administrador) vive directamente en **PL/SQL**, en procedimientos y paquetes almacenados, y PHP actúa principalmente como una capa de HTTP/sesión por encima.

---

## Funcionalidades principales

- **Catálogo público** con búsqueda en vivo, filtros por género/estudio y paginación — sin recargar la página, con endpoints AJAX.
- **Cuentas de usuario**: registro, inicio de sesión, y manejo de sesión.
- **Lista de favoritos** y **carrito de compras**, ambos persistidos por usuario en la base de datos.
- **Alquileres** con una ventana de expiración real (cuenta regresiva visible en "Mi Biblioteca"), expirados automáticamente por una tarea programada.
- **Reseñas y calificaciones** por película.
- **Panel de administración**: CRUD completo de películas, géneros, actores, directores, estudios y proveedores; gestión de usuarios con asignación de rol; una vista de "alquileres activos" por usuario.
- Interfaz responsiva con tema oscuro (Bootstrap 5 + JavaScript puro).

---

## Tecnologías utilizadas

**Backend**
- PHP 8.1, sin framework — un MVC pequeño hecho a mano (`routes/` → `controllers/` → `models/` → `views/`).
- OCI8 (la extensión nativa de Oracle para PHP) para todo el acceso a la base de datos.

**Base de datos**
- Oracle Autonomous Database (capa Always Free).
- Lógica de negocio implementada directamente en PL/SQL: procedimientos, paquetes, funciones, triggers almacenados, y un patrón basado en `SYS_REFCURSOR` para devolver resultados a PHP.

**Frontend**
- Bootstrap 5, Bootstrap Icons.
- JavaScript puro (AJAX con `fetch`, sin paso de compilación).
- DataTables para las tablas de listado del panel de administración.

**Infraestructura y nube**
- Alojado en una instancia de cómputo **Oracle Cloud Infrastructure (OCI)** de capa Always Free (Oracle Linux 9)
- **Apache + PHP-FPM**, conectado a la base de datos Autonomous mediante un Oracle Wallet (TLS mutuo)
- **HTTPS** vía Let's Encrypt / Certbot
- Dominio público vía **DuckDNS**
- Mantenimiento programado (expiración de alquileres) vía una tarea de `cron`

---

## Arquitectura en la nube

```
Navegador
   │  HTTPS
   ▼
VM de Oracle Cloud (OCI Always Free — Oracle Linux 9)
 ┣ Apache (proxy reverso, terminación TLS)
 ┗ PHP-FPM ── OCI8 / Oracle Wallet (mTLS) ──▶ Oracle Autonomous Database (Always Free)
```

Tanto el servidor de aplicación como la base de datos corren en la capa Always Free de Oracle Cloud — sin infraestructura de pago de por medio. Toda la conectividad hacia la base de datos pasa por una conexión TLS mutua basada en Wallet en vez de un puerto de red abierto, y el mismo código corre sin modificaciones tanto en Windows (desarrollo local) como en Linux (producción).

---

## Estructura del proyecto

```
CineBox/
 ├── controllers/     # Manejadores de petición, uno por recurso
 ├── models/          # Capa de acceso a datos — una clase por entidad, llama a PL/SQL
 ├── views/           # Plantillas PHP (public/, admin/, perfil/, layouts/, partials/)
 ├── routes/          # Enrutamiento estilo front-controller (web.php)
 ├── middleware/      # Protección de rutas exclusivas de administrador
 ├── config/          # Cargador de .env, bootstrap de base de datos, constantes compartidas
 ├── css/ · js/       # Recursos estáticos, sin empaquetador
 ├── cron/            # Scripts de mantenimiento programado
 └── database/        # Copia de referencia del esquema PL/SQL completo
```

---

## Capturas de pantalla

<p align="center">
  <img src="screenshots/catalogo.jpg" alt="Catálogo público" width="700"/>
</p>
<p align="center"><em>Catálogo público con búsqueda y filtros</em></p>

<p align="center">
  <img src="screenshots/detalle.jpg" alt="Detalle de película" width="700"/>
</p>
<p align="center"><em>Detalle de película, reparto y reseñas</em></p>

<p align="center">
  <img src="screenshots/admin.jpg" alt="Panel de administración" width="700"/>
</p>
<p align="center"><em>Panel de administración</em></p>

---

## Demo en vivo

CineBox está desplegado y disponible públicamente en:

**[https://cinebox-app.duckdns.org](https://cinebox-app.duckdns.org)**

---

## Multilanguage

Este README está disponible en:
- [English](README.md)
- [Español](README.es.md)
