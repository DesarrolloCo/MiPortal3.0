-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-07-2025 a las 16:32:46
-- Versión del servidor: 10.11.10-MariaDB-cll-lve
-- Versión de PHP: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `contactacom_APP_MI_PORTAL`
--
CREATE DATABASE IF NOT EXISTS `contactacom_APP_MI_PORTAL` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `contactacom_APP_MI_PORTAL`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `ARE_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `ARE_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre de las areas existentes en la infraestructura de las instalaciones de contacta',
  `ARE_DESCRIPCION` varchar(200) DEFAULT NULL COMMENT 'Contiene una descripcion detallada del area',
  `ARE_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada area, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `campanas`
--

CREATE TABLE `campanas` (
  `CAM_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `ARE_ID` int(11) DEFAULT NULL COMMENT 'Area en la que esta la campana',
  `UNC_ID` int(11) NOT NULL,
  `CAM_CODE` int(11) NOT NULL COMMENT 'Codigo de la campaña suministrada por la plataforma SIGO',
  `CAM_NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre de las campañas existentes Ejemplo: SuperEfectivo, Jamar Ventas, Crediminutos.',
  `CAM_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de las campañas, Ejemplo:1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) NOT NULL COMMENT 'ID del usuario que creo el registro',
  `CAM_FECHA_CREACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Contiene la fecha en que fue creado el registro que tiene asignado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `CAR_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `CAR_NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre de cada cargo en la empresa Ejemplo: Supervisor, Cordinador',
  `CAR_CODE` int(11) NOT NULL COMMENT 'Codigo del cargo que se maneja por SIGO',
  `CAR_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) NOT NULL COMMENT 'ID del usuario que creo el registro',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Contiene la fecha en que fue creado el registro que tiene asignado',
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `car_funciones`
--

CREATE TABLE `car_funciones` (
  `CAF_ID` int(11) NOT NULL,
  `CAR_ID` int(11) NOT NULL,
  `CAF_NOMBRE` varchar(200) NOT NULL,
  `CAF_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `CLI_ID` int(11) NOT NULL COMMENT 'Lave primaria',
  `CLI_CODE` int(11) NOT NULL COMMENT 'Codigo de cada una de los clientes suministrada por la plataforma SIGO (Centro de costo)',
  `CLI_NOMBRE` varchar(45) NOT NULL COMMENT 'Nombre de cada unos de los clientes pertenecientes a la empresa',
  `CLI_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO,              3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) NOT NULL DEFAULT 1 COMMENT 'ID del usuario que creo el registro',
  `CLI_FECHA_CREACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Contiene la fecha en que fue creado el registro que tiene asignado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `SER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cli_consumos`
--

CREATE TABLE `cli_consumos` (
  `CLC_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `PRC_ID` int(11) NOT NULL COMMENT 'id del contrato del proveedor que brindo el servicio',
  `CLI_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla clientes',
  `CLC_CONSUMO` int(11) NOT NULL COMMENT 'Consumo general del cliente',
  `CLC_MES` int(11) DEFAULT NULL COMMENT 'numero del mes que se consumio el servicio',
  `CLC_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) NOT NULL COMMENT 'usuario que creo el registro',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes`
--

CREATE TABLE `cortes` (
  `COR_ID` int(11) NOT NULL,
  `COR_CANTIDAD` int(11) NOT NULL,
  `COR_DESCRIPCION` varchar(200) NOT NULL,
  `COR_TOTAL` double NOT NULL,
  `COR_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `DEP_ID` int(11) NOT NULL,
  `DEP_NOMBRE` varchar(255) NOT NULL,
  `DEP_ESTADO` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emc_funciones`
--

CREATE TABLE `emc_funciones` (
  `EMF_ID` int(11) NOT NULL,
  `EMC_ID` int(11) NOT NULL,
  `EMF_NOMBRE` varchar(200) NOT NULL,
  `EMF_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `EMP_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `USER_ID` int(11) NOT NULL COMMENT 'ID del usuario con el que el empleado entra al aplicativo',
  `CAR_ID` int(11) DEFAULT NULL COMMENT 'Id del cargo del empleado',
  `EMP_CODE` varchar(50) NOT NULL COMMENT 'Codigo del empleado suministrado y manejado por SIGO',
  `EMP_CEDULA` int(11) NOT NULL COMMENT 'Se guarda las cedulas de cada empleado la cual no debe repetirse.',
  `MUN_ID` int(11) NOT NULL COMMENT 'Municipio en el que se expedio la cedula',
  `EMP_NOMBRES` varchar(45) NOT NULL COMMENT 'Nombre completo del empleado, se maneja asi porque SIGO lo maneja todo junto',
  `EMP_DIRECCION` varchar(100) DEFAULT NULL COMMENT 'Guarda las direcciones de cada empleado',
  `EMP_TELEFONO` varchar(25) DEFAULT NULL COMMENT 'Telefonos de contacto del empleado el cual es suministrado por SIGO',
  `EMP_SEXO` enum('F','M') DEFAULT NULL COMMENT 'Es el genero del empleado, M = Masculino y F = Femenino',
  `EMP_FECHA_NACIMIENTO` date DEFAULT NULL COMMENT 'Fecha de nacimiento de cada empleado',
  `EMP_FECHA_INGRESO` date DEFAULT NULL COMMENT 'Fecha de ingreso a la empresa de cada empleado',
  `EMP_FECHA_RETIRO` date DEFAULT NULL COMMENT 'Fecha de retiro de la empresa de cada empleado',
  `EMP_SUELDO` double DEFAULT 1000000 COMMENT 'Sueldo base de cada empleado',
  `EMP_TIPO_CONTRATO` varchar(45) DEFAULT NULL COMMENT 'Tipo de contrato de cada empleado Ejemplo: Aprendiz, Contrato de Prestacion de servicio, Contrato fijo',
  `CAM_ID` int(11) NOT NULL COMMENT 'id de la campaña en la que trabajara',
  `DEP_ID` int(11) NOT NULL,
  `CLI_ID` int(11) DEFAULT NULL COMMENT 'Codigo de cada una de los clientes suministrada por la plataforma SIGO (Centro de costo)',
  `EMP_PAIS` varchar(45) DEFAULT NULL COMMENT 'Nacionalidad de cada uno de los empleados, Ejemplo: Colombia, Venezuela, España, Estados Unidos',
  `EMP_CIUDAD` varchar(45) DEFAULT NULL COMMENT 'Ciudad o municipio la que reside cada empleado, Ejmeplo: Barranquilla, Puerto Colombia, Soledad',
  `EMP_EMAIL` varchar(45) NOT NULL COMMENT 'Direccion de Correo electronico de contacto de cada empleado',
  `EMP_ACTIVO` enum('SI','NO') NOT NULL DEFAULT 'SI' COMMENT 'indica si un empleado puede salir o no en la plataforma, se marcara "NO" cuando ya no este contratado',
  `EMP_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Disparadores `empleados`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_email_en_users` AFTER UPDATE ON `empleados` FOR EACH ROW BEGIN
    IF OLD.EMP_EMAIL != NEW.EMP_EMAIL THEN
        UPDATE users
        SET email = NEW.EMP_EMAIL
        WHERE email = OLD.EMP_EMAIL AND email != NEW.EMP_EMAIL;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emp_contratos`
--

CREATE TABLE `emp_contratos` (
  `EMC_ID` int(11) NOT NULL,
  `EMP_ID` int(11) NOT NULL,
  `CAR_ID` int(11) NOT NULL,
  `TIC_ID` int(11) NOT NULL,
  `EMC_SUELDO` double NOT NULL,
  `EMC_FECHA_INI` date NOT NULL,
  `EMC_FECHA_FIN` date DEFAULT NULL,
  `EMC_FINALIZADO` enum('SI','NO') NOT NULL DEFAULT 'NO',
  `USER_ID_FINALIZADO` int(11) DEFAULT NULL,
  `EMC_FECHA_FINALIZADO` date DEFAULT NULL,
  `EMC_ESTADO` int(11) NOT NULL DEFAULT 1,
  `USER_CREATED` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `EQU_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `ARE_ID` int(11) NOT NULL COMMENT 'Id del area',
  `EQU_SERIAL` varchar(200) NOT NULL COMMENT 'Serial de los equipos de computo que funciona como identificacion de la maquina',
  `EQU_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre del equipo esipulada bajo la estipulada en el estandar de la seguridad de la informacion',
  `EQU_PRECIO` double NOT NULL DEFAULT 0,
  `EQU_TIPO` enum('Propio','Alquilado') NOT NULL COMMENT 'El tipo determina el propietario del equipo',
  `EQU_OBSERVACIONES` varchar(200) DEFAULT NULL COMMENT 'Observaciones para tener en cuenta al momento de realizar alguna opcion en el equipo',
  `PRO_ID` int(11) DEFAULT NULL COMMENT 'Id del proveedor del equipo (puede ser nulo)',
  `EQU_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `EQU_STATUS` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equ_asignados`
--

CREATE TABLE `equ_asignados` (
  `EAS_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `EQU_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos',
  `EMP_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla empleados',
  `EAS_FECHA_ENTREGA` date DEFAULT NULL COMMENT 'Fecha en la que se realizara la entrega de asignacion del equipo',
  `EAS_EVIDENCIA` varchar(200) DEFAULT NULL COMMENT 'Direccion de documento que soporta la accion de asignacion de el equipo',
  `EAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ASIGNADO, 2= DESASIGNADO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evi_asignados`
--

CREATE TABLE `evi_asignados` (
  `EVI_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `EAS_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos asignados',
  `EVI_NOMBRE` varchar(200) NOT NULL,
  `EVI_FECHA` date NOT NULL,
  `EVI_EVIDENCIA` longblob DEFAULT NULL COMMENT 'Direccion de documento que soporta la accion de asignacion de el equipo',
  `EVI_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ASIGNADO, 2= DESASIGNADO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `GRU_ID` int(11) NOT NULL,
  `CAM_ID` int(11) NOT NULL,
  `EMP_ID` int(11) NOT NULL,
  `GRU_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `USER_ID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hardwares`
--

CREATE TABLE `hardwares` (
  `HAR_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `HAR_TIPO` enum('Pieza','Dispositivo') NOT NULL COMMENT 'El tipo determina si es uana Pieza o un Dispositivo del equipo',
  `HAR_DESCRIPCION` varchar(200) DEFAULT NULL COMMENT 'Detalles adicionales del hardware',
  `MAR_ID` int(11) NOT NULL DEFAULT 1 COMMENT 'Llave segundaria, relacion con la tabla marcas',
  `HAR_MODELO` varchar(100) NOT NULL COMMENT 'Un modelo es un bosquejo que representa un conjunto real con cierto grado de precisión y en la forma más completa posible',
  `HAR_SERIAL` varchar(100) NOT NULL COMMENT 'SN o número de serie es un número exclusivo que registra información como la fecha de producción y el estado de garantía del producto',
  `HAR_OBSERVACION` varchar(200) DEFAULT NULL COMMENT 'La descripcion que detalla y explica lo sucedido con el objeto',
  `HAR_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `har_asignados`
--

CREATE TABLE `har_asignados` (
  `HAS_ID` int(11) NOT NULL COMMENT 'Llave primaria	',
  `HAR_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla hardware',
  `EQU_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos',
  `HAS_COMENTARIO` varchar(200) DEFAULT NULL COMMENT 'Detalles de la asignacion',
  `HAS_STATUS` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ASIGNADO, 2= CAMBIO, 0=ANULADO',
  `HAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas`
--

CREATE TABLE `horas` (
  `HOR_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `HOR_INICIO` varchar(10) NOT NULL COMMENT 'Este es el campo donde se almacenara la hora inicial de trabajo : 00:00:00',
  `HOR_FINAL` varchar(10) NOT NULL COMMENT 'Este es el campo donde se almacenara la hora final del trabajo : 23:59:59',
  `HOR_TOTAL` int(11) NOT NULL DEFAULT 1 COMMENT 'Este es el campo donde se almacenara el total dehoras trabajadas',
  `HOR_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO,   4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) DEFAULT 1 COMMENT 'ID del usuario que creo el registro',
  `HOR_FECHA_CREACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Contiene la fecha en que fue creado el registro que tiene asignado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE `informes` (
  `INF_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `CAM_ID` int(11) NOT NULL COMMENT 'Relación con la tabla de campañas.',
  `INF_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre del informe (reports)',
  `INF_URL` varchar(200) NOT NULL COMMENT 'Iframe de power BI o plataforma de reportes usada.',
  `CLI_ID` int(11) DEFAULT NULL COMMENT '	Asignación de cliente a reporte.',
  `INF_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de las campañas, Ejemplo:1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornadas`
--

CREATE TABLE `jornadas` (
  `JOR_ID` int(11) NOT NULL,
  `JOR_NOMBRE` mediumtext DEFAULT NULL,
  `JOR_INICIO` int(11) NOT NULL,
  `JOR_FINAL` int(11) NOT NULL,
  `JOR_CANT_HORAS` int(11) NOT NULL DEFAULT 0,
  `JOR_ESTADO` int(11) NOT NULL DEFAULT 1,
  `USER_ID` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `LIC_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `LIC_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre de donde procede la licencia',
  `LIC_PRECIO` double NOT NULL COMMENT 'Valor neto de la licencia',
  `LIC_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_asignados`
--

CREATE TABLE `lic_asignados` (
  `LAS_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `LIC_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla licencias',
  `EMP_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla empleados',
  `LAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mallas`
--

CREATE TABLE `mallas` (
  `MAL_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `CAM_ID` int(11) DEFAULT NULL COMMENT 'Este es el campo donde se almacenara la ID del contrato',
  `EMP_ID` int(11) NOT NULL COMMENT 'Guardara El ID del empleado que tiene asignada la malla',
  `MAL_DIA` varchar(30) NOT NULL,
  `MAL_INICIO` datetime NOT NULL,
  `MAL_FINAL` datetime NOT NULL,
  `USER_ID` int(11) NOT NULL COMMENT 'ID del usuario que creo el registro',
  `MAL_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `MAL_DELETE_FROM` int(11) DEFAULT NULL COMMENT 'quien borro la malla',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `MAN_ID` int(11) NOT NULL COMMENT 'Llave primaria.',
  `EQU_ID` int(11) NOT NULL COMMENT 'Equipo asignado al mantenimiento.',
  `MAN_PROVEEDOR` varchar(200) NOT NULL COMMENT 'Corresponde al proveedor que ejecutara el mantenimiento dentro o fuera de las instalaciones de CONTACTA',
  `MAN_FECHA` date NOT NULL COMMENT 'Corresponde a la fecha asignada para llevar a cabo el mantenimiento programado.',
  `MAN_STATUS` int(11) NOT NULL DEFAULT 1 COMMENT 'Estatus en el cual se encuentra el mantenimiento, Ejemplo: 1 = ACTIVO, 2= REALIZADO, 0=ANULADO',
  `MAN_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 5 = REALIZADO, 0=ANULADO',
  `MAN_TECNICO` int(11) NOT NULL COMMENT 'Técnico asignado al mantenimiento.',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `man_asignados`
--

CREATE TABLE `man_asignados` (
  `MAS_ID` int(11) NOT NULL,
  `MAN_ID` int(11) NOT NULL,
  `MAS_TIPO` enum('Preventivo','Correctivo','Proveedor') NOT NULL,
  `MAS_ACTIVIDAD` text NOT NULL,
  `MAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `MAR_ID` int(11) NOT NULL,
  `MAR_NOMBRE` varchar(200) NOT NULL,
  `MAR_DESCRIPCION` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `MUN_ID` int(11) NOT NULL,
  `MUN_NOMBRE` varchar(255) NOT NULL,
  `DEP_ID` int(11) NOT NULL,
  `MUN_ESTADO` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedades`
--

CREATE TABLE `novedades` (
  `NOV_ID` int(11) NOT NULL COMMENT 'llave primaria',
  `MAL_ID` int(11) NOT NULL COMMENT 'id de la malla que tiene la novedad',
  `TIN_ID` int(11) NOT NULL COMMENT 'id de la novedad asignada',
  `EMP_ID` int(11) NOT NULL COMMENT 'id de empleado que se le asigna la novedad',
  `NOV_FECHA` varchar(25) NOT NULL COMMENT 'fecha de la novedad',
  `NOV_ESTADO` int(11) NOT NULL DEFAULT 1,
  `USER_ID` int(11) NOT NULL COMMENT 'id del usuario que creo el registro',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE `parametros` (
  `PAR_ID` int(11) NOT NULL,
  `PAR_DESCRIPCION` varchar(200) DEFAULT NULL,
  `PAR_TIPO` enum('1','2','3','4','5') NOT NULL COMMENT '1: Entero, 2: Double, 3: Fecha, 4: Varchar, 5: Boleano',
  `PAR_VALOR` text DEFAULT NULL,
  `PAR_CODE` varchar(200) NOT NULL,
  `PAR_USUARIO` int(11) NOT NULL,
  `PAR_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `PRO_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `PRO_NOMBRE` varchar(255) NOT NULL COMMENT 'Nombre de los proveedores de minuteria',
  `PRO_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_contratos`
--

CREATE TABLE `pro_contratos` (
  `PRC_ID` int(11) NOT NULL COMMENT 'llave primaria unica',
  `PRS_ID` int(11) NOT NULL COMMENT 'id del provedor con el servicio que presta',
  `PRC_PRECIO_FIJO` float DEFAULT NULL COMMENT 'precio base de un contrato, puede ser nulo',
  `PRC_CANTIDAD` int(11) DEFAULT NULL COMMENT 'cantidad de servicio prestado: 100 minutos, 20 correos, 500 mensajes',
  `PRC_FECHA_INICIO` datetime DEFAULT NULL COMMENT 'fecha de inicio del contrato',
  `PRC_FECHA_FIN` datetime DEFAULT NULL COMMENT 'fecha de fin del contrato',
  `PRC_TIPO` enum('Fijo','Recarga') NOT NULL COMMENT 'que tipo de contrato es: recarga o fijo',
  `PRC_PAGADO` enum('SI','NO') NOT NULL DEFAULT 'NO' COMMENT 'Estado del pago del contrato',
  `PRC_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pro_servicios`
--

CREATE TABLE `pro_servicios` (
  `PRS_ID` int(11) NOT NULL COMMENT 'llave primaria unica',
  `TPS_ID` int(11) NOT NULL COMMENT 'id del servicio que presta el proveedor',
  `PRO_ID` int(11) NOT NULL COMMENT 'id del proveedor',
  `PRS_PRECIO_U` double NOT NULL COMMENT 'precio unitario del servicio: 0.55 por minuto',
  `PRS_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puertos`
--

CREATE TABLE `puertos` (
  `PUE_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `PUE_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre del puerto',
  `ARE_ID` int(11) DEFAULT NULL COMMENT 'Llave segundaria, relacion con la tabla areas',
  `EQU_ID` int(11) DEFAULT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos',
  `PUE_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = OCUPADO, 2= LIBRE, 3 = DISPONIBLE, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reds`
--

CREATE TABLE `reds` (
  `RED_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `EQU_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos',
  `RED_NOMBRE_IP` varchar(200) NOT NULL COMMENT 'Nombre de ip',
  `RED_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre del red. Ejemplo 0.0.0.0',
  `RED_TIPO_IP` enum('Dinamic','Estatic') NOT NULL COMMENT 'Tipo de ip',
  `RED_TIPO` enum('Grupo de trabajo','Dominio') NOT NULL COMMENT 'Tipo de red',
  `RED_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `REG_ID` int(11) NOT NULL COMMENT 'llave primaria',
  `REG_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `USER_ID` int(11) NOT NULL COMMENT 'codigo del usuario que ingresa al registro',
  `REG_NOMBRE` varchar(200) NOT NULL COMMENT 'nombre de la persona registrada',
  `REG_TIPO_ID` varchar(200) NOT NULL,
  `REG_CEDULA` int(11) NOT NULL COMMENT 'documento de identidad de la persona registrada',
  `REG_EMPRESA` varchar(200) NOT NULL COMMENT 'nombre de la empresa de la cual la persona registrada pertenece',
  `REG_MOTIVO_INGRESO` varchar(200) NOT NULL COMMENT 'motivo del ingreso del visitante',
  `REG_EQUIPO` varchar(200) NOT NULL COMMENT 'aqui se guardara si el visitante ingresara algun equipo a las instalaciones de la empresa',
  `REG_SERIAL` varchar(200) DEFAULT NULL COMMENT 'aqui se ingresara el serial si la visita serial del equipo si se ingresa ',
  `REG_FECHA_HORA_SALIDA` datetime DEFAULT NULL COMMENT 'aqui se registra la fecha y hora de salida del visitante',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `SER_ID` int(11) NOT NULL,
  `SER_CODE` varchar(200) NOT NULL,
  `SER_NOMBRE` varchar(200) NOT NULL,
  `SER_ESTADO` int(11) NOT NULL DEFAULT 1,
  `USER_ID` int(11) NOT NULL,
  `SER_FECHA_CREACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `UNI_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `softwares`
--

CREATE TABLE `softwares` (
  `SOF_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `SOF_NOMBRE` varchar(200) NOT NULL COMMENT 'Nombre del software',
  `SOF_VERSION` varchar(200) NOT NULL COMMENT 'Version del software',
  `SOF_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sof_asignados`
--

CREATE TABLE `sof_asignados` (
  `SAS_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `SOF_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla de software',
  `EQU_ID` int(11) NOT NULL COMMENT 'Llave segundaria, relacion con la tabla equipos',
  `SAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `TEC_ID` int(11) NOT NULL,
  `EMP_ID` int(11) NOT NULL,
  `TEC_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT '	Estado en el cual se encuentra cada empleado, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_contratos`
--

CREATE TABLE `tipos_contratos` (
  `TIC_ID` int(11) NOT NULL,
  `TIC_NOMBRE` varchar(200) NOT NULL,
  `TIC_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_estados`
--

CREATE TABLE `tipos_estados` (
  `TIE_ID` int(11) NOT NULL,
  `TIE_NOMBRE` varchar(200) NOT NULL,
  `TIE_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_novedades`
--

CREATE TABLE `tipos_novedades` (
  `TIN_ID` int(11) NOT NULL COMMENT 'llave primaria',
  `TIN_NOMBRE` varchar(25) NOT NULL COMMENT 'nombre de la novedad',
  `TIN_TIPO` int(11) NOT NULL COMMENT 'tipo de novedad 0 resta horas, 1 sumador de horas',
  `TIN_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'estado 0 inactivo, 1 activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_servicios`
--

CREATE TABLE `tipos_servicios` (
  `tps_id` int(11) NOT NULL,
  `tps_nombre` varchar(200) NOT NULL,
  `tps_estado` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_mantenimientos`
--

CREATE TABLE `tipo_mantenimientos` (
  `TIP_ID` int(11) NOT NULL,
  `TIP_TIPO` enum('Logico','Fisico') NOT NULL,
  `TIP_NOMBRE` varchar(200) NOT NULL,
  `TIP_DESCRIPCION` text DEFAULT NULL,
  `TIP_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tip_asignados`
--

CREATE TABLE `tip_asignados` (
  `TAS_ID` int(11) NOT NULL,
  `MAN_ID` int(11) NOT NULL,
  `TIP_ID` int(11) NOT NULL,
  `TAS_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de los clientes, Ejemplo: 1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_negocios`
--

CREATE TABLE `unidad_negocios` (
  `UNI_ID` int(11) NOT NULL COMMENT 'Llave primaria',
  `UNI_NOMBRE` varchar(50) NOT NULL COMMENT 'Nombre de cada una de las unidades de negocio Ejemplo: Cobranzas, Saud, TI ETC..',
  `UNI_ESTADO` int(11) NOT NULL DEFAULT 1 COMMENT 'Estado de cada una de las unidades de neogicios, Ejemplo:1 = ACTIVO, 2= INACTIVO, 3 = SUSPENDIDO, 4 = RETIRADO, 0=ANULADO',
  `UNI_CODE` int(11) NOT NULL COMMENT 'Codigo de cada una de las campañas suministrada por la plataforma SIGO',
  `USER_ID` int(11) NOT NULL COMMENT 'ID del usuario que creo el registro',
  `UNI_FECHA_CREACION` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Contiene la fecha en que fue creado el registro que tiene asignado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uni_clis`
--

CREATE TABLE `uni_clis` (
  `UNC_ID` int(11) NOT NULL,
  `UNI_ID` int(11) NOT NULL,
  `CLI_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `UNC_ESTADO` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Disparadores `users`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_email_en_empleados` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.email != NEW.email THEN
        UPDATE empleados
        SET EMP_EMAIL = NEW.email
        WHERE EMP_EMAIL = OLD.email AND EMP_EMAIL != NEW.email;
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`ARE_ID`);

--
-- Indices de la tabla `campanas`
--
ALTER TABLE `campanas`
  ADD PRIMARY KEY (`CAM_ID`),
  ADD KEY `FK_USER_CAMPAÑA` (`USER_ID`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`CAR_ID`),
  ADD KEY `FK_USER_CARGOS` (`USER_ID`);

--
-- Indices de la tabla `car_funciones`
--
ALTER TABLE `car_funciones`
  ADD PRIMARY KEY (`CAF_ID`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`CLI_ID`),
  ADD KEY `FK_USER_CLIENTES` (`USER_ID`),
  ADD KEY `FK_SER_CLI` (`SER_ID`);

--
-- Indices de la tabla `cli_consumos`
--
ALTER TABLE `cli_consumos`
  ADD PRIMARY KEY (`CLC_ID`);

--
-- Indices de la tabla `cortes`
--
ALTER TABLE `cortes`
  ADD PRIMARY KEY (`COR_ID`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`DEP_ID`);

--
-- Indices de la tabla `emc_funciones`
--
ALTER TABLE `emc_funciones`
  ADD PRIMARY KEY (`EMF_ID`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`EMP_ID`),
  ADD KEY `FK_USER_EMPLEADOS` (`USER_ID`),
  ADD KEY `FK_CAR_EMP` (`CAR_ID`),
  ADD KEY `fk_cam_emp` (`CAM_ID`);

--
-- Indices de la tabla `emp_contratos`
--
ALTER TABLE `emp_contratos`
  ADD PRIMARY KEY (`EMC_ID`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`EQU_ID`);

--
-- Indices de la tabla `equ_asignados`
--
ALTER TABLE `equ_asignados`
  ADD PRIMARY KEY (`EAS_ID`);

--
-- Indices de la tabla `evi_asignados`
--
ALTER TABLE `evi_asignados`
  ADD PRIMARY KEY (`EVI_ID`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`GRU_ID`),
  ADD KEY `fk_gru_cam` (`CAM_ID`),
  ADD KEY `fk_gru_emp` (`EMP_ID`);

--
-- Indices de la tabla `hardwares`
--
ALTER TABLE `hardwares`
  ADD PRIMARY KEY (`HAR_ID`);

--
-- Indices de la tabla `har_asignados`
--
ALTER TABLE `har_asignados`
  ADD PRIMARY KEY (`HAS_ID`);

--
-- Indices de la tabla `horas`
--
ALTER TABLE `horas`
  ADD PRIMARY KEY (`HOR_ID`),
  ADD KEY `FK_USER_HORAS` (`USER_ID`);

--
-- Indices de la tabla `informes`
--
ALTER TABLE `informes`
  ADD PRIMARY KEY (`INF_ID`);

--
-- Indices de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  ADD PRIMARY KEY (`JOR_ID`),
  ADD KEY `fk_jor_inicio_hor` (`JOR_INICIO`),
  ADD KEY `fk_jor_final_hor` (`JOR_FINAL`);

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`LIC_ID`);

--
-- Indices de la tabla `lic_asignados`
--
ALTER TABLE `lic_asignados`
  ADD PRIMARY KEY (`LAS_ID`);

--
-- Indices de la tabla `mallas`
--
ALTER TABLE `mallas`
  ADD PRIMARY KEY (`MAL_ID`),
  ADD KEY `FK_EMPLEADO_MALLA` (`EMP_ID`),
  ADD KEY `FK_CONTRATOS_MALLA` (`CAM_ID`),
  ADD KEY `FK_USER_MALLA` (`USER_ID`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`MAR_ID`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`MUN_ID`),
  ADD KEY `departamento_id` (`DEP_ID`);

--
-- Indices de la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD PRIMARY KEY (`NOV_ID`),
  ADD KEY `nov_tin_fk` (`TIN_ID`),
  ADD KEY `nov_emp_fk` (`EMP_ID`),
  ADD KEY `nov_mal_fk` (`MAL_ID`),
  ADD KEY `nov_usu_id` (`USER_ID`);

--
-- Indices de la tabla `parametros`
--
ALTER TABLE `parametros`
  ADD PRIMARY KEY (`PAR_ID`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`PRO_ID`);

--
-- Indices de la tabla `pro_contratos`
--
ALTER TABLE `pro_contratos`
  ADD PRIMARY KEY (`PRC_ID`);

--
-- Indices de la tabla `puertos`
--
ALTER TABLE `puertos`
  ADD PRIMARY KEY (`PUE_ID`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`REG_ID`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`SER_ID`);

--
-- Indices de la tabla `softwares`
--
ALTER TABLE `softwares`
  ADD PRIMARY KEY (`SOF_ID`);

--
-- Indices de la tabla `sof_asignados`
--
ALTER TABLE `sof_asignados`
  ADD PRIMARY KEY (`SAS_ID`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`TEC_ID`);

--
-- Indices de la tabla `tipos_contratos`
--
ALTER TABLE `tipos_contratos`
  ADD PRIMARY KEY (`TIC_ID`);

--
-- Indices de la tabla `tipos_estados`
--
ALTER TABLE `tipos_estados`
  ADD PRIMARY KEY (`TIE_ID`);

--
-- Indices de la tabla `tipos_novedades`
--
ALTER TABLE `tipos_novedades`
  ADD PRIMARY KEY (`TIN_ID`);

--
-- Indices de la tabla `tipos_servicios`
--
ALTER TABLE `tipos_servicios`
  ADD PRIMARY KEY (`tps_id`);

--
-- Indices de la tabla `tip_asignados`
--
ALTER TABLE `tip_asignados`
  ADD PRIMARY KEY (`TAS_ID`);

--
-- Indices de la tabla `unidad_negocios`
--
ALTER TABLE `unidad_negocios`
  ADD PRIMARY KEY (`UNI_ID`),
  ADD KEY `FK_USER_UNIDAD_NEGOCIOS` (`USER_ID`);

--
-- Indices de la tabla `uni_clis`
--
ALTER TABLE `uni_clis`
  ADD PRIMARY KEY (`UNC_ID`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `ARE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `campanas`
--
ALTER TABLE `campanas`
  MODIFY `CAM_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `CAR_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `car_funciones`
--
ALTER TABLE `car_funciones`
  MODIFY `CAF_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `CLI_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Lave primaria';

--
-- AUTO_INCREMENT de la tabla `cli_consumos`
--
ALTER TABLE `cli_consumos`
  MODIFY `CLC_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `cortes`
--
ALTER TABLE `cortes`
  MODIFY `COR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `DEP_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `emc_funciones`
--
ALTER TABLE `emc_funciones`
  MODIFY `EMF_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `EMP_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `emp_contratos`
--
ALTER TABLE `emp_contratos`
  MODIFY `EMC_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `EQU_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `equ_asignados`
--
ALTER TABLE `equ_asignados`
  MODIFY `EAS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `evi_asignados`
--
ALTER TABLE `evi_asignados`
  MODIFY `EVI_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `GRU_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hardwares`
--
ALTER TABLE `hardwares`
  MODIFY `HAR_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `har_asignados`
--
ALTER TABLE `har_asignados`
  MODIFY `HAS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria	';

--
-- AUTO_INCREMENT de la tabla `horas`
--
ALTER TABLE `horas`
  MODIFY `HOR_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `informes`
--
ALTER TABLE `informes`
  MODIFY `INF_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  MODIFY `JOR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `licencias`
--
ALTER TABLE `licencias`
  MODIFY `LIC_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `lic_asignados`
--
ALTER TABLE `lic_asignados`
  MODIFY `LAS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `mallas`
--
ALTER TABLE `mallas`
  MODIFY `MAL_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `MAR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `MUN_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `novedades`
--
ALTER TABLE `novedades`
  MODIFY `NOV_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'llave primaria';

--
-- AUTO_INCREMENT de la tabla `parametros`
--
ALTER TABLE `parametros`
  MODIFY `PAR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `PRO_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `pro_contratos`
--
ALTER TABLE `pro_contratos`
  MODIFY `PRC_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'llave primaria unica';

--
-- AUTO_INCREMENT de la tabla `puertos`
--
ALTER TABLE `puertos`
  MODIFY `PUE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `REG_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'llave primaria';

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `SER_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `softwares`
--
ALTER TABLE `softwares`
  MODIFY `SOF_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `sof_asignados`
--
ALTER TABLE `sof_asignados`
  MODIFY `SAS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `TEC_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_contratos`
--
ALTER TABLE `tipos_contratos`
  MODIFY `TIC_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_estados`
--
ALTER TABLE `tipos_estados`
  MODIFY `TIE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_novedades`
--
ALTER TABLE `tipos_novedades`
  MODIFY `TIN_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'llave primaria';

--
-- AUTO_INCREMENT de la tabla `tipos_servicios`
--
ALTER TABLE `tipos_servicios`
  MODIFY `tps_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tip_asignados`
--
ALTER TABLE `tip_asignados`
  MODIFY `TAS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `unidad_negocios`
--
ALTER TABLE `unidad_negocios`
  MODIFY `UNI_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Llave primaria';

--
-- AUTO_INCREMENT de la tabla `uni_clis`
--
ALTER TABLE `uni_clis`
  MODIFY `UNC_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `campanas`
--
ALTER TABLE `campanas`
  ADD CONSTRAINT `FK_USER_CAMPAÑA` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD CONSTRAINT `FK_USER_CARGOS` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `FK_SER_CLI` FOREIGN KEY (`SER_ID`) REFERENCES `servicios` (`SER_ID`),
  ADD CONSTRAINT `FK_USER_CLIENTES` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `horas`
--
ALTER TABLE `horas`
  ADD CONSTRAINT `FK_USER_HORAS` FOREIGN KEY (`USER_ID`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`DEP_ID`) REFERENCES `departamentos` (`DEP_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
