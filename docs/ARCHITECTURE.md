# Arquitectura

## Objetivo

Recrear el MVP Chef en Casa sobre WordPress y MySQL/MariaDB, conservando una separación clara entre presentación, datos y reglas de negocio.

## Componentes

```text
Navegador
   |
WordPress + tema chef-en-casa
   |
Plugin chef-en-casa (dominio, administración y REST)
   |
MySQL/MariaDB
```

### Tema `chef-en-casa`

Responsable de plantillas, navegación, estilos, scripts, accesibilidad y comportamiento responsive. No debe crear tablas ni contener cálculos nutricionales.

### Plugin `chef-en-casa`

Responsable de tipos de contenido, taxonomías, tablas relacionales, validaciones, cálculos nutricionales, pantallas administrativas y endpoints REST.

### Base de datos

WordPress conservará sus tablas nativas. Las relaciones que incluyen atributos —por ejemplo, cantidad y unidad de un ingrediente dentro de un plato— usarán tablas propias creadas por el plugin.

## Entorno local

Docker Compose levanta WordPress, MySQL y Adminer. Los volúmenes mantienen la base y el core; solo el tema y el plugin propios se versionan.

## Producción

El hosting ejecutará WordPress con MySQL/MariaDB. Tema y plugin se desplegarán como paquetes independientes. La base se migrará mediante una exportación controlada cuando corresponda.

