# CineBox

**CineBox** is a full-stack movie rental web app built with a hand-rolled PHP MVC architecture and an Oracle Autonomous Database, deployed on a real cloud server.
This project is part of my portfolio and was developed to practice **backend architecture, direct PL/SQL database design, and cloud deployment** — end to end, without a framework, without an ORM, and without a PaaS doing the infrastructure work for me.

---

## About the project

CineBox simulates a movie rental service ("videoteca"): visitors browse a public catalog, registered users can build a cart, rent movies for a limited time, leave reviews, and keep a personal list of favorites, while administrators manage the full catalog (movies, actors, directors, studios, genres, providers) and users from a dedicated panel.

It's deliberately built **without a framework**, so that every layer — routing, session handling, database access, business logic — is something I designed and wired together myself instead of configuring a starter template. Almost all business logic (rental expiration, cart consolidation, catalog filtering, admin reporting) lives directly in **PL/SQL** stored procedures and packages, with PHP acting mostly as a thin HTTP/session layer on top of it.

---

## Main features

- **Public catalog** with live search, filtering by genre/studio, and pagination — no page reloads, backed by AJAX endpoints.
- **Accounts**: registration, login, and session handling hardened against showing authenticated pages via the browser's back button after logout.
- **Favorites list** and **shopping cart**, both persisted per user in the database.
- **Rentals** with a real expiration window (countdown shown in "My Library"), automatically expired by a scheduled job.
- **Reviews & ratings** per movie.
- **Admin panel**: full CRUD for movies, genres, actors, directors, studios, and providers; user management with role assignment; a per-user "active rentals" view.
- Responsive, dark-themed UI (Bootstrap 5 + vanilla JavaScript).

---

## Tech stack

**Backend**
- PHP 8.1, no framework — a small hand-rolled MVC (`routes/` → `controllers/` → `models/` → `views/`)
- OCI8 (PHP's native Oracle extension) for all database access

**Database**
- Oracle Autonomous Database (Always Free tier)
- Business logic implemented directly in PL/SQL: stored procedures, packages, functions, triggers, and a `SYS_REFCURSOR`-based pattern for returning result sets to PHP

**Frontend**
- Bootstrap 5, Bootstrap Icons
- Vanilla JavaScript (`fetch`-based AJAX, no build step)
- DataTables for the admin panel's listing tables

**Infrastructure & cloud**
- Hosted on an **Oracle Cloud Infrastructure (OCI)** Always Free compute instance (Oracle Linux 9)
- **Apache + PHP-FPM**, connected to the Autonomous Database through an Oracle Wallet (mutual TLS)
- **HTTPS** via Let's Encrypt / Certbot
- Public domain via **DuckDNS**
- Scheduled maintenance (rental expiration) via a `cron` job

---

## Cloud architecture

```
Browser
   │  HTTPS
   ▼
Oracle Cloud VM (OCI Always Free — Oracle Linux 9)
 ┣ Apache (reverse proxy, TLS termination)
 ┗ PHP-FPM ── OCI8 / Oracle Wallet (mTLS) ──▶ Oracle Autonomous Database (Always Free)
```

The application server and the database both run on Oracle Cloud's Always Free tier — no paid infrastructure involved. All connectivity to the database goes through a Wallet-based mutual-TLS connection rather than an open network port, and the same codebase runs unmodified on both Windows (local development) and Linux (production).

---

## Project structure

```
CineBox/
 ├── controllers/     # Request handlers, one per resource
 ├── models/          # Database access layer — one class per entity, calls PL/SQL
 ├── views/           # PHP templates (public/, admin/, perfil/, layouts/, partials/)
 ├── routes/          # Front-controller style routing (web.php)
 ├── middleware/      # Admin-only route guard
 ├── config/          # .env loader, DB bootstrap, shared constants
 ├── css/ · js/       # Static assets, no bundler
 ├── cron/            # Scheduled maintenance scripts
 └── database/        # Reference copy of the full PL/SQL schema
```

---

## Screenshots

<p align="center">
  <img src="screenshots/catalogo.png" alt="Public catalog" width="700"/>
</p>
<p align="center"><em>Public catalog with search and filters</em></p>

<p align="center">
  <img src="screenshots/detalle.png" alt="Movie detail" width="700"/>
</p>
<p align="center"><em>Movie detail, cast, and reviews</em></p>

<p align="center">
  <img src="screenshots/admin.png" alt="Admin panel" width="700"/>
</p>
<p align="center"><em>Admin panel</em></p>

---

## Live demo

CineBox is deployed and publicly reachable at:

**[https://cinebox-app.duckdns.org](https://cinebox-app.duckdns.org)**

---

## Running it locally

This project connects to a private Oracle Autonomous Database instance and requires an Oracle Wallet, so it can't be run out of the box without a database of your own — but the setup is:

1. Clone the repository and point Apache/PHP at its root (no build step, no Composer dependency).
2. Copy `.env.example` to `.env` and fill in your own Oracle credentials and TNS alias.
3. Point `TNS_ADMIN` at your Oracle Wallet directory.
4. Import `database/cinebox_schema.sql` into your own Oracle database.

---

## Multilanguage

This README is available in:
- [English](README.md)
- [Español](README.es.md)
