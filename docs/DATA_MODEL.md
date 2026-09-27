# Modelo de datos — Capa 4

## Tablas

El prefijo real depende de la instalación de WordPress (`wp_` por defecto).

### `wp_chef_food_types`

Tipos de alimentación configurables: nombre, slug, descripción, estado y auditoría básica.

### `wp_chef_ingredients`

Materias primas con unidad y valores aproximados por 100 g: calorías, proteínas, carbohidratos, grasas y fibra.

### `wp_chef_ingredient_food_type`

Relación muchos-a-muchos normalizada. Su clave primaria compuesta impide asociaciones duplicadas.

```text
chef_food_types 1 ──< chef_ingredient_food_type >── 1 chef_ingredients
```

No se agregan claves foráneas físicas para conservar compatibilidad con los mecanismos de actualización de tablas de WordPress; la integridad se administra desde el plugin.

## Migraciones

`CHEF_EN_CASA_DB_VERSION` controla la versión. Al activar o actualizar el plugin, `dbDelta()` crea o adapta tablas. La carga inicial busca registros por slug e inserta solo los faltantes.

## Datos iniciales

- 3 tipos de alimentación.
- 15 ingredientes.
- Relaciones entre ingredientes y tipos.

Los nutrientes son aproximados y demostrativos. No constituyen diagnóstico ni recomendación médica.

## API de lectura

- `GET /wp-json/chef-en-casa/v1/food-types`
- `GET /wp-json/chef-en-casa/v1/ingredients`
- `GET /wp-json/chef-en-casa/v1/ingredients?food_type=vegana`

Las operaciones de escritura se incorporarán junto con permisos, nonces y administración en una capa posterior.

