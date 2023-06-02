# Prueba técnica
Hola que tal, gracias por el interes en mi perfil, a continuacion les redacto las especificaciones del proyecto, instrucciones de instalacion, configuracion inicial y patrones de diseño.

## Especificaciones
El proyecto esta escrito con Laravel 8, usa docker para la construccion de la infraestructura de desarrollo, se crean 3 contenedores para su correcto funcionamiento, los cuales son:

- nginx: construido a partir de la ultima imagen estable de nginx, expone el puerto 80 para la escucha de peticiones, usa un fichero de configuracion personalizado para la comunicacion entre el servidor http y la imagen de php
- backend: construido a partir de un Dockerfile personalizado, el cual implementa la imagen `php:7.4.6-fpm`, instala todas las dependencias necesarias para el funcionamiento de Laravel e instala composer, este contenedor puede ser usado para usar artisan de laravel
- database: construido a partir de la imagen `mariadb:10.7.8`, expone el puerto 3306 y realiza las configuraciones de conexion con el servidor ademas de crear la base de datos que usará Laravel

Los contenedores se construyen a partir de un documento `docker-compose.yml` ubicado en la raíz del proyecto, implementa la version 3.8, el fichero crea e implementa una red de comunicacion interna llamada `prueba-network`.

El proyecto cuenta con un stack sencillo de test unitarios que verifica el correcto manejo de exepciones y casos de error mas comunes para el servicio `ProductService`.

## Instalación
Para poder ejecutar el proyecto es necesario tener instalado los sistemas `docker 24.0.2` y `docker compose 2.3.3` aunque puede existir compatibilidad con versiones superiores(leer documentacion).

El sistema operativo en el que se desarrolló es `Ubuntu 20.04`

- [docker](https://docs.docker.com/)
- [docker compose](https://docs.docker.com/compose/)
- [ubuntu](https://releases.ubuntu.com/focal/)

Para la instalación y ejecución de los contenedores se escribio un fichero `Makefile`, el cual contiene comandos utiles para automatizar la construcción y ejecución de los contenedores

```bash
$ make run
```
Ejecuta y construye los contenedores por primera vez, verifica si la red interna existe y de no ser asi crea una nueva

```bash
$ make stop
```
Detiene los contenedores

```bash
$ make restart
```
Detiene y vuelve a ejecutar los contenedores, es util para probar e implementar nuevas funcionalidades que impliquen la modificacion de variables de entorno

```bash
$ make ssh-be
```
Abre una conexion con bash del container `backend` en donde se ejecuta laravel, es util para la instalacion de dependencias con composer y la interaccion con artisan de laravel

Para hacer uso de este fichero es necesario tener instalado el paquete make en el sistema operativo del anfitrion, este puede ser instalado por medio de los manejadores de paquetes de cada sistema:

- `choco`: para sistemas operativos windows
- `brew`: para sistemas operativos macos
- `apt`: para sistemas operativos linux basados en debian
- `pacman`: para sistemas operativos linux basados en arch

~~~
Nota: si usas un sistema con algun otro gestor de paquetes revisa la documentacion de tu sistema
~~~

## Configuracion inicial
Para que el proyecto pueda funcionar correctamente despues de la instalación es necesario ejecutar algunos comandos sencillos para levantar la base de datos e insertar informacion de pruebas en la misma. Para ingresar al container `backend` nos apoyaremos del `Makefile` haciendo uso del comando:

```bash
$ make ssh-be
```

### Variables de entorno
Para que Laravel se ejecute correctamente debes crear un fichero `.env` en la raiz del proyecto (puedes copiar el contenido del fichero `.env.example`) y generar una clave unica para laravel, entra al container `backend` y ejecuta el comando:

```bash
$ php artisan key:generate
```

Agrega los valores de conexion con la base de datos que se encuentran en el fichero `docker-compose.yml` en las variables correspondientes del fichero `.env`. Para la variable `DB_HOST` usa el nombre del container `database`

### Migraciones
Una vez configuradas las variables de entorno, dentro del contenedor `backend` ejecuta los siguientes comandos de laravel:

```bash
$ php artisan migrate
```
Esto construirá los modelos de la base de datos

```bash
$ php artisan db:seed
```
Para poblar la base de datos con información de pruebas

```bash
$ chmod -R 777 storage

$ chmod -R 777 bootstrap/cache
```
Esto le dará a laravel permisos de escritura necesarios en las carpetas del `storage` y `bootstrap/cache` para el almacenamiento de logs, ficheros y caché.

### Tests
Para ejecutar el stack de test debes ingresar al contenedor `backend` y ejecutar el comando:

```bash
$ php artisan test
```
Esto ejecutara la integración de laravel con `phpUnit`.

## Patrones de diseño y reglas de negocios
El proyecto se apega a un patron de diseño princila `MVC` para respetar la arquitectura `REST`. Adicional a esto hago uso de las propiedades de inyeccion de dependencias de laravel para hacer uso de los patrones de diseño `Repositorie` y `Service`

El proyecto contiene un controllador llamado `ProductsController` el cual inyecta el servicio `ProductService` el cual es el que contiene las reglas de negocios y el conjunto de funciones necesarias para la aplicación, desarrollé dos funciones personalizadas para manejar las respuestas exitosas, respuesta simple y respuesta con data, las cuales se encuentran en el `Controller` principal de Laravel para así poder reutilizarlas en caso de escalar funcionalidades con otras entidades.

Para la implementación de los servicios cree una carpeta llamada `Services` la cual se encuentra dentro de la capreta `app` de laravel, son mapeados por el `namespace` `App\Services`, los servicios inyectan las clases `Repositorie` que sean necesarias para su funcionamiento, en este caso solo existe el servicio `ProductService` el cual inyecta `ProductRepo`, responsable de la interacción con la base de datos del modelo `Product`.

Los repositorios implementan abstracciones con `Eloquent` y `EloquentFilter` de los modelos para extender y estandarizar funcionalidades comunes, esto pensando en la posible escalabilidad del proyecto. Cada repositorio inyecta una entidad o modelo de `Eloquent` con la cual va a interactuar con la base de datos.

Para la aplicación de los filtros se implementa en cada modelo la dependencia `EloquentFilter`, con la cual se pueden personalizar filtros de busqueda.

By: Erick Damian Gonzalez Aranda