# Ecommerce Cart Challenge

## Descripción del proyecto
Este proyecto implementa una API para la gestión de un carrito de compras. Se centra en la lógica de creación de órdenes, así como la adición, actualización y eliminación de productos dentro de una orden. Además, incluye la funcionalidad de marcar una orden como pagada. Se complementa con endpoints para listar productos y actualizar la información de una orden existente.

## OpenAPI Specification
La documentación de la API fue generada utilizando NelmioApiDocBundle, que permite definir los endpoints mediante anotaciones OpenAPI (attributes como #[OA\...]) directamente en los controladores.

Puedes acceder a la especificación de la API de las siguientes maneras:

- UI interactiva (Swagger):
    - https://localhost/api/doc

- Documento JSON OpenAPI:
    - https://localhost/api/doc.json

## Modelado del Dominio
El dominio está diseñado bajo los principios de DDD y Arquitectura Hexagonal, incluyendo:

- Entidades
    - **Orden**
        - id: OrdenId (ValueObject)
        - username: UserName (ValueObject)
        - pagado: boolean

    - **OrdenItem**
        - id: int 
        - producto: ProductoId (ValueObject)
        - cantidad: Cantidad (ValueObject)

    - **Product**
        - id: ProductoId (ValueObject)
        - nombre: ProductName (ValueObject)
        - precio: ProductPrice (ValueObject)

- Value Objects: 
    - OrdenId: OrdenIdType (int)
    - ProductoId: ProductIdType (int)
    - ProductName: ProductNameType (varchar(100))
    - ProductPrice: ProductPriceType (float(10, 2))
    - Cantidad: CantidadType (int)
    - Username: UsernameType (varchar(255))

### Otros conceptos aplicados
- DTOs y Assembler para desacoplar la capa de presentación
- CQRS: separación clara entre comandos (Command) y consultas (query)
- Repositorio por dominio, desacoplado de la infraestructura

## Tecnología utilizada

- **Lenguaje:** PHP 8.2+
- **Framework:** Symfony 7.3
- **Base de datos:** POSTGRESQL
- **ORM:** Doctrine
- **Testing:** PHPUnit
- **Arquitectura:** DDD, Hexagonal, CQRS
- **Contenedores:** Docker, Docker Compose

## Instrucciones para levantar el entorno con Docker
Asegúrate de tener instalado Docker y Docker Compose. 
Clona el repositorio. Se incluye el archivo .env para facilitar el despleigue. 
Seguidamente puede ejecutar los siguientes comandos: 

- Build sin cache: docker compose build --pull --no-cache
- Levantar contenedores (modo espera): docker compose up --wait 
- O simplemente levantar normalmente: docker compose up 

Accede a la API a la documentación Swagger a traves del url descrito en **OpenAPI Specification**

## Comando para lanzar los tests
Desde el contenedor, ejecuta:
- docker-compose exec php ./vendor/bin/phpunit

También puedes ejecutar test de una clase en específico, por ejemplo:
- docker-compose exec php ./vendor/bin/phpunit tests/Cart/Infrastructure/Controller/OrdenControllerTest.php

