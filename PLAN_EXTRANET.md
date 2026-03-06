# PLAN DE IMPLEMENTACIÓN: MÓDULO EXTRANET CORPORATIVA
## MiPortal 3.0 - Intranet Social Empresarial

**Desarrollador Senior:** Claude Code
**Fecha de Inicio:** 5 de marzo de 2026
**Duración Estimada:** 4-6 semanas
**Complejidad:** Alta
**Prioridad:** Alta

---

## 📋 TABLA DE CONTENIDOS

1. [Visión General](#1-visión-general)
2. [Análisis de Requisitos](#2-análisis-de-requisitos)
3. [Arquitectura del Módulo](#3-arquitectura-del-módulo)
4. [Diseño de Base de Datos](#4-diseño-de-base-de-datos)
5. [Plan de Implementación](#5-plan-de-implementación)
6. [Funcionalidades Detalladas](#6-funcionalidades-detalladas)
7. [Automatizaciones](#7-automatizaciones)
8. [Sistema de Permisos](#8-sistema-de-permisos)
9. [Timeline y Sprints](#9-timeline-y-sprints)
10. [Pruebas y Validación](#10-pruebas-y-validación)
11. [Documentación](#11-documentación)

---

## 1. VISIÓN GENERAL

### 1.1 Objetivo

Crear un **módulo de Extranet/Intranet corporativa** que sirva como centro de comunicación y colaboración interna, integrándose perfectamente con los módulos existentes de MiPortal 2.0.

### 1.2 Filosofía de Diseño

- **No invasivo:** No modificar código existente, solo extender
- **Modular:** Componentes independientes y reutilizables
- **Automatizado:** Máxima automatización usando datos existentes
- **Responsive:** Diseño mobile-first
- **Social:** Fomentar la interacción entre empleados
- **Informativo:** Dashboard centralizado de información corporativa

### 1.3 Alcance

**Funcionalidades Principales:**

1. ✅ **Comunicados Internos** (Anuncios oficiales de RH/Dirección)
2. ✅ **Gestión de Proyectos** (Proyectos departamentales)
3. ✅ **Cumpleaños** (Automatizado desde empleados)
4. ✅ **Nuevos Empleados** (Automatizado, últimos 30 días)
5. ✅ **Eventos Corporativos** (Calendario de eventos)
6. ✅ **Galería de Fotos** (Álbumes de eventos)
7. ✅ **Reconocimientos** (Empleado del mes, logros)
8. ✅ **Encuestas** (Pulso organizacional)
9. ✅ **Documentos Compartidos** (Políticas, manuales)
10. ✅ **Muro Social** (Feed de actividad empresarial)
11. ✅ **Directorio de Empleados** (Búsqueda y contacto)
12. ✅ **Aniversarios Laborales** (Automatizado)

**Funcionalidades Secundarias:**

13. 📊 **Estadísticas en Tiempo Real**
14. 🔔 **Sistema de Notificaciones**
15. 💬 **Comentarios y Reacciones**
16. 📱 **Versión móvil optimizada**
17. 🎨 **Temas personalizables**

---

## 2. ANÁLISIS DE REQUISITOS

### 2.1 Requisitos Funcionales

| ID | Requisito | Prioridad | Fuente de Datos |
|----|-----------|-----------|-----------------|
| RF-01 | Publicar comunicados internos | Alta | Manual + automático |
| RF-02 | Gestionar proyectos departamentales | Alta | Manual |
| RF-03 | Mostrar cumpleaños del día/semana | Alta | `empleados.EMP_FECHA_NACIMIENTO` |
| RF-04 | Listar nuevos empleados | Alta | `empleados.created_at` |
| RF-05 | Calendario de eventos corporativos | Alta | Manual + integración `eventos` |
| RF-06 | Galería de fotos de eventos | Media | Manual |
| RF-07 | Sistema de reconocimientos | Media | Manual |
| RF-08 | Encuestas internas | Media | Manual |
| RF-09 | Repositorio de documentos | Alta | Manual |
| RF-10 | Muro social con feed de actividad | Alta | Automático |
| RF-11 | Directorio de empleados | Alta | `empleados` |
| RF-12 | Aniversarios laborales | Alta | `emp_contratos.EMC_FECHA_INICIO` |
| RF-13 | Notificaciones push | Media | Automático |
| RF-14 | Comentarios y reacciones | Media | Manual |
| RF-15 | Búsqueda global | Media | Múltiples tablas |

### 2.2 Requisitos No Funcionales

| ID | Requisito | Criterio de Aceptación |
|----|-----------|------------------------|
| RNF-01 | Performance | Carga de dashboard < 2 segundos |
| RNF-02 | Seguridad | Permisos granulares por rol |
| RNF-03 | Usabilidad | Interfaz intuitiva, max 3 clics |
| RNF-04 | Escalabilidad | Soportar 1000+ empleados |
| RNF-05 | Mantenibilidad | Código PSR-12, documentado |
| RNF-06 | Disponibilidad | 99.5% uptime |
| RNF-07 | Compatibilidad | Chrome, Firefox, Safari, Edge |
| RNF-08 | Responsive | Mobile, tablet, desktop |

### 2.3 Actores del Sistema

| Actor | Rol | Permisos |
|-------|-----|----------|
| **Administrador** | Super usuario | Todos los permisos |
| **RH Manager** | Recursos Humanos | Crear/editar todo excepto proyectos |
| **Project Manager** | Gestión de proyectos | Gestionar proyectos y tareas |
| **Content Manager** | Gestión de contenido | Crear/editar comunicados, eventos, galería |
| **Empleado** | Usuario estándar | Solo lectura, comentar, reaccionar |
| **Supervisor** | Jefe de área | Lectura + crear reconocimientos de su equipo |

---

## 3. ARQUITECTURA DEL MÓDULO

### 3.1 Estructura de Directorios

```
app/
├── Http/
│   └── Controllers/
│       └── Extranet/
│           ├── DashboardController.php          # Dashboard principal
│           ├── ComunicadoController.php         # Comunicados internos
│           ├── ProyectoController.php           # Gestión de proyectos
│           ├── EventoExtranetController.php     # Eventos corporativos
│           ├── GaleriaController.php            # Galerías de fotos
│           ├── ReconocimientoController.php     # Reconocimientos
│           ├── EncuestaController.php           # Encuestas
│           ├── DocumentoController.php          # Documentos compartidos
│           ├── MuroController.php               # Muro social
│           ├── DirectorioController.php         # Directorio empleados
│           ├── ComentarioController.php         # Sistema de comentarios
│           ├── ReaccionController.php           # Sistema de reacciones
│           └── NotificacionController.php       # Notificaciones
│
├── Models/
│   └── Extranet/
│       ├── Comunicado.php
│       ├── Proyecto.php
│       ├── TareaProyecto.php
│       ├── EventoExtranet.php
│       ├── Galeria.php
│       ├── FotoGaleria.php
│       ├── Reconocimiento.php
│       ├── Encuesta.php
│       ├── PreguntaEncuesta.php
│       ├── RespuestaEncuesta.php
│       ├── Documento.php
│       ├── PublicacionMuro.php
│       ├── Comentario.php
│       ├── Reaccion.php
│       └── Notificacion.php
│
├── Services/
│   └── Extranet/
│       ├── ExtranetService.php                  # Servicio principal
│       ├── CumpleanosService.php                # Lógica de cumpleaños
│       ├── AniversarioService.php               # Lógica de aniversarios
│       ├── NotificacionService.php              # Gestión notificaciones
│       └── MuroService.php                      # Feed del muro
│
├── Events/
│   └── Extranet/
│       ├── ComunicadoPublicado.php
│       ├── ProyectoCreado.php
│       ├── EventoProximo.php
│       ├── ReconocimientoOtorgado.php
│       └── NuevoComentario.php
│
└── Listeners/
    └── Extranet/
        ├── NotificarComunicado.php
        ├── NotificarProyecto.php
        ├── NotificarEvento.php
        └── NotificarReconocimiento.php

database/
├── migrations/
│   └── 2026_03_05_xxxxxx_create_extranet_tables.php
│
└── seeders/
    └── ExtranetSeeder.php

resources/
├── views/
│   └── extranet/
│       ├── dashboard.blade.php                  # Dashboard principal
│       ├── comunicados/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── proyectos/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── show.blade.php
│       │   └── kanban.blade.php
│       ├── eventos/
│       ├── galeria/
│       ├── reconocimientos/
│       ├── encuestas/
│       ├── documentos/
│       ├── muro/
│       ├── directorio/
│       ├── widgets/
│       │   ├── cumpleanos.blade.php
│       │   ├── nuevos-empleados.blade.php
│       │   ├── aniversarios.blade.php
│       │   ├── eventos-proximos.blade.php
│       │   └── estadisticas.blade.php
│       └── components/
│           ├── card-comunicado.blade.php
│           ├── card-proyecto.blade.php
│           ├── card-evento.blade.php
│           └── widget-base.blade.php
│
└── js/
    └── extranet/
        ├── dashboard.js
        ├── muro.js
        ├── notificaciones.js
        └── comentarios.js

public/
└── extranet/
    ├── css/
    ├── js/
    └── img/
```

### 3.2 Patrón de Diseño

**MVC + Repository + Service + Events**

```
Request → Route → Controller → Service → Repository → Model → Database
                      ↓
                   Events → Listeners → Actions
```

**Beneficios:**
- Lógica de negocio en Services (reutilizable)
- Acceso a datos en Repositories (testeable)
- Eventos para acciones asíncronas
- Controladores ligeros (solo coordinación)

### 3.3 Integración con Módulos Existentes

**Reutilización de Datos:**

| Módulo Existente | Tabla | Uso en Extranet |
|------------------|-------|-----------------|
| Malla | `empleados` | Cumpleaños, aniversarios, directorio |
| Malla | `emp_contratos` | Aniversarios laborales, datos contrato |
| Malla | `cargos` | Jerarquía organizacional |
| Malla | `departamento` | Organización por departamento |
| Malla | `campana` | Proyectos por campaña |
| Main | `users` | Autores de publicaciones |
| Main | `roles` | Permisos del sistema |

**Principio:** **NO modificar tablas existentes**, solo leer datos

---

## 4. DISEÑO DE BASE DE DATOS

### 4.1 Diagrama ER

```
┌─────────────────┐         ┌──────────────────┐
│  COMUNICADOS    │         │   PROYECTOS      │
├─────────────────┤         ├──────────────────┤
│ id              │         │ id               │
│ titulo          │         │ nombre           │
│ contenido       │         │ descripcion      │
│ tipo            │         │ fecha_inicio     │
│ prioridad       │         │ fecha_fin        │
│ fecha_inicio    │         │ estado           │
│ fecha_fin       │         │ progreso         │
│ archivo_url     │         │ responsable_id   │─┐
│ autor_id        │─┐       │ campana_id       │ │
│ visible_para    │ │       │ created_at       │ │
│ estado          │ │       └──────────────────┘ │
│ created_at      │ │                            │
└─────────────────┘ │       ┌──────────────────┐ │
                    │       │ TAREAS_PROYECTO  │ │
┌─────────────────┐ │       ├──────────────────┤ │
│  EVENTOS        │ │       │ id               │ │
├─────────────────┤ │       │ proyecto_id      │─┘
│ id              │ │       │ titulo           │
│ titulo          │ │       │ descripcion      │
│ descripcion     │ │       │ asignado_a       │─┐
│ tipo            │ │       │ estado           │ │
│ fecha_inicio    │ │       │ prioridad        │ │
│ fecha_fin       │ │       │ fecha_vencimiento│ │
│ hora_inicio     │ │       │ created_at       │ │
│ lugar           │ │       └──────────────────┘ │
│ organizador_id  │─┤                            │
│ imagen_url      │ │       ┌──────────────────┐ │
│ cupo_max        │ │       │   EMPLEADOS      │ │
│ estado          │ │       ├──────────────────┤ │
│ created_at      │ │       │ EMP_ID           │◄┴─┐
└─────────────────┘ │       │ EMP_NOMBRES      │   │
                    │       │ EMP_APELLIDOS    │   │
┌─────────────────┐ │       │ EMP_CEDULA       │   │
│  GALERIAS       │ │       │ EMP_FECHA_NAC    │   │
├─────────────────┤ │       │ EMP_EMAIL        │   │
│ id              │ │       │ EMP_TELEFONO     │   │
│ titulo          │ │       │ EMP_FOTO_URL     │   │
│ descripcion     │ │       │ CAR_ID           │   │
│ evento_id       │─┤       │ DEP_ID           │   │
│ fecha           │ │       │ created_at       │   │
│ autor_id        │─┤       └──────────────────┘   │
│ created_at      │ │                              │
└─────────────────┘ │       ┌──────────────────┐   │
        │           │       │      USERS       │   │
        ↓           │       ├──────────────────┤   │
┌─────────────────┐ │       │ id               │◄──┘
│  FOTOS_GALERIA  │ │       │ name             │
├─────────────────┤ │       │ email            │
│ id              │ │       │ password         │
│ galeria_id      │─┘       │ created_at       │
│ archivo_url     │         └──────────────────┘
│ descripcion     │
│ orden           │         ┌──────────────────┐
│ created_at      │         │ RECONOCIMIENTOS  │
└─────────────────┘         ├──────────────────┤
                            │ id               │
┌─────────────────┐         │ empleado_id      │─┐
│  DOCUMENTOS     │         │ tipo             │ │
├─────────────────┤         │ titulo           │ │
│ id              │         │ descripcion      │ │
│ titulo          │         │ otorgado_por     │─┤
│ descripcion     │         │ fecha            │ │
│ categoria       │         │ imagen_url       │ │
│ archivo_url     │         │ created_at       │ │
│ version         │         └──────────────────┘ │
│ autor_id        │─┐                            │
│ visible_para    │ │       ┌──────────────────┐ │
│ created_at      │ │       │   ENCUESTAS      │ │
└─────────────────┘ │       ├──────────────────┤ │
                    │       │ id               │ │
┌─────────────────┐ │       │ titulo           │ │
│ PUBLICACIONES   │ │       │ descripcion      │ │
│    _MURO        │ │       │ autor_id         │─┤
├─────────────────┤ │       │ fecha_inicio     │ │
│ id              │ │       │ fecha_fin        │ │
│ tipo            │ │       │ anonima          │ │
│ referencia_id   │ │       │ estado           │ │
│ titulo          │ │       │ created_at       │ │
│ contenido       │ │       └──────────────────┘ │
│ autor_id        │─┤                ↓           │
│ destacado       │ │       ┌──────────────────┐ │
│ created_at      │ │       │ PREGUNTAS       │ │
└─────────────────┘ │       │   _ENCUESTA     │ │
        │           │       ├──────────────────┤ │
        ↓           │       │ id               │ │
┌─────────────────┐ │       │ encuesta_id     │─┘
│  COMENTARIOS    │ │       │ pregunta        │
├─────────────────┤ │       │ tipo_respuesta  │
│ id              │ │       │ opciones        │
│ publicacion_id  │─┘       │ obligatoria     │
│ comentario_id   │◄┐       │ orden           │
│ autor_id        │─┼┐      └──────────────────┘
│ contenido       │ ││              ↓
│ created_at      │ ││      ┌──────────────────┐
└─────────────────┘ ││      │  RESPUESTAS     │
        └───────────┘│      │   _ENCUESTA     │
                     │      ├──────────────────┤
┌─────────────────┐  │      │ id               │
│  REACCIONES     │  │      │ encuesta_id     │─┐
├─────────────────┤  │      │ pregunta_id     │─┤
│ id              │  │      │ empleado_id     │─┤
│ publicacion_id  │──┘      │ respuesta       │ │
│ comentario_id   │◄────────│ created_at      │ │
│ autor_id        │─────────└─────────────────┘ │
│ tipo            │                             │
│ created_at      │         ┌──────────────────┐│
└─────────────────┘         │ NOTIFICACIONES   ││
                            ├──────────────────┤│
                            │ id               ││
                            │ empleado_id      │┘
                            │ tipo             │
                            │ titulo           │
                            │ mensaje          │
                            │ referencia_tipo  │
                            │ referencia_id    │
                            │ leida            │
                            │ leida_at         │
                            │ created_at       │
                            └──────────────────┘
```

### 4.2 Tablas Detalladas

#### 4.2.1 Tabla: `comunicados`

```sql
CREATE TABLE `comunicados` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `contenido` TEXT NOT NULL,
  `tipo` ENUM('general', 'urgente', 'rh', 'ti', 'operaciones', 'admin') DEFAULT 'general',
  `prioridad` ENUM('baja', 'media', 'alta', 'critica') DEFAULT 'media',
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NULL,
  `archivo_url` VARCHAR(500) NULL,
  `imagen_url` VARCHAR(500) NULL,
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `visible_para` JSON NULL COMMENT 'Array de roles o empleados',
  `fijado` BOOLEAN DEFAULT FALSE,
  `estado` ENUM('borrador', 'publicado', 'archivado') DEFAULT 'borrador',
  `vistas` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,

  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_estado` (`estado`),
  INDEX `idx_tipo` (`tipo`),
  INDEX `idx_fecha_inicio` (`fecha_inicio`),
  INDEX `idx_fijado` (`fijado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.2 Tabla: `proyectos`

```sql
CREATE TABLE `proyectos` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `objetivo` TEXT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NULL,
  `fecha_fin_real` DATE NULL,
  `estado` ENUM('planificacion', 'en_progreso', 'pausado', 'completado', 'cancelado') DEFAULT 'planificacion',
  `prioridad` ENUM('baja', 'media', 'alta', 'critica') DEFAULT 'media',
  `progreso` TINYINT UNSIGNED DEFAULT 0 COMMENT '0-100',
  `presupuesto` DECIMAL(15,2) NULL,
  `responsable_id` BIGINT UNSIGNED NOT NULL,
  `departamento_id` BIGINT UNSIGNED NULL,
  `campana_id` BIGINT UNSIGNED NULL,
  `etiquetas` JSON NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,

  FOREIGN KEY (`responsable_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE CASCADE,
  FOREIGN KEY (`departamento_id`) REFERENCES `departamentos`(`DEP_ID`) ON DELETE SET NULL,
  FOREIGN KEY (`campana_id`) REFERENCES `campanas`(`CAM_ID`) ON DELETE SET NULL,
  INDEX `idx_estado` (`estado`),
  INDEX `idx_responsable` (`responsable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.3 Tabla: `tareas_proyecto`

```sql
CREATE TABLE `tareas_proyecto` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `proyecto_id` BIGINT UNSIGNED NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `asignado_a` BIGINT UNSIGNED NULL,
  `estado` ENUM('pendiente', 'en_progreso', 'revision', 'completada', 'cancelada') DEFAULT 'pendiente',
  `prioridad` ENUM('baja', 'media', 'alta', 'critica') DEFAULT 'media',
  `fecha_vencimiento` DATE NULL,
  `fecha_completada` DATETIME NULL,
  `orden` INT UNSIGNED DEFAULT 0,
  `dependencias` JSON NULL COMMENT 'IDs de tareas dependientes',
  `tiempo_estimado` INT UNSIGNED NULL COMMENT 'Horas',
  `tiempo_real` INT UNSIGNED NULL COMMENT 'Horas',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`asignado_a`) REFERENCES `empleados`(`EMP_ID`) ON DELETE SET NULL,
  INDEX `idx_proyecto` (`proyecto_id`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_asignado` (`asignado_a`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.4 Tabla: `eventos_extranet`

```sql
CREATE TABLE `eventos_extranet` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `tipo` ENUM('reunion', 'capacitacion', 'celebracion', 'conferencia', 'team_building', 'otro') DEFAULT 'reunion',
  `modalidad` ENUM('presencial', 'virtual', 'hibrido') DEFAULT 'presencial',
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NULL,
  `hora_inicio` TIME NULL,
  `hora_fin` TIME NULL,
  `lugar` VARCHAR(255) NULL,
  `link_virtual` VARCHAR(500) NULL,
  `organizador_id` BIGINT UNSIGNED NOT NULL,
  `departamento_id` BIGINT UNSIGNED NULL,
  `imagen_url` VARCHAR(500) NULL,
  `cupo_maximo` INT UNSIGNED NULL,
  `requiere_confirmacion` BOOLEAN DEFAULT FALSE,
  `estado` ENUM('borrador', 'publicado', 'en_curso', 'finalizado', 'cancelado') DEFAULT 'borrador',
  `color` VARCHAR(7) DEFAULT '#007bff' COMMENT 'Color hexadecimal',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,

  FOREIGN KEY (`organizador_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE CASCADE,
  FOREIGN KEY (`departamento_id`) REFERENCES `departamentos`(`DEP_ID`) ON DELETE SET NULL,
  INDEX `idx_fecha_inicio` (`fecha_inicio`),
  INDEX `idx_tipo` (`tipo`),
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.5 Tabla: `asistentes_evento`

```sql
CREATE TABLE `asistentes_evento` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `evento_id` BIGINT UNSIGNED NOT NULL,
  `empleado_id` BIGINT UNSIGNED NOT NULL,
  `estado_confirmacion` ENUM('pendiente', 'confirmado', 'rechazado') DEFAULT 'pendiente',
  `asistio` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`evento_id`) REFERENCES `eventos_extranet`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE CASCADE,
  UNIQUE KEY `unique_asistente` (`evento_id`, `empleado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.6 Tabla: `galerias`

```sql
CREATE TABLE `galerias` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `evento_id` BIGINT UNSIGNED NULL,
  `fecha` DATE NOT NULL,
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `portada_url` VARCHAR(500) NULL,
  `visible_para` JSON NULL,
  `total_fotos` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`evento_id`) REFERENCES `eventos_extranet`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.7 Tabla: `fotos_galeria`

```sql
CREATE TABLE `fotos_galeria` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `galeria_id` BIGINT UNSIGNED NOT NULL,
  `archivo_url` VARCHAR(500) NOT NULL,
  `descripcion` VARCHAR(500) NULL,
  `orden` INT UNSIGNED DEFAULT 0,
  `likes` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`galeria_id`) REFERENCES `galerias`(`id`) ON DELETE CASCADE,
  INDEX `idx_galeria` (`galeria_id`),
  INDEX `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.8 Tabla: `reconocimientos`

```sql
CREATE TABLE `reconocimientos` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `empleado_id` BIGINT UNSIGNED NOT NULL,
  `tipo` ENUM('empleado_mes', 'aniversario', 'logro', 'excelencia', 'innovacion', 'trabajo_equipo', 'otro') DEFAULT 'logro',
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `otorgado_por` BIGINT UNSIGNED NOT NULL,
  `fecha` DATE NOT NULL,
  `imagen_url` VARCHAR(500) NULL,
  `publico` BOOLEAN DEFAULT TRUE,
  `destacado` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE CASCADE,
  FOREIGN KEY (`otorgado_por`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_empleado` (`empleado_id`),
  INDEX `idx_tipo` (`tipo`),
  INDEX `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.9 Tabla: `encuestas`

```sql
CREATE TABLE `encuestas` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_fin` DATETIME NULL,
  `anonima` BOOLEAN DEFAULT TRUE,
  `visible_para` JSON NULL,
  `estado` ENUM('borrador', 'activa', 'cerrada') DEFAULT 'borrador',
  `total_respuestas` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_estado` (`estado`),
  INDEX `idx_fecha_inicio` (`fecha_inicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.10 Tabla: `preguntas_encuesta`

```sql
CREATE TABLE `preguntas_encuesta` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `encuesta_id` BIGINT UNSIGNED NOT NULL,
  `pregunta` TEXT NOT NULL,
  `tipo_respuesta` ENUM('texto_corto', 'texto_largo', 'opcion_multiple', 'checkbox', 'escala', 'fecha') DEFAULT 'texto_corto',
  `opciones` JSON NULL COMMENT 'Para opciones múltiples o checkbox',
  `escala_min` INT NULL,
  `escala_max` INT NULL,
  `obligatoria` BOOLEAN DEFAULT FALSE,
  `orden` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas`(`id`) ON DELETE CASCADE,
  INDEX `idx_encuesta` (`encuesta_id`),
  INDEX `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.11 Tabla: `respuestas_encuesta`

```sql
CREATE TABLE `respuestas_encuesta` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `encuesta_id` BIGINT UNSIGNED NOT NULL,
  `pregunta_id` BIGINT UNSIGNED NOT NULL,
  `empleado_id` BIGINT UNSIGNED NULL COMMENT 'NULL si es anónima',
  `respuesta` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas_encuesta`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE SET NULL,
  INDEX `idx_encuesta` (`encuesta_id`),
  INDEX `idx_pregunta` (`pregunta_id`),
  INDEX `idx_empleado` (`empleado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.12 Tabla: `documentos_extranet`

```sql
CREATE TABLE `documentos_extranet` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `categoria` ENUM('politicas', 'manuales', 'formatos', 'reglamentos', 'procedimientos', 'capacitacion', 'otro') DEFAULT 'otro',
  `archivo_url` VARCHAR(500) NOT NULL,
  `archivo_nombre` VARCHAR(255) NOT NULL,
  `archivo_tipo` VARCHAR(50) NULL,
  `archivo_tamano` INT UNSIGNED NULL COMMENT 'Bytes',
  `version` VARCHAR(20) DEFAULT '1.0',
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `departamento_id` BIGINT UNSIGNED NULL,
  `visible_para` JSON NULL,
  `descargas` INT UNSIGNED DEFAULT 0,
  `destacado` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,

  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`departamento_id`) REFERENCES `departamentos`(`DEP_ID`) ON DELETE SET NULL,
  INDEX `idx_categoria` (`categoria`),
  INDEX `idx_departamento` (`departamento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.13 Tabla: `publicaciones_muro`

```sql
CREATE TABLE `publicaciones_muro` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `tipo` ENUM('comunicado', 'proyecto', 'evento', 'reconocimiento', 'cumpleanos', 'aniversario', 'nuevo_empleado', 'documento', 'encuesta', 'manual') NOT NULL,
  `referencia_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID del registro origen',
  `titulo` VARCHAR(255) NOT NULL,
  `contenido` TEXT NULL,
  `imagen_url` VARCHAR(500) NULL,
  `autor_id` BIGINT UNSIGNED NULL,
  `destacado` BOOLEAN DEFAULT FALSE,
  `comentarios_habilitados` BOOLEAN DEFAULT TRUE,
  `total_comentarios` INT UNSIGNED DEFAULT 0,
  `total_reacciones` INT UNSIGNED DEFAULT 0,
  `vistas` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_tipo` (`tipo`),
  INDEX `idx_created_at` (`created_at` DESC),
  INDEX `idx_destacado` (`destacado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.14 Tabla: `comentarios_extranet`

```sql
CREATE TABLE `comentarios_extranet` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `publicacion_id` BIGINT UNSIGNED NOT NULL,
  `comentario_padre_id` BIGINT UNSIGNED NULL COMMENT 'Para respuestas',
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `contenido` TEXT NOT NULL,
  `total_reacciones` INT UNSIGNED DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,

  FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones_muro`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`comentario_padre_id`) REFERENCES `comentarios_extranet`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_publicacion` (`publicacion_id`),
  INDEX `idx_padre` (`comentario_padre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.15 Tabla: `reacciones_extranet`

```sql
CREATE TABLE `reacciones_extranet` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `reaccionable_type` VARCHAR(50) NOT NULL COMMENT 'publicaciones_muro, comentarios_extranet',
  `reaccionable_id` BIGINT UNSIGNED NOT NULL,
  `autor_id` BIGINT UNSIGNED NOT NULL,
  `tipo` ENUM('like', 'love', 'haha', 'wow', 'sad', 'angry') DEFAULT 'like',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`autor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_reaccion` (`reaccionable_type`, `reaccionable_id`, `autor_id`),
  INDEX `idx_reaccionable` (`reaccionable_type`, `reaccionable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 4.2.16 Tabla: `notificaciones_extranet`

```sql
CREATE TABLE `notificaciones_extranet` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `empleado_id` BIGINT UNSIGNED NOT NULL,
  `tipo` ENUM('comunicado', 'proyecto', 'evento', 'reconocimiento', 'comentario', 'reaccion', 'mencion', 'cumpleanos', 'aniversario', 'sistema') NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `mensaje` TEXT NULL,
  `referencia_tipo` VARCHAR(50) NULL,
  `referencia_id` BIGINT UNSIGNED NULL,
  `url` VARCHAR(500) NULL,
  `icono` VARCHAR(50) NULL,
  `color` VARCHAR(7) NULL,
  `leida` BOOLEAN DEFAULT FALSE,
  `leida_at` TIMESTAMP NULL,
  `importante` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (`empleado_id`) REFERENCES `empleados`(`EMP_ID`) ON DELETE CASCADE,
  INDEX `idx_empleado` (`empleado_id`),
  INDEX `idx_leida` (`leida`),
  INDEX `idx_created_at` (`created_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. PLAN DE IMPLEMENTACIÓN

### 5.1 Fase 1: Fundamentos (Semana 1) ✅

**Objetivo:** Preparar la infraestructura base del módulo

**Tareas:**

1. **Crear estructura de directorios**
   - Controllers/Extranet/
   - Models/Extranet/
   - Services/Extranet/
   - Events/Extranet/
   - Listeners/Extranet/
   - resources/views/extranet/

2. **Crear migración maestra**
   - Archivo: `2026_03_05_000000_create_extranet_tables.php`
   - Todas las 16 tablas
   - Índices optimizados
   - Foreign keys

3. **Crear modelos Eloquent**
   - 14 modelos principales
   - Relaciones definidas
   - Scopes útiles
   - Accessors/Mutators

4. **Seeder inicial**
   - Datos de ejemplo
   - Roles y permisos
   - Comunicados demo
   - Eventos demo

5. **Actualizar sistema de permisos**
   - Agregar 30+ permisos nuevos
   - Rol "Content Manager"
   - Actualizar RolesSeeder

**Entregables:**
- ✅ Base de datos completa
- ✅ Modelos funcionales
- ✅ Datos de prueba
- ✅ Permisos configurados

**Duración:** 5 días laborables

---

### 5.2 Fase 2: Dashboard y Widgets (Semana 2) 📊

**Objetivo:** Crear el dashboard principal con widgets automatizados

**Tareas:**

1. **DashboardController**
   ```php
   index()           // Dashboard principal
   stats()           // Estadísticas AJAX
   feed()            // Feed del muro
   ```

2. **Widgets automatizados**
   - **Cumpleaños del día/semana**
     - Query: `empleados.EMP_FECHA_NACIMIENTO`
     - Ordenar por proximidad
     - Foto, nombre, edad

   - **Aniversarios laborales**
     - Query: `emp_contratos.EMC_FECHA_INICIO`
     - Calcular años cumplidos
     - Mostrar tiempo en empresa

   - **Nuevos empleados (últimos 30 días)**
     - Query: `empleados.created_at >= NOW() - 30`
     - Ordenar por fecha desc
     - Foto, nombre, cargo

   - **Eventos próximos (siguiente semana)**
     - Query: `eventos_extranet.fecha_inicio BETWEEN NOW() AND NOW() + 7`
     - Ordenar por fecha asc
     - Con botón de confirmar asistencia

   - **Proyectos activos**
     - Query: `proyectos.estado IN ('en_progreso', 'planificacion')`
     - Progress bars
     - Tareas pendientes

   - **Estadísticas generales**
     - Total empleados activos
     - Comunicados del mes
     - Eventos del mes
     - Proyectos completados

3. **Vista dashboard principal**
   - Layout grid responsive
   - Cards con datos en tiempo real
   - Gráficos con Chart.js
   - Carrusel de comunicados fijados

4. **Servicios de datos**
   ```php
   CumpleanosService::obtenerProximos($dias = 7)
   AniversarioService::obtenerProximos($dias = 7)
   ExtranetService::getDashboardData()
   ```

**Entregables:**
- ✅ Dashboard funcional
- ✅ 6 widgets automatizados
- ✅ Datos en tiempo real
- ✅ Diseño responsive

**Duración:** 5 días laborables

---

### 5.3 Fase 3: Comunicados y Eventos (Semana 3) 📢

**Objetivo:** Sistema completo de comunicados internos y eventos

**Tareas:**

1. **ComunicadoController (CRUD completo)**
   ```php
   index()           // Listado con filtros
   create()          // Formulario de creación
   store()           // Guardar comunicado
   show($id)         // Ver detalle
   edit($id)         // Formulario de edición
   update($id)       // Actualizar
   destroy($id)      // Eliminar (soft delete)
   fijar($id)        // Fijar/desfijar
   archivar($id)     // Archivar
   ```

2. **Funcionalidades de comunicados**
   - Editor WYSIWYG (Summernote)
   - Upload de archivos adjuntos
   - Upload de imagen destacada
   - Selector de fecha inicio/fin
   - Visibilidad por roles
   - Prioridad (baja, media, alta, crítica)
   - Tipos (general, urgente, RH, TI, etc.)
   - Fijar en dashboard
   - Contador de vistas
   - Envío de notificaciones automáticas

3. **EventoExtranetController (CRUD completo)**
   ```php
   index()           // Listado y calendario
   create()
   store()
   show($id)
   edit($id)
   update($id)
   destroy($id)
   confirmarAsistencia($id)
   cancelarAsistencia($id)
   listaAsistentes($id)
   marcarAsistencia($id)   // Para organizador
   ```

4. **Funcionalidades de eventos**
   - Calendario interactivo (FullCalendar)
   - Tipos de evento
   - Modalidad (presencial, virtual, híbrido)
   - Link de reunión virtual
   - Cupo máximo
   - Sistema de confirmación
   - Lista de asistentes
   - Recordatorios automáticos (24h antes)
   - Integración con galería de fotos

5. **Vistas**
   - Listado de comunicados con filtros
   - Detalle de comunicado
   - Formulario de comunicado
   - Calendario de eventos
   - Listado de eventos
   - Detalle de evento con mapa
   - Modal de confirmación de asistencia

**Entregables:**
- ✅ CRUD de comunicados
- ✅ CRUD de eventos
- ✅ Calendario interactivo
- ✅ Sistema de confirmación
- ✅ Notificaciones automáticas

**Duración:** 5 días laborables

---

### 5.4 Fase 4: Proyectos y Reconocimientos (Semana 4) 🏆

**Objetivo:** Gestión de proyectos estilo Kanban y sistema de reconocimientos

**Tareas:**

1. **ProyectoController**
   ```php
   index()           // Listado de proyectos
   create()
   store()
   show($id)         // Vista detalle + Kanban
   edit($id)
   update($id)
   destroy($id)
   actualizarProgreso($id)
   tableroKanban($id)
   crearTarea($proyecto_id)
   actualizarTarea($tarea_id)
   moverTarea($tarea_id)
   eliminarTarea($tarea_id)
   exportarPDF($id)
   estadisticas($id)
   ```

2. **Funcionalidades de proyectos**
   - Vista de lista con filtros
   - Vista de tablero Kanban
   - Tareas con drag & drop
   - Estados de tarea (pendiente, en progreso, revisión, completada)
   - Asignación de tareas a empleados
   - Fechas de vencimiento
   - Prioridades
   - Dependencias entre tareas
   - Progreso automático del proyecto
   - Timeline/Gantt simplificado
   - Comentarios por tarea
   - Adjuntos por tarea

3. **ReconocimientoController**
   ```php
   index()           // Muro de reconocimientos
   create()
   store()
   show($id)
   edit($id)
   update($id)
   destroy($id)
   empleadoDelMes()  // Vista especial
   estadisticas()    // Por empleado, tipo, departamento
   ```

4. **Funcionalidades de reconocimientos**
   - Tipos predefinidos (empleado del mes, aniversario, logro, excelencia, etc.)
   - Formulario con empleado, tipo, descripción
   - Upload de imagen/diploma
   - Destacar en dashboard
   - Notificar a empleado reconocido
   - Estadísticas de reconocimientos
   - Filtros por tipo, fecha, empleado
   - Exportar reconocimientos a PDF

5. **Vistas**
   - Listado de proyectos
   - Tablero Kanban con drag & drop
   - Detalle de proyecto con estadísticas
   - Formulario de proyecto
   - Muro de reconocimientos
   - Detalle de reconocimiento
   - Formulario de reconocimiento
   - Dashboard de empleado del mes

**Entregables:**
- ✅ Gestión completa de proyectos
- ✅ Tablero Kanban funcional
- ✅ Sistema de tareas
- ✅ Sistema de reconocimientos
- ✅ Estadísticas de proyectos

**Duración:** 5 días laborables

---

### 5.5 Fase 5: Encuestas, Documentos y Galería (Semana 5) 📊

**Objetivo:** Completar módulos de encuestas, repositorio de documentos y galería

**Tareas:**

1. **EncuestaController**
   ```php
   index()           // Listado de encuestas
   create()          // Crear con preguntas dinámicas
   store()
   show($id)         // Ver encuesta (si no respondió) o resultados
   edit($id)
   update($id)
   destroy($id)
   responder($id)    // Guardar respuestas
   resultados($id)   // Vista de resultados con gráficos
   exportarExcel($id)
   ```

2. **Funcionalidades de encuestas**
   - Constructor de encuestas dinámico
   - Tipos de pregunta:
     - Texto corto
     - Texto largo
     - Opción múltiple
     - Checkbox
     - Escala (1-5, 1-10)
     - Fecha
   - Preguntas obligatorias/opcionales
   - Orden de preguntas
   - Encuestas anónimas
   - Fecha inicio/fin
   - Visibilidad por roles
   - Resultados en tiempo real
   - Gráficos por pregunta
   - Exportar a Excel

3. **DocumentoController**
   ```php
   index()           // Repositorio con categorías
   create()
   store()
   show($id)
   edit($id)
   update($id)
   destroy($id)
   descargar($id)    // Incrementar contador
   version($id, $nueva_version)
   buscar(Request $request)
   ```

4. **Funcionalidades de documentos**
   - Categorías (políticas, manuales, formatos, etc.)
   - Upload de archivos (PDF, Word, Excel, etc.)
   - Control de versiones
   - Visibilidad por roles/departamentos
   - Contador de descargas
   - Destacar documentos importantes
   - Búsqueda por título, categoría, autor
   - Preview de PDFs (si es posible)
   - Historial de versiones

5. **GaleriaController**
   ```php
   index()           // Listado de galerías
   create()
   store()
   show($id)         // Lightbox de fotos
   edit($id)
   update($id)
   destroy($id)
   uploadFotos($id)
   eliminarFoto($foto_id)
   ordenarFotos($id)
   like($foto_id)
   ```

6. **Funcionalidades de galería**
   - Álbumes de fotos
   - Relación con eventos
   - Upload múltiple de fotos
   - Lightbox para visualización
   - Reordenar fotos (drag & drop)
   - Eliminar fotos
   - Likes por foto
   - Descripciones
   - Portada del álbum
   - Compartir álbum

7. **Vistas**
   - Constructor de encuestas
   - Formulario de respuesta
   - Dashboard de resultados con Chart.js
   - Repositorio de documentos con grid
   - Visor de documentos
   - Galería con grid de álbumes
   - Lightbox de fotos

**Entregables:**
- ✅ Sistema completo de encuestas
- ✅ Constructor dinámico
- ✅ Resultados con gráficos
- ✅ Repositorio de documentos
- ✅ Galería de fotos
- ✅ Lightbox funcional

**Duración:** 5 días laborables

---

### 5.6 Fase 6: Muro Social y Notificaciones (Semana 6) 🔔

**Objetivo:** Feed social con publicaciones, comentarios, reacciones y sistema de notificaciones

**Tareas:**

1. **MuroController**
   ```php
   index()           // Feed infinito
   loadMore()        // Paginación AJAX
   destacar($id)
   ocultar($id)
   ```

2. **Automatización del Muro**
   - Listener para crear publicaciones automáticamente:
     - Nuevo comunicado → Publicación
     - Nuevo proyecto → Publicación
     - Nuevo evento → Publicación
     - Nuevo reconocimiento → Publicación
     - Cumpleaños del día → Publicación
     - Aniversario laboral → Publicación
     - Nuevo empleado → Publicación
     - Nueva encuesta → Publicación
     - Nuevo documento destacado → Publicación

3. **ComentarioController**
   ```php
   store($publicacion_id)
   update($id)
   destroy($id)
   responder($comentario_id)   // Comentario anidado
   ```

4. **ReaccionController**
   ```php
   toggle(Request $request)    // Toggle reacción (AJAX)
   ```

5. **DirectorioController**
   ```php
   index()           // Directorio con búsqueda
   show($id)         // Perfil de empleado
   organigrama()     // Vista de organigrama
   buscar(Request $request)
   exportarVCard($id)
   ```

6. **NotificacionController**
   ```php
   index()           // Centro de notificaciones
   marcarLeida($id)
   marcarTodasLeidas()
   eliminar($id)
   getNoLeidas()     // AJAX para badge
   ```

7. **NotificacionService**
   - Crear notificaciones automáticas:
     - Nuevo comunicado → Notificar a todos
     - Asignación de tarea → Notificar a asignado
     - Comentario en publicación → Notificar a autor
     - Reacción a comentario → Notificar a autor del comentario
     - Evento próximo (24h antes) → Notificar a asistentes confirmados
     - Cumpleaños de compañero → Notificar a departamento
     - Reconocimiento → Notificar a empleado reconocido
     - Mención en comentario (@usuario) → Notificar a mencionado

8. **Vistas**
   - Feed del muro (estilo Facebook)
   - Card de publicación con:
     - Tipo (con icono y color)
     - Título y contenido
     - Imagen (si aplica)
     - Fecha y autor
     - Botones de reacción
     - Contador de comentarios
     - Lista de comentarios
     - Formulario de comentario
   - Directorio de empleados con:
     - Grid de tarjetas
     - Buscador
     - Filtros por departamento, cargo
     - Perfil de empleado completo
   - Centro de notificaciones
   - Dropdown de notificaciones

**Entregables:**
- ✅ Feed social funcional
- ✅ Sistema de comentarios anidados
- ✅ Reacciones (like, love, etc.)
- ✅ Directorio de empleados
- ✅ Sistema de notificaciones
- ✅ Centro de notificaciones

**Duración:** 5 días laborables

---

### 5.7 Fase 7: Integración y Pulido (Semana 7) 🎨

**Objetivo:** Integrar con módulos existentes, modificar Home y pulir detalles

**Tareas:**

1. **Modificar HomeController**
   ```php
   public function index()
   {
       // Redirigir a extranet/dashboard
       return redirect()->route('extranet.dashboard');
   }
   ```

2. **Actualizar rutas (routes/web.php)**
   ```php
   // Cambiar ruta de home
   Route::get('/home', [ExtranetDashboardController::class, 'index'])->name('home');

   // Grupo de rutas Extranet
   Route::prefix('extranet')->name('extranet.')->group(function () {
       Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       Route::resource('comunicados', ComunicadoController::class);
       Route::resource('proyectos', ProyectoController::class);
       Route::resource('eventos', EventoExtranetController::class);
       // ... más rutas
   });
   ```

3. **Actualizar sidebar (sidebar.blade.php)**
   - Agregar sección "Extranet" al menú
   - Menú items:
     - 🏠 Dashboard
     - 📢 Comunicados
     - 📋 Proyectos
     - 📅 Eventos
     - 🏆 Reconocimientos
     - 📊 Encuestas
     - 📁 Documentos
     - 📸 Galería
     - 👥 Directorio
   - Permisos por ítem

4. **Actualizar permisos (RolesSeeder)**
   ```php
   // Sidebar
   'sidebar_extranet',

   // Comunicados
   'ver-comunicados',
   'crear-comunicado',
   'editar-comunicado',
   'eliminar-comunicado',
   'fijar-comunicado',

   // Proyectos
   'ver-proyectos',
   'crear-proyecto',
   'editar-proyecto',
   'eliminar-proyecto',
   'gestionar-tareas',

   // ... demás permisos
   ```

5. **Crear eventos y listeners**
   - Registrar en EventServiceProvider
   - Implementar lógica de notificaciones
   - Implementar creación de publicaciones en muro

6. **Optimizaciones**
   - Eager loading en queries
   - Cache de widgets (Redis)
   - Índices en BD
   - Compresión de imágenes
   - Lazy loading de imágenes
   - Paginación infinita en muro

7. **Responsive y UX**
   - Versión móvil de dashboard
   - Touch gestures para móvil
   - Indicadores de carga
   - Mensajes de éxito/error
   - Confirmaciones antes de eliminar
   - Tooltips explicativos

**Entregables:**
- ✅ Home redirige a Extranet
- ✅ Menú actualizado
- ✅ Permisos configurados
- ✅ Eventos funcionando
- ✅ Optimizaciones aplicadas
- ✅ UX pulida

**Duración:** 5 días laborables

---

## 6. FUNCIONALIDADES DETALLADAS

### 6.1 Dashboard Principal

**Secciones del Dashboard:**

```
┌─────────────────────────────────────────────────────────────┐
│  MiPortal - Extranet Corporativa                            │
│  Hola, [Nombre Empleado]  👤 [Foto]  🔔 (3)                │
└─────────────────────────────────────────────────────────────┘

┌────────── Comunicados Fijados ──────────┐
│  [Carrusel de 3 comunicados urgentes]   │
└─────────────────────────────────────────┘

┌── Estadísticas ────┬── Cumpleaños ──┬── Aniversarios ─┐
│ 👥 245 Empleados   │ 🎂 Hoy (3)     │ 🎉 Esta semana │
│ 📢 12 Comunicados  │ Juan Pérez     │ María López    │
│ 📋 8 Proyectos     │ Ana García     │ 5 años         │
│ 📅 5 Eventos       │ Luis Martín    │                │
└────────────────────┴────────────────┴────────────────┘

┌─────── Nuevos Empleados ───────┬──── Eventos Próximos ────┐
│ Carmen Ruiz - Agente           │ 📅 15 Mar - Capacitación │
│ Hace 2 días                    │ 📅 18 Mar - Team Building│
│                                │ 📅 20 Mar - Conferencia  │
│ Pedro Sánchez - Supervisor     │                          │
│ Hace 5 días                    │ [Ver todos los eventos]  │
└────────────────────────────────┴──────────────────────────┘

┌───────────────── Proyectos Activos ──────────────────────┐
│ Migración MiPortal 3.0  [████████░░] 80%  ⚠️ Atrasado   │
│ Renovación Infraestructura [██████████] 100% ✅ Completo │
│ Sistema de Reportes [████░░░░░░] 40%  ⏳ En tiempo      │
└──────────────────────────────────────────────────────────┘

┌───────────────────── Muro Social ────────────────────────┐
│                                                          │
│  📢 [COMUNICADO] Nueva política de teletrabajo           │
│  Hace 2 horas • 👍 15  💬 8                              │
│                                                          │
│  🏆 [RECONOCIMIENTO] ¡Felicitaciones a María por...     │
│  Hace 4 horas • ❤️ 32  💬 12                            │
│                                                          │
│  🎂 [CUMPLEAÑOS] Hoy celebramos a Juan Pérez             │
│  Hace 6 horas • 🎉 28  💬 20                            │
│                                                          │
│  [Cargar más...]                                         │
└──────────────────────────────────────────────────────────┘
```

### 6.2 Sistema de Notificaciones

**Tipos de Notificaciones:**

| Tipo | Icono | Color | Trigger |
|------|-------|-------|---------|
| Comunicado | 📢 | Azul | Nuevo comunicado publicado |
| Proyecto | 📋 | Verde | Asignación de tarea |
| Evento | 📅 | Naranja | Recordatorio 24h antes |
| Reconocimiento | 🏆 | Dorado | Reconocimiento otorgado |
| Comentario | 💬 | Gris | Comentario en tu publicación |
| Reacción | ❤️ | Rojo | Reacción a tu comentario |
| Mención | @ | Azul | Mención en comentario |
| Cumpleaños | 🎂 | Rosa | Cumpleaños de compañero |
| Aniversario | 🎉 | Morado | Aniversario laboral |
| Sistema | ⚙️ | Gris | Mensajes del sistema |

**Centro de Notificaciones:**

```
┌─── Notificaciones (5 no leídas) ───────────────────┐
│                                                    │
│  🏆 Te otorgaron un reconocimiento                │
│     "Excelente trabajo en el proyecto..."         │
│     Hace 10 minutos                       [Leer]  │
│                                                    │
│  💬 Juan comentó en tu publicación                │
│     "Gran iniciativa, me parece excelente..."     │
│     Hace 1 hora                          [Leer]  │
│                                                    │
│  📅 Recordatorio: Evento "Capacitación" mañana    │
│     15 de marzo, 9:00 AM - Sala de juntas         │
│     Hace 2 horas                         [Leer]  │
│                                                    │
│  [Marcar todas como leídas]  [Ver todas]          │
└────────────────────────────────────────────────────┘
```

---

## 7. AUTOMATIZACIONES

### 7.1 Automatizaciones de Datos

**1. Cumpleaños Automáticos**

```php
// app/Services/Extranet/CumpleanosService.php

public function obtenerProximos($dias = 7)
{
    $hoy = Carbon::now();

    return empleado::where('EMP_ACTIVO', 1)
        ->select('EMP_ID', 'EMP_NOMBRES', 'EMP_APELLIDOS', 'EMP_FECHA_NACIMIENTO', 'EMP_FOTO_URL')
        ->get()
        ->filter(function ($empleado) use ($hoy, $dias) {
            $cumple = Carbon::parse($empleado->EMP_FECHA_NACIMIENTO);
            $proxCumple = $cumple->setYear($hoy->year);

            if ($proxCumple < $hoy) {
                $proxCumple->addYear();
            }

            return $proxCumple->diffInDays($hoy) <= $dias;
        })
        ->map(function ($empleado) use ($hoy) {
            $cumple = Carbon::parse($empleado->EMP_FECHA_NACIMIENTO);
            $proxCumple = $cumple->setYear($hoy->year);

            if ($proxCumple < $hoy) {
                $proxCumple->addYear();
            }

            $empleado->dias_faltantes = $proxCumple->diffInDays($hoy);
            $empleado->edad_cumplira = $hoy->year - $cumple->year;
            $empleado->es_hoy = $proxCumple->isToday();

            return $empleado;
        })
        ->sortBy('dias_faltantes')
        ->values();
}

public function crearPublicacionCumpleanos($empleado)
{
    PublicacionMuro::create([
        'tipo' => 'cumpleanos',
        'referencia_id' => $empleado->EMP_ID,
        'titulo' => "¡Feliz cumpleaños {$empleado->EMP_NOMBRES}!",
        'contenido' => "Hoy celebramos el cumpleaños de {$empleado->nombre_completo}. ¡Felicidades!",
        'imagen_url' => $empleado->EMP_FOTO_URL,
        'comentarios_habilitados' => true,
    ]);

    // Notificar al empleado
    NotificacionService::crear([
        'empleado_id' => $empleado->EMP_ID,
        'tipo' => 'cumpleanos',
        'titulo' => '¡Feliz cumpleaños!',
        'mensaje' => 'Todo el equipo te desea un feliz cumpleaños',
        'icono' => 'cake',
        'color' => '#ff69b4',
    ]);
}
```

**2. Aniversarios Laborales Automáticos**

```php
// app/Services/Extranet/AniversarioService.php

public function obtenerProximos($dias = 7)
{
    $hoy = Carbon::now();

    $contratos = emp_contrato::with('empleado')
        ->whereHas('empleado', function ($query) {
            $query->where('EMP_ACTIVO', 1);
        })
        ->where('EMC_ESTADO', 1)
        ->get()
        ->filter(function ($contrato) use ($hoy, $dias) {
            $inicio = Carbon::parse($contrato->EMC_FECHA_INICIO);
            $proxAniversario = $inicio->setYear($hoy->year);

            if ($proxAniversario < $hoy) {
                $proxAniversario->addYear();
            }

            return $proxAniversario->diffInDays($hoy) <= $dias;
        })
        ->map(function ($contrato) use ($hoy) {
            $inicio = Carbon::parse($contrato->EMC_FECHA_INICIO);
            $anosServicio = $hoy->year - $inicio->year;

            $contrato->anos_servicio = $anosServicio;
            $contrato->dias_faltantes = $inicio->setYear($hoy->year)->diffInDays($hoy);
            $contrato->es_hoy = $inicio->setYear($hoy->year)->isToday();

            return $contrato;
        })
        ->sortBy('dias_faltantes')
        ->values();

    return $contratos;
}
```

**3. Nuevos Empleados Automáticos**

```php
public function obtenerNuevos($dias = 30)
{
    return empleado::with(['cargo', 'campana'])
        ->where('EMP_ACTIVO', 1)
        ->where('created_at', '>=', Carbon::now()->subDays($dias))
        ->orderBy('created_at', 'DESC')
        ->get()
        ->map(function ($empleado) {
            $empleado->dias_antiguedad = Carbon::parse($empleado->created_at)->diffInDays(Carbon::now());
            return $empleado;
        });
}
```

**4. Publicaciones Automáticas en Muro**

```php
// app/Listeners/Extranet/CrearPublicacionMuro.php

class CrearPublicacionMuro implements ShouldQueue
{
    public function handle($event)
    {
        // Detectar tipo de evento
        switch (get_class($event)) {
            case ComunicadoPublicado::class:
                $this->crearPublicacionComunicado($event->comunicado);
                break;

            case ProyectoCreado::class:
                $this->crearPublicacionProyecto($event->proyecto);
                break;

            case EventoCreado::class:
                $this->crearPublicacionEvento($event->evento);
                break;

            case ReconocimientoOtorgado::class:
                $this->crearPublicacionReconocimiento($event->reconocimiento);
                break;
        }
    }

    private function crearPublicacionComunicado($comunicado)
    {
        PublicacionMuro::create([
            'tipo' => 'comunicado',
            'referencia_id' => $comunicado->id,
            'titulo' => $comunicado->titulo,
            'contenido' => Str::limit($comunicado->contenido, 200),
            'imagen_url' => $comunicado->imagen_url,
            'autor_id' => $comunicado->autor_id,
            'destacado' => $comunicado->fijado,
        ]);
    }
}
```

**5. Notificaciones Automáticas**

```php
// app/Services/Extranet/NotificacionService.php

class NotificacionService
{
    public static function comunicadoPublicado($comunicado)
    {
        // Obtener empleados según visibilidad
        $empleados = self::obtenerDestinatarios($comunicado->visible_para);

        foreach ($empleados as $empleado) {
            NotificacionExtranet::create([
                'empleado_id' => $empleado->EMP_ID,
                'tipo' => 'comunicado',
                'titulo' => 'Nuevo comunicado: ' . $comunicado->titulo,
                'mensaje' => Str::limit($comunicado->contenido, 100),
                'referencia_tipo' => 'comunicado',
                'referencia_id' => $comunicado->id,
                'url' => route('extranet.comunicados.show', $comunicado->id),
                'icono' => 'bullhorn',
                'color' => self::getColorPrioridad($comunicado->prioridad),
                'importante' => $comunicado->prioridad === 'critica',
            ]);
        }
    }

    public static function tareaAsignada($tarea)
    {
        NotificacionExtranet::create([
            'empleado_id' => $tarea->asignado_a,
            'tipo' => 'proyecto',
            'titulo' => 'Nueva tarea asignada',
            'mensaje' => $tarea->titulo,
            'referencia_tipo' => 'tarea',
            'referencia_id' => $tarea->id,
            'url' => route('extranet.proyectos.show', $tarea->proyecto_id),
            'icono' => 'tasks',
            'color' => '#28a745',
        ]);
    }

    public static function recordatorioEvento($evento, $horasAntes = 24)
    {
        $asistentes = $evento->asistentes()
            ->where('estado_confirmacion', 'confirmado')
            ->get();

        foreach ($asistentes as $asistente) {
            NotificacionExtranet::create([
                'empleado_id' => $asistente->empleado_id,
                'tipo' => 'evento',
                'titulo' => 'Recordatorio: ' . $evento->titulo,
                'mensaje' => "El evento comienza mañana a las {$evento->hora_inicio}",
                'referencia_tipo' => 'evento',
                'referencia_id' => $evento->id,
                'url' => route('extranet.eventos.show', $evento->id),
                'icono' => 'calendar',
                'color' => '#ff9800',
                'importante' => true,
            ]);
        }
    }
}
```

### 7.2 Comandos Artisan Programados

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // Publicar cumpleaños del día a las 8:00 AM
    $schedule->call(function () {
        $service = new CumpleanosService();
        $cumpleanerosHoy = $service->obtenerProximos(0); // Solo hoy

        foreach ($cumpleanerosHoy as $empleado) {
            $service->crearPublicacionCumpleanos($empleado);
        }
    })->dailyAt('08:00');

    // Publicar aniversarios del día a las 8:00 AM
    $schedule->call(function () {
        $service = new AniversarioService();
        $aniversariosHoy = $service->obtenerProximos(0);

        foreach ($aniversariosHoy as $contrato) {
            $service->crearPublicacionAniversario($contrato);
        }
    })->dailyAt('08:00');

    // Recordatorio de eventos (24 horas antes) a las 9:00 AM
    $schedule->call(function () {
        $manana = Carbon::tomorrow();
        $eventos = EventoExtranet::whereDate('fecha_inicio', $manana)
            ->where('estado', 'publicado')
            ->get();

        foreach ($eventos as $evento) {
            NotificacionService::recordatorioEvento($evento);
        }
    })->dailyAt('09:00');

    // Limpieza de notificaciones antiguas (más de 90 días leídas)
    $schedule->call(function () {
        NotificacionExtranet::where('leida', true)
            ->where('leida_at', '<', Carbon::now()->subDays(90))
            ->delete();
    })->weekly();
}
```

---

## 8. SISTEMA DE PERMISOS

### 8.1 Permisos Definidos (30 nuevos)

```php
// database/seeders/RolesSeeder.php - Agregar

$permisos_extranet = [
    // Sidebar
    'sidebar_extranet',

    // Dashboard
    'ver-dashboard-extranet',

    // Comunicados
    'ver-comunicados',
    'crear-comunicado',
    'editar-comunicado',
    'eliminar-comunicado',
    'fijar-comunicado',
    'archivar-comunicado',

    // Proyectos
    'ver-proyectos',
    'crear-proyecto',
    'editar-proyecto',
    'eliminar-proyecto',
    'gestionar-tareas',
    'asignar-tareas',

    // Eventos
    'ver-eventos',
    'crear-evento',
    'editar-evento',
    'eliminar-evento',
    'gestionar-asistentes',

    // Reconocimientos
    'ver-reconocimientos',
    'crear-reconocimiento',
    'editar-reconocimiento',
    'eliminar-reconocimiento',

    // Encuestas
    'ver-encuestas',
    'crear-encuesta',
    'editar-encuesta',
    'eliminar-encuesta',
    'ver-resultados-encuesta',
    'responder-encuesta',

    // Documentos
    'ver-documentos',
    'subir-documento',
    'editar-documento',
    'eliminar-documento',
    'gestionar-versiones',

    // Galería
    'ver-galeria',
    'crear-album',
    'editar-album',
    'eliminar-album',
    'subir-fotos',
    'eliminar-fotos',

    // Muro
    'ver-muro',
    'publicar-muro',
    'comentar',
    'reaccionar',

    // Directorio
    'ver-directorio',

    // Notificaciones
    'gestionar-notificaciones',
];
```

### 8.2 Asignación de Permisos por Rol

```php
// Desarrollador - TODOS los permisos
$rol_desarrollador->givePermissionTo($permisos_extranet);

// Administrador - Todos excepto eliminar
$permisos_admin = collect($permisos_extranet)
    ->reject(fn($p) => Str::contains($p, 'eliminar'))
    ->toArray();
$rol_administrador->givePermissionTo($permisos_admin);

// Content Manager - Gestión de contenido
$rol_content_manager = Role::create(['name' => 'Content Manager']);
$rol_content_manager->givePermissionTo([
    'sidebar_extranet',
    'ver-dashboard-extranet',
    'ver-comunicados', 'crear-comunicado', 'editar-comunicado', 'fijar-comunicado',
    'ver-eventos', 'crear-evento', 'editar-evento', 'gestionar-asistentes',
    'ver-reconocimientos', 'crear-reconocimiento', 'editar-reconocimiento',
    'ver-galeria', 'crear-album', 'editar-album', 'subir-fotos',
    'ver-documentos', 'subir-documento', 'editar-documento',
    'ver-muro', 'publicar-muro', 'comentar', 'reaccionar',
    'ver-directorio',
]);

// Supervisor - Proyectos y reconocimientos de su equipo
$rol_supervisor->givePermissionTo([
    'sidebar_extranet',
    'ver-dashboard-extranet',
    'ver-comunicados',
    'ver-proyectos', 'crear-proyecto', 'editar-proyecto', 'gestionar-tareas', 'asignar-tareas',
    'ver-eventos',
    'ver-reconocimientos', 'crear-reconocimiento',
    'ver-encuestas', 'responder-encuesta', 'ver-resultados-encuesta',
    'ver-documentos',
    'ver-galeria',
    'ver-muro', 'comentar', 'reaccionar',
    'ver-directorio',
]);

// Agente - Solo lectura y participación
$rol_agente->givePermissionTo([
    'sidebar_extranet',
    'ver-dashboard-extranet',
    'ver-comunicados',
    'ver-proyectos',
    'ver-eventos',
    'ver-reconocimientos',
    'ver-encuestas', 'responder-encuesta',
    'ver-documentos',
    'ver-galeria',
    'ver-muro', 'comentar', 'reaccionar',
    'ver-directorio',
]);
```

### 8.3 Middleware de Permisos

```php
// routes/web.php

Route::prefix('extranet')->name('extranet.')->middleware(['auth', 'permission:sidebar_extranet'])->group(function () {

    // Dashboard (todos con acceso a extranet)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Comunicados
    Route::resource('comunicados', ComunicadoController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('comunicados', ComunicadoController::class)->only(['create', 'store'])->middleware('permission:crear-comunicado');
    Route::resource('comunicados', ComunicadoController::class)->only(['edit', 'update'])->middleware('permission:editar-comunicado');
    Route::delete('comunicados/{id}', [ComunicadoController::class, 'destroy'])->middleware('permission:eliminar-comunicado');

    // ... más rutas con permisos específicos
});
```

---

## 9. TIMELINE Y SPRINTS

### 9.1 Gantt Simplificado

```
Semana 1: Fundamentos [████████] 100%
  ├── Estructura de directorios [██]
  ├── Migraciones [██]
  ├── Modelos [███]
  └── Seeders [█]

Semana 2: Dashboard y Widgets [        ] 0%
  ├── DashboardController [ ]
  ├── Widgets automatizados [ ]
  ├── Servicios [ ]
  └── Vistas [ ]

Semana 3: Comunicados y Eventos [        ] 0%
  ├── ComunicadoController [ ]
  ├── EventoController [ ]
  ├── Sistema de notificaciones [ ]
  └── Vistas [ ]

Semana 4: Proyectos y Reconocimientos [        ] 0%
  ├── ProyectoController [ ]
  ├── Kanban [ ]
  ├── ReconocimientoController [ ]
  └── Vistas [ ]

Semana 5: Encuestas, Docs y Galería [        ] 0%
  ├── EncuestaController [ ]
  ├── DocumentoController [ ]
  ├── GaleriaController [ ]
  └── Vistas [ ]

Semana 6: Muro y Notificaciones [        ] 0%
  ├── MuroController [ ]
  ├── ComentarioController [ ]
  ├── NotificacionController [ ]
  └── Directorio [ ]

Semana 7: Integración y Pulido [        ] 0%
  ├── Modificar Home [ ]
  ├── Actualizar sidebar [ ]
  ├── Eventos/Listeners [ ]
  ├── Optimizaciones [ ]
  └── Testing [ ]
```

### 9.2 Hitos (Milestones)

| Hito | Fecha Objetivo | Entregables | Estado |
|------|----------------|-------------|--------|
| **M1: Base de datos completa** | 12 Mar 2026 | Migraciones, Modelos, Seeders | 🔄 En progreso |
| **M2: Dashboard funcional** | 19 Mar 2026 | Dashboard con 6 widgets | ⏳ Pendiente |
| **M3: Comunicación interna** | 26 Mar 2026 | Comunicados y Eventos CRUD | ⏳ Pendiente |
| **M4: Colaboración** | 2 Abr 2026 | Proyectos Kanban y Reconocimientos | ⏳ Pendiente |
| **M5: Engagement** | 9 Abr 2026 | Encuestas, Docs y Galería | ⏳ Pendiente |
| **M6: Social** | 16 Abr 2026 | Muro, Comentarios y Notificaciones | ⏳ Pendiente |
| **M7: Producción** | 23 Abr 2026 | Sistema completo integrado | ⏳ Pendiente |

---

## 10. PRUEBAS Y VALIDACIÓN

### 10.1 Plan de Pruebas

**Pruebas Unitarias (PHPUnit):**
- Servicios: CumpleanosService, AniversarioService
- Modelos: Relaciones Eloquent
- Helpers: Funciones auxiliares

**Pruebas de Integración:**
- Controladores: Respuestas HTTP
- Eventos: Listeners ejecutados
- Notificaciones: Creadas correctamente

**Pruebas Funcionales (Feature Tests):**
- CRUD completo de cada módulo
- Permisos por rol
- Flujo completo de usuario

**Pruebas de Aceptación:**
- Escenarios de usuario
- Casos de uso reales
- UAT con usuarios finales

### 10.2 Casos de Prueba Críticos

```php
// tests/Feature/Extranet/ComunicadoTest.php

class ComunicadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_ver_comunicados()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('ver-comunicados');

        $response = $this->actingAs($user)->get(route('extranet.comunicados.index'));

        $response->assertStatus(200);
    }

    public function test_admin_puede_crear_comunicado()
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('crear-comunicado');

        $response = $this->actingAs($admin)->post(route('extranet.comunicados.store'), [
            'titulo' => 'Comunicado de prueba',
            'contenido' => 'Contenido del comunicado',
            'tipo' => 'general',
            'fecha_inicio' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comunicados', ['titulo' => 'Comunicado de prueba']);
    }

    public function test_agente_no_puede_crear_comunicado()
    {
        $agente = User::factory()->create();
        $agente->assignRole('Agente');

        $response = $this->actingAs($agente)->post(route('extranet.comunicados.store'), []);

        $response->assertStatus(403);
    }
}
```

---

## 11. DOCUMENTACIÓN

### 11.1 Documentación Técnica

**Archivos a crear:**

1. **README_EXTRANET.md**
   - Descripción del módulo
   - Instalación
   - Configuración
   - Uso básico

2. **API_EXTRANET.md**
   - Endpoints disponibles
   - Parámetros
   - Respuestas
   - Ejemplos

3. **PERMISOS_EXTRANET.md**
   - Lista de permisos
   - Roles predefinidos
   - Ejemplos de asignación

4. **MODELOS_EXTRANET.md**
   - Diagrama ER
   - Descripción de tablas
   - Relaciones

### 11.2 Documentación de Usuario

**Manuales a crear:**

1. **Manual de Usuario - Empleado**
   - Cómo usar el dashboard
   - Cómo ver comunicados
   - Cómo confirmar asistencia a eventos
   - Cómo responder encuestas
   - Cómo comentar y reaccionar

2. **Manual de Administrador**
   - Cómo publicar comunicados
   - Cómo crear eventos
   - Cómo otorgar reconocimientos
   - Cómo crear encuestas
   - Cómo gestionar documentos

3. **Manual de Project Manager**
   - Cómo crear proyectos
   - Cómo usar el Kanban
   - Cómo asignar tareas
   - Cómo hacer seguimiento

---

## 12. CONSIDERACIONES FINALES

### 12.1 Riesgos Identificados

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| Sobrecarga de BD con archivos | Media | Alto | Almacenar en filesystem, no en BD |
| Performance con muchos usuarios | Media | Alto | Implementar cache Redis, índices |
| Incompatibilidad con módulos existentes | Baja | Medio | Testing exhaustivo, código aislado |
| Resistencia al cambio de usuarios | Media | Medio | Capacitación, soporte, feedback |

### 12.2 Métricas de Éxito

| Métrica | Objetivo | Cómo Medir |
|---------|----------|------------|
| Adopción | 80% empleados activos en 1 mes | Usuarios únicos/día |
| Engagement | 50% interacción semanal | Comentarios, reacciones, vistas |
| Comunicación | 90% comunicados leídos | Vistas/total empleados |
| Satisfacción | 4/5 estrellas | Encuesta NPS |
| Performance | < 2s carga dashboard | Lighthouse, GTmetrix |

### 12.3 Mantenimiento Post-Lanzamiento

**Tareas recurrentes:**
- Monitoreo de errores (Sentry)
- Revisión de feedback de usuarios
- Actualización de contenido destacado
- Limpieza de datos antiguos
- Optimización de queries lentas
- Actualización de dependencias

---

**FIN DEL PLAN DE IMPLEMENTACIÓN**

---

## PRÓXIMOS PASOS

1. ✅ **Revisar y aprobar este plan**
2. ⏳ **Comenzar Fase 1: Fundamentos**
3. ⏳ **Configurar entorno de desarrollo**
4. ⏳ **Crear rama Git: `feature/modulo-extranet`**
5. ⏳ **Iniciar desarrollo**

**¿Listo para comenzar?** 🚀
