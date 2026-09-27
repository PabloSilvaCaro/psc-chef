# Identidad visual — Chef en Casa

## Concepto: sabor en movimiento

La identidad expresa energía, frescura y cercanía. El isotipo abandona los símbolos literales de casa y chef para construir una marca más distintiva:

- la **C** circular recuerda un plato visto desde arriba;
- la hoja turquesa expresa frescura y movimiento;
- el punto mango funciona como ingrediente, sol y firma visual.

El resultado debe sentirse contemporáneo, alegre y profesional, sin parecer una clínica nutricional ni un restaurante formal.

## Logotipo

- `assets/brand/logo-horizontal.svg`: versión principal para fondos claros.
- `assets/brand/logo-horizontal-reversed.svg`: versión para berenjena y fondos oscuros.
- `assets/brand/mark.svg`: avatar, sello y espacios compactos.
- `assets/brand/favicon.svg`: navegador y accesos directos.

No deformar, rotar, encerrar en otra forma ni alterar la relación entre coral, turquesa y mango. Mantener un área libre equivalente al diámetro del punto mango.

## Paleta viva

| Rol | Nombre | Hex | Uso |
|---|---|---:|---|
| Base premium | Berenjena | `#35205B` | Texto, navegación y fondos de contraste |
| Energía | Coral | `#FF5548` | Marca, botones y llamadas principales |
| Optimismo | Mango | `#FFC53D` | Destacados, iconos y microinteracciones |
| Frescura | Turquesa | `#00B89C` | Badges, categorías y detalles naturales |
| Fondo cálido | Crema | `#FFF8EF` | Secciones y tarjetas |
| Texto | Tinta ciruela | `#241B32` | Lectura principal |

Los colores vivos se usan con jerarquía: coral para acción, mango para énfasis y turquesa para frescura.

## Tipografía

- **Bricolage Grotesque**: logo, títulos y mensajes de alto impacto; pesos 600–800.
- **Plus Jakarta Sans**: navegación, texto, formularios y datos; pesos 400–700.
- Alternativas: Arial Black para display y Segoe UI/Arial para interfaz.

## Accesibilidad

- Berenjena o tinta sobre crema para párrafos.
- Blanco sobre berenjena para secciones oscuras.
- Coral, mango y turquesa no se usan como texto pequeño sobre fondos claros.
- Foco visible con mango y contorno berenjena.

## Implementación

La Capa 3 debe consumir `assets/css/design-tokens.css`. No duplicar colores o familias tipográficas sin justificarlo.
