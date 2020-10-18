# phpcfdi/webapp-resources

Aplicación web para exponer el estado de los recursos y sus construcciones con estado.

## Instalación

Clonar en la carpeta adecuada usando el siguiente comandos:

```shell
git clone https://github.com/phpcfdi/webapp-resources.git
```

Nota: Toma en cuenta que el flujo de trabajo del proyecto es por medio de [Git Flow](http://aprendegit.com/que-es-git-flow/) por lo que es necesario inicirlo en tu repositorio local y continuar la configuración en la rama *develop*.

Utiliza composer [composer](https://getcomposer.org/).

Instalar el composer assset plugin:

```shell
composer global require "fxp/composer-asset-plugin:^1.4.1"
```

Ahora puedes instalar las dependencias del proyecto con:

```shell
composer install
```

### Configurar base de datos

Crea una base de datos de MySQL.

Realiza una copia del archivo `.env.example` localizado en la raíz del proyecto con el nombre de `.env` y configura las siguientes variables de acuerdo a tu entorno:

```bash
DATABASE_DSN_BASE = "mysql:host=localhost;port=3306"
DATABASE_DSN_DB = "yii2_app"
DATABASE_USER = "root"
DATABASE_PASSWORD = ""
DATABASE_TABLE_PREFIX = ""
DATABASE_CHARSET = "utf8"
```

Para configurar la base de datos para los test debes de editar las mismas variables pero en el archivo `.env.test`.

### Dominios permitidos en la REST API

Por defecto esta configuración marca `*` como los dominios permitidos para los CORS de la API REST, si se desean cambiar debe de cambiarse desde el archivo `.env` que nosotros acabamos de generar. Cada uno de éstos son separados por una coma, como en el siguiente ejemplo.

```bash
ALLOWED_DOMAINS = "https://www.dominio1.com, https://www.dominio2.com"
```

### Correr migrates

Ahora se pueden correr los migrates de la base de datos para el proyecto.

Una de las cosas a tomar en cuenta es correr las primeras nueve migraciones para levantar las tablas de módulo `2amigos/usuario`.

```shell
./yii migrate 9
```

Lo siguiente es correr los migrates para el RBAC de Yii.

```shell
./yii migrate --migrationPath=@yii/rbac/migrations
```

Ya corridas las migraciones de yii/usuario y RBAC podemos correr las migraciones sobrantes.

```shell
./yii migrate
```

### Crear usuario admin

Antes de usar la aplicación debemos crear nuestro usuario SuperAdmin. El usuario debe de ser *SuperAdmin* ya que [Yii2 Usuario Module](https://github.com/2amigos/yii2-usuario) está configurado para que sea el usuario principal. Es necesario que antes de realizar esto nosotros debamos de añadir credenciales de SMTP que se encuentran dentro del archivo `.env`.

```env
APP_MAIL_HOST         = ""
APP_MAIL_USERNAME     = ""
APP_MAIL_PASS         = ""
APP_MAIL_PORT         = ""
APP_MAIL_ENCRYPTION   = ""
```

Recomendamos el uso de la herramienta de webmail de pruebas [Mailtrap](http://mailtrap.io/).

Crear al usuario principal:

```shell
./yii user/create admin@example.com SuperAdmin mypassword
./yii user/confirm admin@example.com
```

Nota: Puedes cambiar *mypassword* por el que te sea más cómodo.

### Correr la aplicación

En la configuración del script de composer están definidos los comandos necesario así que basta con correr:

```shell
composer start-server
```

Ahora, visita la ruta `http://localhost:8080` en tu navegador.

## 2amigos app template

Este proyecto no usa el típico esquema de Yii2 ya sea basic o advanced, este se basa en la propuesta de 2amigos [Yii 2 Basic Application Template with ConfigKit and Yii 2 Usuario Module](https://github.com/2amigos/yii2-app-usuario-template) usando
[https://github.com/2amigos/yii2-app-template](https://github.com/2amigos/yii2-app-template) + [https://github.com/2amigos/yii2-config-kit](https://github.com/2amigos/yii2-config-kit) + [https://github.com/2amigos/yii2-usuario](https://github.com/2amigos/yii2-usuario). Es una plantilla de proyecto funcional.

Para futuras referencias, usar los siguientes enlaces:

- [Step by Step Installation Process](http://www.2amigos.us/blog/how-to-work-with-yii-2-config-kit-and-yii-2-usuario-module)
- [Yii 2 Application Template](https://github.com/2amigos/yii2-app-template)
- [Yii 2 Config Kit](https://github.com/2amigos/yii2-config-kit)
- [Yii 2 Usuario Module](https://github.com/2amigos/yii2-usuario)

## Aplicaciones del proyecto

Se contemplan cuatro aplicaciones para el proyecto:

1. WEB. La página WEB para usuarios **Invitados** con toda la información de los estados de las construcciones de los recursos. Un usuario registrado pasa a ser **Suscriptor** y puede administrar desde aquí su perfil y recibir notificaciones por correo de los cambios de estado en las construcciones de los recursos. Un **Suscriptor** puede ser elevado a **Suscriptor premium** para recibir también notificaciones por _webhooks_.
2. Admin. El panel de los **Administradores**.
3. API. La API que provee la información que es presentada en la WEB y que puede ser usada desde otras aplicaciones para obtener los estados de las construcciones de los recursos.
4. Console. Aplicación de consola.

## Estructura de carpetas

```shell
admin               [ aplicación Admin ]
api                 [ aplicación API ]
app                 [ código de Yii relacionado a las aplicaciones ]
├── assets          [ contiene las definiciones de los asset's ]
├── commands        [ contiene los comandos de Consola (llamados controllers en Yii) ]
├── components      [ contiene los componentes ]
├── controllers     [ contiene los controllers de cada aplicación ]
├───── admin        [ contiene los controllers de la aplicación Admin ]
├───── web          [ contiene los controllers de la aplicación Web ]
├── migrations      [ contiene los scripts de la base de datos ]
├── models          [ contiene los modelos de la aplicación ]
├── views           [ contiene las vistas de la aplicación ]
├── widgets         [ contiene los widgets ]
bin                 [ contiene scripts ejecutables en línea de comandos ]
bootstrap           [ contiene archivos del bootstrap ]
config              [ contiene archivos de configuración de aplicaciones ]
public_html         [ contiene script de entrada de la aplicación WEB ]
runtime             [ contiene archivos generados durante runtime ]
src                 [ contiene archivos de la lógica de negocio. Código portable, libre de código de Yii. ]
tests               [ contiene codeception tests de las aplicaciones ]
```

## Soporte

Puedes obtener soporte abriendo un ticket en Github.

Adicionalmente, este proyecto pertenece a la comunidad [PhpCfdi](https://www.phpcfdi.com), así que puedes usar los
mismos canales de comunicación para obtener ayuda de algún miembro de la comunidad.

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el [CHANGELOG][].

## Copyright and License

The `phpcfdi/webapp-resources` proyect is copyright © [PhpCfdi](https://www.phpcfdi.com)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.
