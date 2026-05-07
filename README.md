# 🌿 EcoGotchi

> **Proyecto Final de Grado Superior — Desarrollo de Aplicaciones Web (DAW)**  
> Una mascota virtual con conciencia ecológica. Cuida a tu mascota, completa misiones sostenibles y mejora el planeta.

🔗 **Demo en producción:** [https://ecogotchi.vercel.app](https://ecogotchi.vercel.app)

---

## 📋 Índice

1. [Descripción del proyecto](#-descripción-del-proyecto)
2. [Funcionalidades principales](#-funcionalidades-principales)
3. [Tecnologías utilizadas](#-tecnologías-utilizadas)
4. [Arquitectura y estructura del proyecto](#-arquitectura-y-estructura-del-proyecto)
5. [Base de datos](#-base-de-datos)
6. [Instalación local (XAMPP)](#-instalación-local-xampp)
7. [Despliegue en producción — Supabase + Vercel](#-despliegue-en-producción--supabase--vercel)
8. [Autores](#-autores)

---

## 🌍 Descripción del proyecto

**EcoGotchi** es una aplicación web gamificada con temática ecológica inspirada en los Tamagotchi. El usuario registra una mascota virtual (animal, planta o fantasía) y debe cuidarla alimentándola, dejándola dormir, jugando con ella y manteniéndola limpia. A medida que el tiempo pasa, las estadísticas de la mascota se degradan automáticamente, obligando al usuario a interactuar con ella regularmente.

El elemento diferenciador es el **Entorno Ecológico**: cada mascota vive en un ecosistema cuyo nivel de salud (0–100) varía según las acciones del usuario. Completar **misiones sostenibles** del mundo real (reciclar, ahorrar agua, usar transporte público…) sube el nivel ecológico y otorga **Monedas Verdes** que se pueden gastar en la **Tienda de Impacto Real**, donde el usuario financia proyectos medioambientales reales como la reforestación del Amazonas, pozos de agua solar o santuarios de abejas.

---

## ✨ Funcionalidades principales

### 👤 Gestión de usuarios
- Registro e inicio de sesión con contraseña hasheada (bcrypt)
- Panel de cuenta propia (`mi_cuenta.php`)
- Baja de usuario con confirmación (elimina mascota y entorno asociados)

### 🐾 Mascota virtual
- Creación de mascota con nombre, tipo (animal / planta / fantasía) y color
- 4 estadísticas de necesidades: **Hambre**, **Sueño**, **Diversión** e **Higiene**
- Degradación automática basada en el tiempo real transcurrido desde la última visita
- Sistema de **basura aleatoria** en el entorno que debe ser recogida mediante drag & drop
- Si todas las estadísticas llegan a 0 → la mascota **muere** y pasa al historial
- Acciones disponibles: dar de comer 🍖, dormir 😴, jugar 🎾, bañar 🛁, limpiar basura 🗑️

### 🌱 Entorno ecológico
- Nivel ecológico de 0 a 100 con 4 estados visuales: **verde**, **normal**, **malo** y **extremo**
- Fondo del entorno que cambia dinámicamente según el estado (imágenes optimizadas JPEG)
- Barra de progreso con color gradiente (verde → amarillo → rojo)
- El entorno se degrada con el tiempo y se recupera completando misiones

### 🎯 Misiones sostenibles
- 3 misiones aleatorias diarias distintas por usuario
- Minijuegos para completarlas: **Clic rápido**, **Secuencia de teclas**, **Acertijo**
- Recompensa: puntos de eco + Monedas Verdes 💚
- Notificaciones toast animadas al completar (sin alert() del navegador)

### 🏪 Tienda de Impacto Real
- 10 acciones medioambientales reales financiables con Monedas Verdes
- Historial de contribuciones del usuario
- Iconos emoji renderizados con fuentes de sistema

### 📜 Historial de mascotas
- Registro de todas las mascotas que han vivido y muerto
- Tabla con estadísticas finales y fecha de defunción

---

## 🛠️ Tecnologías utilizadas

### Backend
| Tecnología | Uso |
|---|---|
| **PHP 8.2** | Lógica de servidor, controladores, modelos |
| **PDO** | Abstracción de base de datos (MySQL local / PostgreSQL producción) |
| **Supabase (PostgreSQL)** | Base de datos en producción |
| **bcrypt** | Hash seguro de contraseñas |

### Frontend
| Tecnología | Uso |
|---|---|
| **HTML5 + CSS3** | Estructura y estilos |
| **JavaScript (Vanilla)** | Minijuegos, drag & drop, toast notifications, fetch API |

### Infraestructura
| Tecnología | Uso |
|---|---|
| **Vercel** | Hosting serverless con runtime `vercel-php@0.7.2` |
| **Supabase** | PostgreSQL gestionado + Connection Pooler (PgBouncer) |
| **GitHub** | Control de versiones y CI/CD automático con Vercel |
| **XAMPP** | Entorno de desarrollo local (MySQL) |

---

## 🏗️ Arquitectura y estructura del proyecto

El proyecto sigue un patrón **MVC simplificado**:

```
ProyectoFinalFP/
│
├── api/
│   └── index.php           ← Router principal (Vercel serverless)
│
├── app/
│   ├── controllers/        ← Lógica de negocio
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── MascotaController.php
│   │   ├── TiendaController.php
│   │   └── UsuarioController.php
│   │
│   ├── models/             ← Consultas SQL (compatibles MySQL y PostgreSQL)
│   │   ├── Usuario.php
│   │   ├── Mascota.php
│   │   ├── Entorno.php
│   │   ├── Mision.php
│   │   ├── MisionCompletada.php
│   │   ├── Tienda.php
│   │   └── Clima.php
│   │
│   └── views/              ← Plantillas HTML/PHP
│       ├── auth/           (login, registro)
│       ├── mascota/        (dashboard, crear_mascota, historial)
│       ├── tienda.php
│       └── usuario/        (baja)
│
├── assets/
│   ├── css/styles.css      ← Estilos globales (dark mode, responsive)
│   ├── js/funciones.js     ← Minijuegos, drag & drop, toast
│   └── img/                ← Sprites de mascotas, fondos optimizados
│
├── config/
│   └── database.php        ← Conexión dual MySQL/PostgreSQL + ASSETS_URL
│
├── includes/
│   └── proteger.php        ← Middleware de sesión
│
├── sql/
│   ├── ecogotchi.sql       ← Schema MySQL (desarrollo local)
│   └── ecogotchi_supabase.sql  ← Schema PostgreSQL (producción)
│
├── vercel.json             ← Configuración del runtime PHP en Vercel
└── README.md
```

### Router Vercel (`api/index.php`)
Vercel no puede ejecutar PHP directamente fuera de `/api/`. El router:
1. **Sirve archivos estáticos** (CSS, JS, imágenes) mediante `readfile()` con el `Content-Type` correcto
2. **Enruta URLs limpias** (sin `.php`) al archivo PHP correspondiente
3. **Elimina extensiones** `.php` de la URL antes de hacer el match

---

## 🗄️ Base de datos

### Tablas principales

| Tabla | Descripción |
|---|---|
| `usuarios` | Registro de cuentas con contraseña bcrypt |
| `mascotas` | Estado actual de la mascota (estadísticas, tipo, color) |
| `entornos` | Nivel ecológico y estado del entorno por usuario |
| `misiones` | Catálogo de misiones ecológicas disponibles |
| `misiones_completadas` | Registro de misiones completadas por fecha y usuario |
| `tienda_acciones` | Acciones medioambientales disponibles en la tienda |
| `compras` | Historial de contribuciones de cada usuario |

### Compatibilidad MySQL ↔ PostgreSQL

El código usa un patrón de **conexión dual** en `config/database.php`:
- **Local (XAMPP):** conecta a MySQL en `127.0.0.1`
- **Producción (Vercel):** conecta a Supabase PostgreSQL vía Transaction Pooler

Las consultas SQL son compatibles con ambos motores. Diferencias manejadas:

| MySQL | PostgreSQL |
|---|---|
| `CURDATE()` | `CURRENT_DATE` |
| `DATE(campo)` | `campo::date` |
| `RAND()` | `RANDOM()` |

---

## 💻 Instalación local (XAMPP)

### Requisitos
- XAMPP con PHP 8.0+ y MySQL
- Git

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/MAFIA-DE-DAW/ProyectoFinalFP.git
cd ProyectoFinalFP

# 2. Copiar a htdocs de XAMPP
# (o clonar directamente en C:\xampp\htdocs\ProyectoFinalFP)
```

```sql
-- 3. Crear la base de datos en phpMyAdmin o MySQL CLI
CREATE DATABASE ecogotchi;
USE ecogotchi;
-- Importar: sql/ecogotchi.sql
```

```
# 4. Acceder desde el navegador
http://localhost/ProyectoFinalFP/
```

> **Nota:** El sistema detecta automáticamente el entorno local y usa MySQL + rutas `/ProyectoFinalFP/assets/` sin ninguna configuración adicional.

---

## 🚀 Despliegue en producción — Supabase + Vercel

### Arquitectura de producción

```
Usuario → Vercel CDN → api/index.php (PHP 8.2 serverless)
                            ↓
                    Supabase PostgreSQL
                    (Transaction Pooler IPv4)
```

### Variables de entorno en Vercel

Configura estas variables en **Vercel → Settings → Environment Variables**:

| Variable | Valor |
|---|---|
| `DB_HOST` | `aws-0-eu-west-1.pooler.supabase.com` |
| `DB_PORT` | `6543` |
| `DB_NAME` | `postgres` |
| `DB_USER` | `postgres.hndzzatpgvxsvqittrgf` |
| `DB_PASSWORD` | *(tu contraseña de Supabase)* |

### Base de datos Supabase

1. Crear proyecto en [supabase.com](https://supabase.com)
2. Ir a **SQL Editor** y ejecutar `sql/ecogotchi_supabase.sql`
3. Usar la URL del **Transaction Pooler** (puerto 6543, modo transacción) para evitar problemas de IPv6 en Vercel

### Notas técnicas

- **Runtime:** `vercel-php@0.7.2` (compatible con Node.js 20)
- **PDO Emulate Prepares:** activado (`true`) — obligatorio con PgBouncer en modo transacción
- **Imágenes de fondo:** convertidas de PNG (8MB) a JPEG optimizado (<0.5MB) para cumplir los límites de funciones serverless
- **ASSETS_URL:** constante dinámica que resuelve `/ProyectoFinalFP/assets` en local y `/assets` en Vercel

---

## 👥 Autores

**MAFIA-DE-DAW** — Proyecto Final de Grado Superior DAW

- 🔗 [Repositorio GitHub](https://github.com/MAFIA-DE-DAW/ProyectoFinalFP)
- 🌐 [Demo en vivo](https://ecogotchi.vercel.app)

---

*Desarrollado con 💚 y mucha conciencia ecológica.*
