# Nuvia

**Sistema web de búsqueda, reserva y gestión de pasajes de avión.**

Nuvia es un sitio web desarrollado como proyecto académico para la materia Entornos Gráficos de la Universidad Tecnológica Nacional (UTN). Su objetivo es facilitar la consulta y reserva de vuelos, así como la administración de aerolíneas, vuelos y promociones.

---

## Funcionalidades

### Usuarios no registrados

- Acceso a las funcionalidades públicas del sitio.
- Consulta y búsqueda de vuelos.
- Visualización de información de los vuelos.
- Registro de una cuenta.

### Usuarios

- Registro e inicio de sesión.
- Recuperación de contraseña.
- Consulta y búsqueda de vuelos.
- Visualización de información de los vuelos.
- Reserva y confirmación de reservas.
- Simulación de confirmación de pago.
- Consulta del historial de reservas.
- Gestión del perfil personal.

### CEOs de aerolíneas

- Gestión de los vuelos de su aerolínea.
- Administración de precios y disponibilidad.
- Gestión de promociones.
- Consulta de reportes relacionados con su aerolínea.

### Administración

- Gestión de aerolíneas.
- Evaluación de solicitudes de registro de CEOs.
- Aprobación o rechazo de promociones.
- Gestión de novedades del sitio.
- Administración de cuentas de CEOs.
- Consulta de reportes generales.

---

## Tecnologías utilizadas

- **PHP:** desarrollo de la lógica del sitio.
- **MySQL:** gestión de la base de datos.
- **phpMyAdmin:** administración de la base de datos.
- **HTML y CSS:** estructura y presentación de las páginas.
- **JavaScript:** funcionalidades e interacciones del sitio.
- **Composer:** gestión de dependencias de PHP.

---

## Integrantes

Proyecto desarrollado en equipo por cuatro integrantes:

- Azurmendi Felicitas

- Benetti Sofia

- Maciá Julieta

- Magliaro Violeta

--- 

## Estructura del proyecto

```text
PaginaWeb/
├── admin/                 # Funcionalidades del administrador
├── ceo/                   # Panel de gestión de aerolíneas
├── css/                   # Hojas de estilo
├── database/              # Archivos relacionados con la base de datos
├── destinosBurbujas/      # Recursos de destinos
├── imagenes/              # Imágenes del sitio
├── includes/              # Componentes compartidos
├── js/                    # Scripts JavaScript
├── php/                   # Archivos PHP auxiliares
├── usuario/               # Funcionalidades de usuarios registrados
├── usuarioNOregistrado/   # Funcionalidades de acceso público
├── vendor/                # Dependencias de Composer
├── home.php               # Página principal
├── inicioSesion.php       # Inicio de sesión
├── registro.php           # Registro de usuarios
├── resultadosVuelos.php   # Resultados de búsqueda
├── vueloElegido.php       # Vuelo seleccionado
├── composer.json          # Dependencias del proyecto
└── README.md              # Documentación del proyecto
```

---

## Acceso al sitio

Nuvia se encuentra disponible en su versión web publicada.

**Sitio web:** [Agregar acá el enlace al sitio]

---

## Instalación y ejecución local

Para ejecutar el proyecto localmente, se requiere un entorno compatible con PHP y un servidor MySQL.

1. Clonar o descargar el repositorio.
2. Configurar un entorno local con PHP y MySQL.
3. Importar la base de datos del proyecto desde phpMyAdmin, utilizando el archivo SQL correspondiente.
4. Configurar las credenciales de conexión a la base de datos según el entorno local.
5. Instalar las dependencias de Composer, si corresponde.
6. Iniciar el servidor web y acceder al sitio desde el navegador.

**Nota:** los nombres del archivo SQL, la base de datos y los parámetros de conexión deben verificarse en el repositorio antes de seguir estos pasos.

---

## Contexto académico

Proyecto desarrollado en equipo para la materia **Entornos Gráficos** de la Universidad Tecnológica Nacional (UTN).

El proyecto integra contenidos de desarrollo web, gestión de bases de datos, diseño de interfaces y control de versiones.
