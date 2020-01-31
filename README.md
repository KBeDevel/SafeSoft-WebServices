# SafeSoft WebServices

[![CodeFactor](https://www.codefactor.io/repository/github/kbedeveloper/safesoft-webservices/badge)](https://www.codefactor.io/repository/github/kbedeveloper/safesoft-webservices) [![CircleCI](https://circleci.com/gh/circleci/circleci-docs.svg?style=svg)](https://circleci.com/gh/KBeDeveloper/SafeSoft-WebServices)


Repositorio para capa de servicios web de SafeSoft

## Links de proyecto

[SafeSoft Desktop](https://github.com/KBeDeveloper/SafeSoft-Desktop)

[SafeSoft WebApp](https://github.com/KBeDeveloper/SafeSoft-WebApp)

[SafeSoft WebServices](https://github.com/KBeDeveloper/SafeSoft-WebServices)

## Equipo

[Nicolás Álvarez (GearlessCrow)](https://github.com/GearlessCrow)

[Esteban Arriagada (zirux20)](https://github.com/zirux20)

[Benjamín Calisto (KBeDeveloper)](https://github.com/KBeDeveloper)

## Dependencias

Revisa las dependencias de **Composer** en [composer.json](https://github.com/KBeDeveloper/SafeSoft-WebServices/blob/master/composer.json) y de **Node** en [package.json](https://github.com/KBeDeveloper/SafeSoft-WebServices/blob/master/packge.json)

## Metadata

- [Apache 2](https://httpd.apache.org/download.cgi) >= 2.4.41 *(Imprescindible para levantar instancia de servidor)*
- [PHP](https://www.php.net/downloads.php) >= 7.3.10 *(Necesario para levantar servicio)*
- [MySQL](https://dev.mysql.com/downloads/mysql/) >= 8.0.18 *(Necesario para levantar instancia de base de datos)*
- [OpenSSL](https://www.openssl.org/source/) >= 1.1.1 *(Necesario para instancia de servidor de PHP)*
- [Composer](https://getcomposer.org/download/) >= 1.9.0 *(Opcional. Útil para mantención de librerías y dependencias de PHP)*
- [Python](https://www.python.org/downloads/) >= 3.7.4 *(Opcional. Útil para ejecución de script)*
- [Ruby](https://www.ruby-lang.org/en/downloads/) >= 2.6.5 *(Opcional. Útil para ejecución de script)*
- [Node](https://nodejs.org/en/download/) >= 10.16.3 *(Opcional. Útil para ejecución de script)*

**NO** se recomienda el uso de XAMPP

## Despliegue (Linux, MacOS y BSD)

### Levantar aplicación WS

Una vez en el directorio del repositorio actual:

```shell
$ cd api
$ sudo php -S localhost:80
```

La configuración <code>localhost:80</code> puede ser cambiada, de tener tráfico SSL válido (certificado disponible), al puerto *443*, ejecutándose como:

```shell
$ sudo php -S localhost:443
```

Se pueden ocupar un puerto no reservado con el comando (bash):

```shell
$ php -S localhost:{port}
```

Siendo <code>{port}</code> el valor de **cualquier** puerto disponible en el servidor. Por ejemplo: <code>$ php -S localhost:8000</code>

### Levantar instancia MySQL (necesaria para consultas y almacenaje de datos)

```shell
$ sudo systemctl start mysqld
```

O, en su defecto:

```shell
$ sudo service mysqld start
```

## Despliegue (Windows NT, Core, IoT, ARM)

### Requerimientoss mínimos

- IIS habilitado y activado
- PHP instalado
- Paquetes de despliegue de servidor IIS de Visual Studio 2017 o superior

### Descarga

```powershell
> git clone https://github.com/KBeDeveloper/SafeSoft-WebServices
```

### Ejecución

En el directorio del repositorio actual, abrir una ventana de PowerShell, como administrador, o el cliente CLI de PHP y ejecutar:

```powershell
> php -S localhost:8000
```

O, en su defecto:

```powershell
> php -S localhost:8000 -t public
```

#### IMPORTANTE:

Si no tienes añadido PHP al PATH del sistema, debes ejecutarlo de la siguiente forma (desde el drectorio del proyecto):

```powershell
> cd SafeSoft\-WebServices/api
> c:/path/to/php.exe -S localhost:8000
```
