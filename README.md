# 🌿 EcoGotchi

> **Proyecto Final de Grado Superior — Desarrollo de Aplicaciones Web (DAW)**  
> Una mascota virtual con conciencia ecológica. Cuida a tu mascota, completa misiones sostenibles y mejora el planeta.

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
- Sistema de **basura aleatoria** en el entorno que debe ser recogida
- Si todas las estadísticas llegan a 0 → la mascota **muere** y pasa al historial
- Acciones disponibles: dar de comer 🍖, dormir 😴, jugar 🎾, bañar 🛁, limpiar basura 🗑️

### 🌱 Entorno ecológico
- Nivel ecológico de 0 a 100 con 4 estados visuales: **verde**, **normal**, **malo** y **extremo**
- Fondo del entorno que cambia dinámicamente según el estado
- Barra de progreso con color gradiente (verde → amarillo → rojo)
- El entorno se degrada con el tiempo y se recupera completando misiones

### 🎯 Misiones sostenibles
- Catálogo de 20 misiones ecológicas reales (reciclar plástico, usar transporte público, plantar una planta…)
- Cada misión otorga puntos ecológicos y **Monedas Verdes**
- Las misiones completadas se registran con fecha

### 🛒 Tienda de Impacto Real
- 13 acciones de impacto real disponibles para comprar con Monedas Verdes
- Proyectos como: Punto de Reciclaje, Plantar un Roble, Santuario de Abejas, Reforestar el Amazonas, Pozos de Agua Solar…
- Cada acción muestra su impacto cuantificado en CO2, agua o biodiversidad

### 📜 Historial de mascotas
- Registro de todas las mascotas que han muerto o han sido eliminadas
- Muestra el motivo del fin (muerte o baja del usuario) y la fecha

### 🌤️ Clima en tiempo real
- Integración con API de clima que muestra el estado meteorológico actual

---

## 🛠 Tecnologías utilizadas

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.2 |
| Base de datos | MySQL / MariaDB → **Supabase (PostgreSQL)** |
| ORM / acceso a datos | PDO (PHP Data Objects) |
| Frontend | HTML5, CSS3 (Vanilla), JavaScript |
| Servidor local | XAMPP (Apache + MariaDB) |
| Despliegue BD | **Supabase** |
| Despliegue app | **Vercel** |
| Control de versiones | Git + GitHub |

---

## 🏗 Arquitectura y estructura del proyecto

El proyecto sigue el patrón **MVC (Modelo–Vista–Controlador)**:

```
ProyectoFinalFP/
│
├── index.php                    # Punto de entrada
├── login.php                    # Enrutador → AuthController
├── registro.php                 # Enrutador → AuthController
├── logout.php                   # Cierre de sesión
├── dashboard.php                # Enrutador → DashboardController
├── crear_mascota.php            # Enrutador → MascotaController
├── accion_mascota.php           # Enrutador → MascotaController (acciones)
├── completar_mision.php         # Enrutador → MisionController
├── tienda.php                   # Enrutador → TiendaController
├── comprar_accion.php           # Enrutador → TiendaController (compra)
├── limpiar_basura.php           # Acción limpiar entorno
├── mi_cuenta.php                # Enrutador → UsuarioController
├── baja_usuario.php             # Enrutador → UsuarioController (baja)
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php       # Login y registro
│   │   ├── DashboardController.php  # Lógica principal del dashboard
│   │   ├── MascotaController.php    # Creación y acciones de mascota
│   │   ├── MisionController.php     # Completar misiones
│   │   ├── TiendaController.php     # Tienda de impacto real
│   │   ├── HistorialController.php  # Historial de mascotas
│   │   └── UsuarioController.php    # Gestión de cuenta y baja
│   │
│   ├── models/
│   │   ├── Mascota.php          # CRUD + degradación + historial
│   │   ├── Entorno.php          # Nivel ecológico + estados visuales
│   │   ├── Mision.php           # Consulta de misiones disponibles
│   │   ├── MisionCompletada.php # Registro de misiones completadas
│   │   ├── Tienda.php           # Acciones de la tienda y compras
│   │   ├── Usuario.php          # Registro, login, monedas, baja
│   │   └── Clima.php            # Integración API del tiempo
│   │
│   └── views/
│       ├── auth/
│       │   ├── login.php
│       │   └── registro.php
│       ├── mascota/
│       │   ├── dashboard.php    # Vista principal con mascota y entorno
│       │   ├── crear_mascota.php
│       │   └── historial.php
│       ├── tienda.php
│       └── usuario/
│           └── baja.php
│
├── config/
│   └── database.php             # Conexión PDO a la base de datos
│
├── includes/
│   └── proteger.php             # Middleware de protección de rutas
│
├── assets/
│   ├── css/styles.css
│   ├── js/funciones.js
│   └── img/                     # Mascotas, fondos e iconos
│
└── sql/
    └── ecogotchi.sql            # Volcado completo de la base de datos
```

---

## 🗄 Base de datos

La base de datos se llama `ecogotchi` y tiene las siguientes tablas:

### Diagrama de tablas

```
usuarios ──────┬──── mascotas
               ├──── mascotas_historial
               ├──── entorno
               ├──── misiones_completadas ──── misiones
               └──── compras ──────────────── tienda_acciones
```

### Descripción de tablas

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Datos de registro: nombre, email, password (bcrypt), monedas_verdes |
| `mascotas` | Mascota activa del usuario: nombre, tipo, color, hambre, sueño, diversión, higiene, salud, basura |
| `mascotas_historial` | Registro de mascotas finalizadas con motivo y fecha de fin |
| `entorno` | Nivel ecológico (0–100) y estado del entorno por usuario |
| `misiones` | Catálogo de 20 misiones sostenibles con título, descripción y recompensas |
| `misiones_completadas` | Registro de qué misión completó cada usuario y cuándo |
| `tienda_acciones` | Acciones de impacto real disponibles en la tienda con coste en Monedas Verdes |
| `compras` | Historial de compras de acciones de la tienda por usuario |

> El archivo `sql/ecogotchi.sql` contiene el volcado completo con estructura y datos de ejemplo.

---

## 💻 Instalación local (XAMPP)

### Requisitos
- XAMPP con PHP 8.x y MariaDB
- Navegador web moderno

### Pasos

1. **Clonar el repositorio** en la carpeta `htdocs` de XAMPP:
   ```bash
   git clone https://github.com/MAFIA-DE-DAW/ProyectoFinalFP.git
   cd ProyectoFinalFP
   ```

2. **Importar la base de datos** desde phpMyAdmin:
   - Crear una base de datos llamada `ecogotchi`
   - Importar el archivo `sql/ecogotchi.sql`

3. **Verificar la conexión** en `config/database.php`:
   ```php
   $cadena_conexion = 'mysql:dbname=ecogotchi;host=127.0.0.1;charset=utf8';
   $usuario = 'root';
   $clave = '';
   ```

4. **Arrancar XAMPP** (Apache + MySQL) y acceder a:
   ```
   http://localhost/ProyectoFinalFP/
   ```

---

## 🚀 Despliegue en producción — Supabase + Vercel

### 🗄 Paso 1 — Base de datos en Supabase

1. Crear cuenta en [supabase.com](https://supabase.com) y crear un nuevo proyecto.

2. En el panel de Supabase ir a **SQL Editor** y ejecutar el contenido de `sql/ecogotchi.sql`.
   > ⚠️ Supabase usa **PostgreSQL**. Es posible que necesites adaptar algunas sentencias MySQL (por ejemplo, `AUTO_INCREMENT` → `SERIAL`, tipos `tinyint` → `boolean`).

3. Obtener las credenciales de conexión desde **Settings → Database**:
   - **Host**: `db.xxxxxxxxxxxx.supabase.co`
   - **Puerto**: `5432`
   - **Base de datos**: `postgres`
   - **Usuario**: `postgres`
   - **Contraseña**: la que configuraste al crear el proyecto

4. Actualizar `config/database.php` con la cadena de conexión de Supabase:
   ```php
   $host     = getenv('DB_HOST')     ?: 'db.xxxxxxxxxxxx.supabase.co';
   $dbname   = getenv('DB_NAME')     ?: 'postgres';
   $usuario  = getenv('DB_USER')     ?: 'postgres';
   $clave    = getenv('DB_PASSWORD') ?: 'tu_contraseña';
   $puerto   = getenv('DB_PORT')     ?: '5432';

   $cadena_conexion = "pgsql:host=$host;port=$puerto;dbname=$dbname;sslmode=require";

   try {
       $bd = new PDO($cadena_conexion, $usuario, $clave);
       $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die('Error con la base de datos: ' . $e->getMessage());
   }
   ```

### ☁️ Paso 2 — Despliegue en Vercel

> ⚠️ Vercel es principalmente una plataforma para aplicaciones Node.js/Next.js. Para PHP necesitas usar **Vercel con el runtime de PHP** o bien un archivo `vercel.json` que configure el servidor PHP.

1. Instalar Vercel CLI:
   ```bash
   npm install -g vercel
   ```

2. Crear el archivo `vercel.json` en la raíz del proyecto:
   ```json
   {
     "functions": {
       "api/*.php": {
         "runtime": "vercel-php@0.6.0"
       }
     },
     "routes": [
       { "src": "/(.*)", "dest": "/index.php" }
     ]
   }
   ```

3. Configurar las **variables de entorno** en el panel de Vercel (Settings → Environment Variables):
   ```
   DB_HOST=db.xxxxxxxxxxxx.supabase.co
   DB_NAME=postgres
   DB_USER=postgres
   DB_PASSWORD=tu_contraseña_supabase
   DB_PORT=5432
   ```

4. Desplegar:
   ```bash
   vercel --prod
   ```

5. Vercel proporcionará una URL pública tipo `https://ecogotchi.vercel.app` 🎉

---

## 👥 Autores

Proyecto desarrollado por el equipo **MAFIA-DE-DAW** para el **Proyecto Final del Grado Superior en Desarrollo de Aplicaciones Web**.

| Nombre | Rol |
|--------|-----|
| Amanda | Desarrollo frontend y backend / vistas /  Arquitectura MVC |
| Paloma | Desarrollo backend y frontend / modelos |
| Jose Manuel (Chema) | Arquitectura MVC / base de datos  / Desarrollo fullstack|

---

<div align="center">

**🌿 EcoGotchi — Cuida tu mascota, salva el planeta 🌍**

[![GitHub](https://img.shields.io/badge/GitHub-MAFIA--DE--DAW-green?style=flat-square&logo=github)](https://github.com/MAFIA-DE-DAW/ProyectoFinalFP)

</div>
