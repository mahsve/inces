-- MySQL Script generated by MySQL Workbench
-- 06/02/20 08:10:00
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema sistema_inces
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sistema_inces
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sistema_inces` DEFAULT CHARACTER SET utf8 ;
USE `sistema_inces` ;

-- -----------------------------------------------------
-- Table `sistema_inces`.`t_estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_estado` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_ciudad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_ciudad` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(70) NOT NULL,
  `codigo_estado` INT NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_estado_ciudad_idx` (`codigo_estado` ASC),
  CONSTRAINT `fk_estado_ciudad`
    FOREIGN KEY (`codigo_estado`)
    REFERENCES `sistema_inces`.`t_estado` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_actividad_economica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_actividad_economica` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_ocupacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_ocupacion` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `enfoque` CHAR(1) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_municipio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_municipio` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(70) NOT NULL,
  `codigo_estado` INT NOT NULL,
  `estatus` CHAR(1) NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_estado_municipio_idx` (`codigo_estado` ASC),
  CONSTRAINT `fk_estado_municipio`
    FOREIGN KEY (`codigo_estado`)
    REFERENCES `sistema_inces`.`t_estado` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_parroquia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_parroquia` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(70) NOT NULL,
  `codigo_municipio` INT NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_municipio_parroqui_idx` (`codigo_municipio` ASC),
  CONSTRAINT `fk_municipio_parroqui`
    FOREIGN KEY (`codigo_municipio`)
    REFERENCES `sistema_inces`.`t_municipio` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_datos_personales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_datos_personales` (
  `nacionalidad` CHAR(1) NOT NULL,
  `cedula` VARCHAR(12) NOT NULL,
  `nombre1` VARCHAR(25) NOT NULL,
  `nombre2` VARCHAR(25) NULL,
  `apellido1` VARCHAR(25) NOT NULL,
  `apellido2` VARCHAR(25) NULL,
  `sexo` CHAR(1) NOT NULL,
  `fecha_n` DATE NULL,
  `lugar_n` VARCHAR(100) NULL,
  `codigo_ocupacion` INT NULL,
  `estado_civil` CHAR(1) NULL,
  `nivel_instruccion` CHAR(2) NULL,
  `titulo_acade` VARCHAR(100) NULL,
  `mision_participado` VARCHAR(150) NULL,
  `codigo_ciudad` INT NOT NULL,
  `codigo_parroquia` INT NULL,
  `direccion` VARCHAR(200) NOT NULL DEFAULT 'S/D',
  `telefono1` CHAR(11) NOT NULL,
  `telefono2` CHAR(11) NULL,
  `correo` VARCHAR(80) NULL,
  `tipo_persona` CHAR(1) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`nacionalidad`, `cedula`),
  INDEX `fk_ciudad_datos_personales_idx` (`codigo_ciudad` ASC),
  INDEX `fk_ocupacion_datos_personales_idx` (`codigo_ocupacion` ASC),
  INDEX `fk_parroqui_datos_personales_idx` (`codigo_parroquia` ASC),
  CONSTRAINT `fk_ciudad_datos_personales`
    FOREIGN KEY (`codigo_ciudad`)
    REFERENCES `sistema_inces`.`t_ciudad` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ocupacion_datos_personales`
    FOREIGN KEY (`codigo_ocupacion`)
    REFERENCES `sistema_inces`.`t_ocupacion` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_parroqui_datos_personales`
    FOREIGN KEY (`codigo_parroquia`)
    REFERENCES `sistema_inces`.`t_parroquia` (`codigo`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_empresa` (
  `rif` VARCHAR(12) NOT NULL,
  `nil` VARCHAR(10) NULL,
  `razon_social` VARCHAR(150) NOT NULL,
  `codigo_actividad` INT NOT NULL,
  `codigo_aportante` CHAR(12) NULL,
  `telefono1` CHAR(11) NOT NULL,
  `telefono2` CHAR(11) NULL,
  `codigo_ciudad` INT NOT NULL,
  `direccion` VARCHAR(300) NOT NULL,
  `nacionalidad_contacto` CHAR(1) NOT NULL,
  `persona_contacto` VARCHAR(12) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`rif`),
  INDEX `fk_ciudad_empresa_idx` (`codigo_ciudad` ASC),
  INDEX `fk_actividad_economica_empresa_idx` (`codigo_actividad` ASC),
  INDEX `fk_datos_contacto_empresa_idx` (`nacionalidad_contacto` ASC, `persona_contacto` ASC),
  CONSTRAINT `fk_ciudad_empresa`
    FOREIGN KEY (`codigo_ciudad`)
    REFERENCES `sistema_inces`.`t_ciudad` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_actividad_economica_empresa`
    FOREIGN KEY (`codigo_actividad`)
    REFERENCES `sistema_inces`.`t_actividad_economica` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_datos_contacto_empresa`
    FOREIGN KEY (`nacionalidad_contacto` , `persona_contacto`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_oficio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_oficio` (
  `codigo` VARCHAR(10) NOT NULL,
  `nombre` VARCHAR(120) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_informe_social`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_informe_social` (
  `numero` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `nacionalidad_aprendiz` CHAR(1) NOT NULL,
  `cedula_aprendiz` VARCHAR(12) NOT NULL,
  `codigo_oficio` VARCHAR(10) NOT NULL,
  `turno` CHAR(1) NOT NULL,
  `nacionalidad_fac` CHAR(1) NOT NULL,
  `cedula_facilitador` VARCHAR(12) NOT NULL,
  `condicion_vivienda` VARCHAR(1000) NOT NULL,
  `caracteristicas_generales` VARCHAR(1000) NOT NULL,
  `diagnostico_social` VARCHAR(1000) NOT NULL,
  `diagnostico_preliminar` VARCHAR(1000) NOT NULL,
  `conclusiones` VARCHAR(1000) NOT NULL,
  `enfermos` CHAR(1) NOT NULL,
  `representante` INT NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'E',
  PRIMARY KEY (`numero`),
  INDEX `fk_oficio_informe_social_idx` (`codigo_oficio` ASC),
  INDEX `fk_datos_aprendiz_informe_social_idx` (`nacionalidad_aprendiz` ASC, `cedula_aprendiz` ASC),
  INDEX `fk_datos_facilitador_informe_social_idx` (`nacionalidad_fac` ASC, `cedula_facilitador` ASC),
  CONSTRAINT `fk_oficio_informe_social`
    FOREIGN KEY (`codigo_oficio`)
    REFERENCES `sistema_inces`.`t_oficio` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_datos_aprendiz_informe_social`
    FOREIGN KEY (`nacionalidad_aprendiz` , `cedula_aprendiz`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_datos_facilitador_informe_social`
    FOREIGN KEY (`nacionalidad_fac` , `cedula_facilitador`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_ficha_aprendiz`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_ficha_aprendiz` (
  `numero` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATE NOT NULL,
  `tipo_inscripcion` CHAR(1) NOT NULL,
  `ficha_anterior` INT NULL,
  `correlativo` VARCHAR(10) NULL,
  `numero_orden` VARCHAR(3) NOT NULL,
  `numero_informe` INT NOT NULL,
  `empresa_actual` VARCHAR(12) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'I',
  PRIMARY KEY (`numero`),
  INDEX `fk_empresa_actual_ficha_aprendiz_idx` (`empresa_actual` ASC),
  INDEX `fk_informe_social_ficha_aprendiz_idx` (`numero_informe` ASC),
  INDEX `fk_ficha_anterior_ficha_aprendiz_idx` (`ficha_anterior` ASC),
  CONSTRAINT `fk_empresa_actual_ficha_aprendiz`
    FOREIGN KEY (`empresa_actual`)
    REFERENCES `sistema_inces`.`t_empresa` (`rif`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_informe_social_ficha_aprendiz`
    FOREIGN KEY (`numero_informe`)
    REFERENCES `sistema_inces`.`t_informe_social` (`numero`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ficha_anterior_ficha_aprendiz`
    FOREIGN KEY (`ficha_anterior`)
    REFERENCES `sistema_inces`.`t_ficha_aprendiz` (`numero`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_rol` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_usuario` (
  `usuario` VARCHAR(30) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,
  `nacionalidad` CHAR(1) NOT NULL,
  `cedula` VARCHAR(12) NOT NULL,
  `pregunta_seguridad` VARCHAR(100) NOT NULL,
  `respuesta_seguridad` VARCHAR(255) NOT NULL,
  `codigo_rol` INT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`usuario`),
  INDEX `fk_datos_personales_usuario_idx` (`nacionalidad` ASC, `cedula` ASC),
  INDEX `fk_rol_usuario_idx` (`codigo_rol` ASC),
  CONSTRAINT `fk_datos_personales_usuario`
    FOREIGN KEY (`nacionalidad` , `cedula`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_rol_usuario`
    FOREIGN KEY (`codigo_rol`)
    REFERENCES `sistema_inces`.`t_rol` (`codigo`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_modulo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_modulo` (
  `codigo` VARCHAR(10) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `codigo_oficio` VARCHAR(10) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_oficio_modulo_idx` (`codigo_oficio` ASC),
  CONSTRAINT `fk_oficio_modulo`
    FOREIGN KEY (`codigo_oficio`)
    REFERENCES `sistema_inces`.`t_oficio` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_familia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_familia` (
  `id_familiar` INT NOT NULL AUTO_INCREMENT,
  `numero_informe` INT NOT NULL,
  `nombre1` VARCHAR(25) NOT NULL,
  `nombre2` VARCHAR(25) NULL,
  `apellido1` VARCHAR(25) NOT NULL,
  `apellido2` VARCHAR(25) NULL,
  `fecha_n` DATE NOT NULL,
  `sexo` CHAR(1) NOT NULL,
  `parentesco` CHAR(2) NOT NULL,
  `codigo_ocupacion` INT NOT NULL,
  `trabaja` CHAR(1) NOT NULL,
  `ingresos` FLOAT NOT NULL,
  PRIMARY KEY (`id_familiar`),
  INDEX `fk_informe_social_familia_idx` (`numero_informe` ASC),
  INDEX `fk_ocupacion_familia_idx` (`codigo_ocupacion` ASC),
  CONSTRAINT `fk_informe_social_familia`
    FOREIGN KEY (`numero_informe`)
    REFERENCES `sistema_inces`.`t_informe_social` (`numero`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ocupacion_familia`
    FOREIGN KEY (`codigo_ocupacion`)
    REFERENCES `sistema_inces`.`t_ocupacion` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_bitacora`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_bitacora` (
  `numero` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(30) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `operacion` CHAR(1) NOT NULL,
  `valor_viejo` VARCHAR(10000) NOT NULL,
  `valor_nuevo` VARCHAR(10000) NOT NULL,
  PRIMARY KEY (`numero`),
  INDEX `fk_usuario_bitacora_idx` (`usuario` ASC),
  CONSTRAINT `fk_usuario_bitacora`
    FOREIGN KEY (`usuario`)
    REFERENCES `sistema_inces`.`t_usuario` (`usuario`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_asignatura`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_asignatura` (
  `codigo` VARCHAR(10) NOT NULL,
  `nombre` VARCHAR(60) NOT NULL,
  `codigo_modulo` VARCHAR(10) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_modulo_asinatura_idx` (`codigo_modulo` ASC),
  CONSTRAINT `fk_modulo_asinatura`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`t_modulo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_gestion_dinero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_gestion_dinero` (
  `numero_informe` INT NOT NULL,
  `descripcion` VARCHAR(150) NOT NULL,
  `cantidad` FLOAT NOT NULL,
  INDEX `fk_informe_social_gestion_dinero_idx` (`numero_informe` ASC),
  CONSTRAINT `fk_informe_social_gestion_dinero`
    FOREIGN KEY (`numero_informe`)
    REFERENCES `sistema_inces`.`t_informe_social` (`numero`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_datos_hogar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_datos_hogar` (
  `nacionalidad` CHAR(1) NOT NULL,
  `cedula` VARCHAR(12) NOT NULL,
  `punto_referencia` VARCHAR(200) NOT NULL,
  `tipo_area` CHAR(1) NOT NULL,
  `tipo_vivienda` CHAR(1) NOT NULL,
  `tenencia_vivienda` CHAR(1) NOT NULL,
  `agua` CHAR(1) NOT NULL,
  `electricidad` CHAR(1) NOT NULL,
  `excretas` CHAR(1) NOT NULL,
  `basura` CHAR(1) NOT NULL,
  `otros` VARCHAR(100) NULL,
  `techo` VARCHAR(100) NOT NULL,
  `paredes` VARCHAR(100) NOT NULL,
  `piso` VARCHAR(100) NOT NULL,
  `via_acceso` VARCHAR(100) NOT NULL,
  `sala` INT(1) NOT NULL,
  `comedor` INT(1) NOT NULL,
  `cocina` INT(1) NOT NULL,
  `banos` INT(1) NOT NULL,
  `n_dormitorios` INT(1) NOT NULL,
  INDEX `fk_datos_personales_datos_hogar_idx` (`nacionalidad` ASC, `cedula` ASC),
  CONSTRAINT `fk_datos_personales_datos_hogar`
    FOREIGN KEY (`nacionalidad` , `cedula`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_seccion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_seccion` (
  `codigo` VARCHAR(10) NOT NULL,
  `nombre` VARCHAR(60) NOT NULL,
  `codigo_modulo` VARCHAR(10) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_modulo_seccion_idx` (`codigo_modulo` ASC),
  CONSTRAINT `fk_modulo_seccion`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`t_modulo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`td_modulo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`td_modulo` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(300) NOT NULL,
  `fecha` DATE NOT NULL,
  `codigo_modulo` VARCHAR(10) NOT NULL,
  `codigo_seccion` VARCHAR(10) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`codigo`),
  INDEX `fk_seccion_modulo_actual_idx` (`codigo_seccion` ASC),
  INDEX `fk_modulo_modulo_actual_idx` (`codigo_modulo` ASC),
  CONSTRAINT `fk_seccion_tdmodulo`
    FOREIGN KEY (`codigo_seccion`)
    REFERENCES `sistema_inces`.`t_seccion` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_modulo_tdmodulo`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`t_modulo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`td_aprendiz`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`td_aprendiz` (
  `numero_ficha` INT NOT NULL,
  `codigo_modulo` INT NOT NULL,
  INDEX `fk_curso_aprendiz_curso_idx` (`codigo_modulo` ASC),
  INDEX `fk_ficha_aprendiz_aprendiz_curso_idx` (`numero_ficha` ASC),
  CONSTRAINT `fk_td_aprendiz_modulo`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`td_modulo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_td_aprendiz_asistencia`
    FOREIGN KEY (`numero_ficha`)
    REFERENCES `sistema_inces`.`t_ficha_aprendiz` (`numero`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`td_asignatura`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`td_asignatura` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `codigo_modulo` INT NOT NULL,
  `codigo_asignatura` VARCHAR(10) NOT NULL,
  `nacionalidad_facilitador` CHAR(1) NULL,
  `cedula_facilitador` VARCHAR(12) NULL,
  `fecha_inicio` DATE NOT NULL,
  `horas` INT NOT NULL,
  `estatus` CHAR(1) NOT NULL,
  INDEX `fk_modulo_curso_asignatura_idx` (`codigo_modulo` ASC),
  INDEX `fk_td_asignatura_asignatura_idx` (`codigo_asignatura` ASC),
  PRIMARY KEY (`codigo`),
  INDEX `fk_datos_facilitador_tdasignatura_idx` (`nacionalidad_facilitador` ASC, `cedula_facilitador` ASC),
  CONSTRAINT `fk_tdmodulo_tdasignatura`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`td_modulo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_asignatura_tdasignatura`
    FOREIGN KEY (`codigo_asignatura`)
    REFERENCES `sistema_inces`.`t_asignatura` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_datos_facilitador_tdasignatura`
    FOREIGN KEY (`nacionalidad_facilitador` , `cedula_facilitador`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_modulo_sistema`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_modulo_sistema` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(60) NOT NULL,
  `posicion` INT(2) NOT NULL,
  `icono` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`codigo`, `posicion`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`td_rol_modulo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`td_rol_modulo` (
  `codigo_rol` INT NOT NULL,
  `codigo_modulo` INT NOT NULL,
  INDEX `fk_rol_modulo_idx` (`codigo_rol` ASC),
  INDEX `fk_detalles_modulo_idx` (`codigo_modulo` ASC),
  CONSTRAINT `fk_rol_modulo`
    FOREIGN KEY (`codigo_rol`)
    REFERENCES `sistema_inces`.`t_rol` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_detalles_modulo`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`t_modulo_sistema` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_vista`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_vista` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `codigo_modulo` INT NOT NULL,
  `nombre` VARCHAR(60) NOT NULL,
  `enlace` VARCHAR(60) NOT NULL,
  `posicion` INT(2) NOT NULL,
  `icono` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_modulo_servicio_idx` (`codigo_modulo` ASC),
  CONSTRAINT `fk_modulo_vista`
    FOREIGN KEY (`codigo_modulo`)
    REFERENCES `sistema_inces`.`t_modulo_sistema` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`td_rol_vista`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`td_rol_vista` (
  `codigo_rol` INT NOT NULL AUTO_INCREMENT,
  `codigo_vista` INT NOT NULL,
  `registrar` TINYINT(1) NOT NULL,
  `modificar` TINYINT(1) NOT NULL,
  `act_desc` TINYINT(1) NOT NULL,
  `eliminar` TINYINT(1) NOT NULL,
  INDEX `fk_detalles_rol_servicio_idx` (`codigo_rol` ASC),
  INDEX `fk_detalles_servicio_rol_idx` (`codigo_vista` ASC),
  CONSTRAINT `fk_detalles_rol_servicio`
    FOREIGN KEY (`codigo_rol`)
    REFERENCES `sistema_inces`.`t_rol` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_detalles_rol_vista`
    FOREIGN KEY (`codigo_vista`)
    REFERENCES `sistema_inces`.`t_vista` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_asistencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_asistencia` (
  `numero` INT NOT NULL AUTO_INCREMENT,
  `codigo_asignatura` INT NOT NULL,
  `numero_ficha` INT NOT NULL,
  `fecha` DATE NOT NULL,
  `asistencia` CHAR(1) NOT NULL,
  INDEX `fk_td_asignatura_asistencia_idx` (`codigo_asignatura` ASC),
  PRIMARY KEY (`numero`),
  INDEX `fk_aprendiz_modulo_asistencia_idx` (`numero_ficha` ASC),
  CONSTRAINT `fk_tdasignatura_asistencia`
    FOREIGN KEY (`codigo_asignatura`)
    REFERENCES `sistema_inces`.`td_asignatura` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_aprendiz_modulo_asistencia`
    FOREIGN KEY (`numero_ficha`)
    REFERENCES `sistema_inces`.`td_aprendiz` (`numero_ficha`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_justificativo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_justificativo` (
  `numero` INT NOT NULL AUTO_INCREMENT,
  `numero_asistencia` INT NOT NULL,
  `extencion_img` VARCHAR(6) NOT NULL,
  `estatus` CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`numero`),
  INDEX `fk_justificativo_asistencia_idx` (`numero_asistencia` ASC),
  CONSTRAINT `fk_justificativo_asistencia`
    FOREIGN KEY (`numero_asistencia`)
    REFERENCES `sistema_inces`.`t_asistencia` (`numero`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_nota`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_nota` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `codigo_asignatura` INT NOT NULL,
  `numero_ficha` INT NOT NULL,
  `nota1` INT(2) NULL,
  `nota2` INT(2) NULL,
  `nota3` INT(2) NULL,
  `nota4` INT(2) NULL,
  INDEX `fk_aprendiz_modulo_idx` (`numero_ficha` ASC),
  INDEX `fk_nota_asignatura_idx` (`codigo_asignatura` ASC),
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_aprendiz_modulo_nota`
    FOREIGN KEY (`numero_ficha`)
    REFERENCES `sistema_inces`.`td_aprendiz` (`numero_ficha`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_nota_asignatura`
    FOREIGN KEY (`codigo_asignatura`)
    REFERENCES `sistema_inces`.`td_asignatura` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_inces`.`t_documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_inces`.`t_documentos` (
  `numero_doc` INT NOT NULL AUTO_INCREMENT,
  `nacionalidad` CHAR(1) NOT NULL,
  `cedula` VARCHAR(12) NOT NULL,
  `entension` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`numero_doc`),
  INDEX `fk_datos_personales_documentos_idx` (`nacionalidad` ASC, `cedula` ASC),
  CONSTRAINT `fk_datos_personales_documentos`
    FOREIGN KEY (`nacionalidad` , `cedula`)
    REFERENCES `sistema_inces`.`t_datos_personales` (`nacionalidad` , `cedula`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
