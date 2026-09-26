# Despliegue al hosting

Este documento define el procedimiento previsto; no autoriza ni ejecuta cambios en producción.

## Datos que se requerirán

- proveedor y panel del hosting (cPanel, Plesk u otro);
- dominio y acceso DNS;
- versión de PHP disponible;
- servidor MySQL/MariaDB y credenciales;
- acceso SFTP/SSH o administrador de archivos;
- límites de subida y memoria;
- política de SSL y copias de seguridad.

## Estrategia prevista

1. Validar el sitio completo localmente.
2. Crear respaldo del hosting si ya contiene datos.
3. Instalar WordPress limpio en producción.
4. Empaquetar e instalar el tema y el plugin propios.
5. Exportar/importar contenido y tablas del plugin.
6. reemplazar URLs del entorno local de forma segura.
7. verificar enlaces permanentes, SSL, formularios, imágenes y permisos.
8. conservar un plan de reversión.

El despliegue se realizará únicamente cuando el usuario entregue los datos del hosting y autorice expresamente la publicación.

