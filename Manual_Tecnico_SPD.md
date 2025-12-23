# Manual Técnico-Funcional del Sistema de Gestión de Perfeccionamiento Docente (SPD)

## 1. Introducción y Alcance
Este documento describe en detalle la totalidad de las funcionalidades, interfaces y lógicas de negocio implementadas en el Sistema SPD. El sistema está diseñado para gestionar el ciclo de vida completo de la capacitación docente, desde la creación de la oferta académica hasta la certificación final del participante.

---

## 2. Módulo de Seguridad y Acceso (Autenticación)

El punto de entrada al sistema gestiona la identidad de dos tipos de usuarios: Administrativos y Estudiantes.

### A. Vista de Inicio de Sesión (`login`)
*   **Interfaz Visual (Aesthethics):**
    *   **Fondo Animado:** Implementación CSS pura (`animated-bg`) con gradientes dinámicos azul/dorado institucional y partículas flotantes (`floating-shapes`).
    *   **Tarjeta Glassmorphism:** Contenedor central con efecto de vidrio esmerilado (`backdrop-filter: blur`).
    *   **Línea Dorada:** Animación superior (`shimmerGold`) para denotar identidad corporativa premium.
*   **Funcionalidad:**
    *   **Formulario de Acceso:** Campos para Usuario (`adm_login`) y Contraseña.
    *   **Validación:** Feedback visual en rojo si hay errores de credenciales (`@error`).
    *   **Acceso Diferenciado:** Botón secundario borderless para redirigir al "Portal del Participante".
    *   **Lógica de Controlador (`AuthController`):**
        *   Verifica credenciales contra la tabla `usuarios` (admins) o `participante` (alumnos).
        *   Redirige automáticamente al dashboard correspondiente según el rol (`admin.dashboard` o `participant.dashboard`).

---

## 3. Portal del Participante (Estudiante)

Entorno de autogestión académica enfocado en la experiencia de usuario (UX) simplificada.

### A. Dashboard Principal (`participant.dashboard`)
*   **Objetivo:** Ofrecer una visión panorámica del estado académico.
*   **Componente "Hero":**
    *   Bienvenida personalizada con nombre del usuario.
    *   Fondo con patrón SVG geométrico y gradientes.
*   **Carrusel de Estadísticas:**
    *   Datos reales calculados en tiempo real: Cursos Aprobados, Horas Totales acumuladas.

### B. Módulo "Mis Cursos" (`participant.my_courses`)
Diseñado para la gestión de la carga académica actual e histórica.

#### 1. Navegación por Pestañas
*   **[En Curso]:**
    *   *Funcionalidad:* Filtra y muestra cursos activos (`cur_estado=1`) y no aprobados.
    *   *Lógica:* Excluye cursos finalizados para limpiar la vista prioritaria.
*   **[Finalizados]:**
    *   *Funcionalidad:* Muestra el historial completo.
    *   *Filtros Internos:* Botones para segmentar por "Aprobados" (Verde), "Reprobados" (Rojo) o "Todos".

#### 2. Tarjeta de Curso Inteligente
Cada tarjeta es un componente interactivo complejo con múltiples estados:
*   **Estados Visuales:**
    *   **Activo:** Etiqueta verde pulsante (`animate-pulse`).
    *   **Terminado:** Etiqueta gris estática.
*   **Interacción "Ver Detalle" (Progressive Disclosure):**
    *   *Botón:* Alterna entre "Ver Detalle" y "Ocultar Detalle".
    *   *Contenido Exclusivo:* Al expandir, se carga y muestra:
        *   Descripción técnica completa.
        *   Objetivos de Aprendizaje (HTML renderizado).
        *   Contenidos/Temario.
        *   Nómina de Docentes (Relatores) asignados.
*   **Acciones de Certificación:**
    *   **Botón Descargar Certificado:**
        *   *Condición:* Solo visible si `is_approved` es verdadero.
        *   *Acción:* Genera PDF oficial en nueva pestaña.
*   **Sistema de Retroalimentación (Feedback Loop):**
    *   **Botón Valorar Curso:**
        *   *Disponibilidad:* Solo en cursos finalizados sin valoración previa.
        *   *Modal Interactivo:*
            *   Rating (1-5 Estrellas).
            *   Toggle de Recomendación (Sí/No).
            *   Feedback cualitativo (Texto).
        *   *Persistencia:* Una vez enviado, se guarda en tabla `informacion` y bloquea nuevas valoraciones.

---

## 4. Portal de Administración (Gestión Backend)

Entorno robusto para la gestión integral de la oferta .

### A. Dashboard Administrativo
*   **KPIs en Tiempo Real:** Total Cursos, Activos, Terminados, Total Usuarios.
*   **Accesos Directos:** Botones grandes para "Nuevo Curso", "Nuevo Evento".

### B. Módulo de Gestión de Cursos (`admin.courses`)
*   **Buscador Multifacético:**
    *   Permite filtrar simultáneamente por: Texto (Nombre/ID), Categoría (Select), Fecha (Datepicker), Estado (Activo/Inactivo).
*   **Tabla de Datos Avanzada:**
    *   **Barra de Asistencia:** Visualización gráfica del promedio de asistencia del curso.
    *   **Contador Ofertas:** Muestra cuántas versiones activas tiene el curso.
*   **Acciones CRUD:**
    *   **Crear:** Wizzard con validación de campos obligatorios (Nombre, Horas, Modalidad).
    *   **Editar:** Modificación total de metadatos.
    *   **Terminar (Soft Delete):** Inhabilita el curso sin perder datos históricos.
    *   **Eliminar (Hard Delete):** Borrado físico con modal de confirmación de seguridad.
    *   **Ver Participantes:** Enlace directo al módulo de usuarios filtrado por el curso seleccionado.

### C. Módulo de Gestión de Usuarios (`admin.users`)
*   **Filtrado Contextual:**
    *   La vista se adapta dinámicamente. Si se accede desde un curso, muestra "Usuarios del Curso X". Si se accede desde el menú general, muestra "Todos los Usuarios".
*   **Selección Masiva (Bulk Actions):**
    *   Checkboxes para seleccionar múltiples usuarios.
    *   **Barra Flotante:** Aparece al seleccionar items, permitiendo la **Eliminación Masiva** de usuarios.
*   **Exportación:** Capacidad de exportar la lista filtrada a CSV/Excel.

### D. Módulo de Eventos (`admin.events`)
*   **Gestión de Calendario:**
    *   Administración de seminarios, congresos y talleres puntuales.
    *   Filtros visuales tipo "Píldora" (En Curso, Próximos, Finalizados).
*   **Interacciones Hover:**
    *   Las tarjetas de eventos revelan acciones (Editar, Eliminar, Ver) solo al pasar el mouse, manteniendo la interfaz limpia.

---

## 5. Portal del Relator (Docencia)

Interfaz simplificada para la operación académica.

### A. Gestión de Calificaciones (`grades`)
*   **Sticky Header:** La cabecera de la tabla se "pega" al borde superior al hacer scroll, facilitando la lectura en listas largas de alumnos.
*   **Planilla Interactiva:**
    *   **Inputs Directos:** Campos de Nota y Asistencia editables directamente en la fila.
    *   **Semáforo de Estado:**
        *   Calcula automáticamente si el alumno está "Aprobado" (Verde) o "Reprobado" (Rojo) basándose en la nota ingresada (Umbral 4.0).
*   **Guardado Masivo:**
    *   Un solo botón "Guardar Notas" envía toda la matriz de datos al servidor, optimizando la conexión y el tiempo del docente.

---

## 6. Detalles Técnicos Transversales

*   **Front-End Stack:**
    *   **Tailwind CSS:** Para todo el estilizado, diseño responsive y paleta de colores institucional.
    *   **Alpine.js:** Para la reactividad ligera (Modales, Dropdowns, Toggles, Selección masiva).
    *   **Animaciones:** Uso de `transition`, `transform` y `keyframes` para una experiencia fluida.
*   **Back-End Stack:**
    *   **Laravel 10:** Framework PHP robusto.
    *   **Eloquent ORM:** Manejo de relaciones complejas (Curso -> Programas -> Relatores).
    *   **Middleware:** Seguridad por capas (`auth:admin`, `auth:participant`) para aislamiento de roles.
*   **Base de Datos:**
    *   Modelo relacional normalizado con tablas pivote para inscripciones y asignaciones docentes.
