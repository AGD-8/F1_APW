# 🏎️ UltraSpeed | Motorsport World 🏆

¡Bienvenido a **UltraSpeed**! Una plataforma web interactiva y premium diseñada exclusivamente para los entusiastas del motor, abarcando las dos disciplinas reinas del automovilismo y el motociclismo mundial: **Fórmula 1** y **MotoGP**.

Esta aplicación ofrece un portal dinámico donde los miembros de la comunidad pueden explorar circuitos históricos, analizar las especificaciones técnicas de vehículos legendarios, seguir la trayectoria de los pilotos más destacados, valorar con estrellas sus elementos favoritos, comentar en chats interactivos y personalizar su perfil de usuario con avatares y credenciales.

---

## 📺 Vista previa e Interfaz Visual

La interfaz de **UltraSpeed** ha sido desarrollada bajo una estética de diseño premium de corte futurista y oscuro (_Dark Cinematic Mode_). Algunas características destacadas de la experiencia de usuario (UX/UI) son:

- **Split-Screen Hero Section**: La pantalla de inicio presenta una impresionante pantalla dividida en diagonal que representa la rivalidad y complementariedad entre **Fórmula 1 🏁** (tonalidades rojas y fibra de carbono) y **MotoGP 🏆** (tonalidades azules y metálicas).
  - _Interactividad Dinámica_: Al pasar el cursor por encima de cada sección, los contenedores ajustan sus proporciones dinámicamente mediante variables CSS (`--split-top`, `--split-bottom`) y transiciones matemáticas de tipo _cubic-bezier_, ofreciendo un efecto premium tridimensional.
- **Micro-animaciones y Scroll Triggered**: El contenido de las secciones se desvela mediante un efecto suave de entrada (_fade-in_ y deslizamiento vertical) gracias al uso del `IntersectionObserver` de JavaScript.
- **Luxury Glassmorphism**: Uso de difuminados de fondo en contenedores (_backdrop-filter: blur_), bordes sutiles semi-transparentes y gradientes de color de alta gama (neón rojo, naranja y cian) que dan un aspecto de alta gama.

---

## 🚀 Características y Funcionalidades del Sistema

### 1. Sistema de Base de Datos Dinámica y Autoconfigurable

- **Script de Configuración Automática (`setup.php`)**: La instalación se realiza en un solo clic. El script establece conexión con el servidor MySQL local, crea la base de datos `motorsport_full_db` si no existe, carga las tablas relacionales desde `full_schema.sql` y redirige al usuario al panel principal.
- **Variables de Entorno (`.env`)**: Admite el uso de variables locales protegidas para resguardar las credenciales sensibles del servidor de base de datos.

### 2. Gestión de Entidades (CRUD Completo)

Los usuarios autenticados pueden registrar, editar, actualizar o eliminar registros en tres áreas diferenciadas del motor:

- **Circuitos Legendarios**: Almacena información detallada que incluye el país de ubicación, longitud total en kilómetros, año de inauguración, capacidad de espectadores, récord de vuelta rápida (tiempo y piloto), curvas principales destacadas y la descripción de la forma o trazado del asfalto.
- **Máquinas de Precisión (Vehículos)**: Permite registrar prototipos seleccionando su categoría (`Formula 1` o `MotoGP`), motorizaciones, velocidad máxima, aceleración (0-100 km/h), compuestos de neumáticos asignados y peso neto en kilogramos.
- **Héroes de la Pista (Pilotos)**: Permite recopilar la nacionalidad del deportista, dorsal de carrera oficial, año de nacimiento, historial de trayectoria por escuderías pasadas, equipo actual, cualidades técnicas principales y número de títulos de campeonato mundial acumulados.

### 3. Sistema Interactiva de Calificaciones (Ratings)

- **Cálculo Promediado**: Cada circuito, piloto o vehículo calcula en tiempo real su promedio de estrellas basado en los votos registrados.
- **Valoración Visual**: Formulado con estrellas interactivas en la sección de detalles (`detalle.php`), donde el usuario puede previsualizar su puntuación antes de enviarla.
- **Sección de Opiniones**: Un panel de opiniones recientes muestra el nombre de usuario, la valoración en estrellas y una reseña textual con fecha y hora de publicación.

### 4. Chat de Comunidad y Hilos de Discusión (`chat.php`)

- Cada elemento tiene su propia sala de debate privada donde los usuarios registrados pueden conversar en tiempo real, plantear debates o comentar las últimas carreras.
- Los mensajes muestran el avatar personalizado del usuario, el nombre de usuario resaltado y la fecha/hora exacta de publicación.

### 5. Área Privada de Perfil (`perfil.php`)

- **Subida y Almacenamiento de Avatares**: Sistema robusto de subida de imágenes en formato binario que renombra y guarda automáticamente el archivo en la carpeta `assets/` bajo el esquema `user_[id].[extensión]` y actualiza la ruta del perfil en la base de datos.
- **Cambio de Credenciales**: Permite cambiar el nombre de usuario en tiempo real con actualización automática de la variable de sesión activa.
- **Actualización de Contraseña Segura**: Compara la clave actual usando hashing asimétrico y actualiza a la nueva contraseña aplicando la función `password_hash()` con el algoritmo robusto por defecto de PHP.

### 6. Demo Interactiva Offline (`demo_interactiva.html`)

- Un panel alternativo desarrollado íntegramente en HTML, CSS y JS del lado del cliente.
- Permite a desarrolladores y diseñadores visualizar toda la interactividad premium (animaciones, apertura de detalles, simulación de chat y puntuaciones) de forma inmediata sin necesidad de levantar un servidor local de PHP o configurar bases de datos.

---

## 🛠️ Tecnologías y Arquitectura Utilizadas

### Backend (Lógica de Servidor)

- **PHP 8+**: Lenguaje principal de servidor estructurado para gestionar el flujo, sesiones e inyección de layouts HTML.
- **PDO (PHP Data Objects)**: Conector de base de datos que garantiza mayor seguridad mediante el uso de **sentencias preparadas** (_prepared statements_) que blindan la aplicación contra ataques de inyección SQL.
- **Manejo de Sesiones**: Control de acceso granular para usuarios autenticados y restricciones de seguridad en páginas críticas (`editar.php`, `eliminar.php`, `perfil.php`).

### Frontend (Presentación e Interactividad)

- **HTML5 Semántico**: Estructuraciones limpias mediante `<nav>`, `<header>`, `<main>`, `<section>` y `<footer>` que optimizan el rendimiento SEO.
- **Vanilla CSS**: Diseño premium 100% personalizado sin dependencias de frameworks externos pesados (no utiliza TailwindCSS ni Bootstrap). Implementa:
  - Flexbox y CSS Grid para layouts altamente responsivos en móviles, tablets y monitores ultrawide.
  - Variables CSS (Tokens de Diseño) para colores primarios, secundarios, fondos y brillos neón.
  - Animaciones clave con `@keyframes` y el uso de `@property` para interpolar gradientes y recortes en el split-screen.
- **JavaScript (ES6+)**: Interactividad intuitiva para alternar campos en los formularios según la categoría elegida, validación dinámica de imágenes en tiempo real durante la edición y control de estados visuales.

### Base de Datos

- **MySQL / MariaDB**: Motor de almacenamiento relacional con claves foráneas, restricciones de claves primarias únicas y eliminaciones en cascada (_ON DELETE CASCADE_) para garantizar la integridad referencial del sistema si se elimina una cuenta de usuario.

---

## 📂 Estructura de Directorios del Proyecto

La organización del código fuente en la carpeta principal se distribuye de la siguiente forma:

```text
📂 APW_F1/
├── 📂 assets/                  # Iconos, imágenes por defecto y avatares cargados por los usuarios
├── 📂 Circuito Imágenes/       # Directorio local para almacenamiento físico de imágenes de circuitos
├── 📂 Pilotos Imágenes/        # Directorio local para almacenamiento físico de imágenes de pilotos
├── 📂 Vehículos imágenes/      # Directorio local para almacenamiento físico de imágenes de vehículos
│
├── 📄 config.php               # Archivo de conexión PDO centralizada y arranque de sesión
├── 📄 setup.php                # Instalador e inicializador automático de la base de datos
├── 📄 full_schema.sql          # Esquema relacional completo de la Base de Datos (Versión Robusta)
├── 📄 F1.sql                   # Esquema histórico alternativo de base de datos básica
├── 📄 Registros.sql            # Esquema histórico del registro original
│
├── 📄 functions.php            # Biblioteca de funciones (cálculo de ratings y estrellas visuales)
├── 📄 index.php                # Dashboard principal con el Split-Screen Hero y el listado de cards
├── 📄 detalle.php              # Ficha técnica ampliada del elemento con formulario de valoración
├── 📄 editar.php               # Formulario inteligente de edición con preview dinámica de imagen
├── 📄 eliminar.php             # Controlador para el borrado seguro de registros
├── 📄 chat.php                 # Canal interactivo de discusión comunitaria por elemento
├── 📄 perfil.php               # Panel de control del usuario para modificar su avatar y contraseña
├── 📄 registro.php             # Formulario de registro para nuevos integrantes de la plataforma
├── 📄 login.php                # Formulario de inicio de sesión seguro
├── 📄 logout.php               # Finalización limpia de la sesión activa
│
├── 📄 style.css                # Hoja de estilos centralizada (Temática de velocidad oscura / Premium)
├── 📄 demo_interactiva.html    # Demo de presentación offline en cliente puro
├── 📄 .env                     # Archivo de variables de entorno para configuración local
└── 📄 .gitignore               # Exclusiones de Git (evita subir el archivo .env o avatares de prueba)
```

---

## 💾 Detalle de la Base de Datos (`full_schema.sql`)

El motor relacional utiliza seis tablas interconectadas para dotar a la plataforma de su gran dinamismo:

```mermaid
erDiagram
    USUARIOS ||--o{ CIRCUITOS : "crea"
    USUARIOS ||--o{ PILOTOS : "crea"
    USUARIOS ||--o{ VEHICULOS : "crea"
    USUARIOS ||--o{ VALORACIONES : "valora"
    USUARIOS ||--o{ MENSAJES : "comenta"

    USUARIOS {
        int id PK
        varchar usuario UNIQUE
        varchar password
        varchar foto_perfil
        timestamp fecha_creacion
    }

    CIRCUITOS {
        int id PK
        varchar nombre
        varchar pais
        decimal longitud_km
        varchar imagen_url
        text curvas_principales
        text forma_circuito
        varchar vuelta_rapida
        int anio_inauguracion
        int capacidad
        int creado_por FK
    }

    PILOTOS {
        int id PK
        varchar nombre
        varchar nacionalidad
        int dorsal
        varchar imagen_url
        int anio_nacimiento
        text cualidades
        int titulos
        varchar equipo_actual
        text historia_equipos
        int creado_por FK
    }

    VEHICULOS {
        int id PK
        varchar nombre
        varchar equipo
        enum categoria "Formula 1, MotoGP"
        varchar imagen_url
        text tipos_neumaticos
        varchar motor
        varchar velocidad_max
        varchar aceleracion_0_100
        int peso_kg
        int creado_por FK
    }

    VALORACIONES {
        int id PK
        int id_usuario FK
        enum tipo_elemento "circuito, vehiculo, piloto"
        int id_elemento
        int estrellas
        text comentario
        timestamp fecha
    }

    MENSAJES {
        int id PK
        int id_usuario FK
        enum tipo_elemento "circuito, vehiculo, piloto"
        int id_elemento
        text mensaje
        timestamp fecha
    }
```

---

## ⚙️ Guía de Instalación y Configuración Paso a Paso

Sigue las siguientes instrucciones detalladas para poner en marcha la aplicación en tu entorno de desarrollo local:

### Paso 1: Entorno de Servidor Local

Asegúrate de contar con un servidor local que integre **PHP 8** y **MySQL**. La opción recomendada es **XAMPP**:

1. Descarga e instala [XAMPP](https://www.apachefriends.org/).
2. Abre el **XAMPP Control Panel** e inicia los módulos de **Apache** y **MySQL**.

### Paso 2: Ubicación del Código

1. Navega al directorio de publicación de tu servidor local. En Windows, por defecto, se ubica en:
   `C:\xampp\htdocs\`
2. Copia la carpeta completa del proyecto `APW_F1` dentro de la ruta mencionada:
   `C:\xampp\htdocs\APW_F1\`

### Paso 3: Configuración de Variables del Servidor

Abre el archivo `config.php` y verifica las credenciales de conexión con MySQL. Por defecto, en una instalación limpia de XAMPP las credenciales son:

```php
$host = 'localhost';
$db   = 'tu_nombre_de_bd';
$user = 'tu_usuario';
$pass = ''; // Por defecto viene vacío
```

_Si tienes contraseñas configuradas en tu servidor MySQL, actualiza la variable `$pass` con la tuya._

### Paso 4: Inicialización Automática de la Base de Datos

La aplicación creará automáticamente la base de datos y cargará todo el esquema relacional inicial sin necesidad de que utilices comandos manuales en phpMyAdmin:

1. Abre tu navegador web favorito (Chrome, Edge, Firefox, etc.).
2. Accede a la siguiente dirección de inicialización segura:
   [http://localhost/APW_F1/setup.php](http://localhost/APW_F1/setup.php)
3. Al instante verás una tarjeta visual de confirmación con el mensaje **🚀 ¡Todo listo!** que te indicará que las tablas de usuarios, sistemas de estrellas, chats y relaciones han sido generados exitosamente.
4. Tras transcurrir **3 segundos**, el instalador te redirigirá automáticamente a la landing page principal (`index.php`).

### Paso 5: ¡A Disfrutar!

Ya puedes empezar a explorar el portal:

1. Crea tu primera cuenta de usuario en **Registrarse** (`registro.php`).
2. Sube una foto de perfil personalizada en tu panel de control **Mi Perfil** (`perfil.php`).
3. Empieza a rellenar la plataforma pulsando en **+ Añadir Contenido**, completando la información de tus pilotos favoritos, circuitos más queridos o coches de F1/Motos de GP favoritos, ¡y comparte tus valoraciones y chats con toda la comunidad!

---

Desarrollado con pasión por el motor y la ingeniería de vanguardia. 🏁🚀
