# Documentacion para el correcto uso de la API

## Indice:
* [Propiedades](#propiedades)
  * [GET/propiedades](#getpropiedades)
  * [POST/propiedades](#agregar-propiedad)
  * [DELETE/propiedades](#eliminar-propiedad)
  * [PUT/propiedades](#editar-propiedad)
* [Propietarios](#propietarios)
  * [GET/propietarios](#getpropietarios)
  * [POST/propietarios](#agregar-propietario)
  * [DELETE/propietarios](#eliminar-propietario)
  * [PUT/propietarios](#editar-propietario)

## Propiedades
### __GET/propiedades__
### __Traer todas las propiedades__
Devuelve un arreglo de objetos con TODAS las propiedades.

Ej. GET/propiedades

---
### __Traer todas las propiedades ordenadas por su precio (ASC | DESC)__
Dado un orden (ASC📈 o DESC📉) trae todas las propiedades ordenadas ascendente o descendetemente por su precio.

Ej. GET/propiedades?orden=DESC

---
#### __Traer todas las propiedades donde un atributo sea igual a un valor__
Dado un atributo (columna) y valor (valor del campo de la columna especificada) 
devuelve todas las filas (propiedades) donde la columna tenga el valor indicado  

Ej. GET/propiedades?atributo=tipo&valor=casa

Atributos validos😎 (❗ En caso de solicitar un atributo invalido tirará "error" ❌): 
  * id
  * titulo
  * tipo
  * operacion
  * descripcion
  * precio
  * metros_cuadrados
  * ambientes
  * banios
  * permite_mascotas
  * propietario

Valores que posiblemente traigan algo🤞 (❗ Puede que no haya ninguna propiedad con los requisitos solicitados):
  * tipo: casa, departamento, ph, fondo de comercio, terreno baldio.
  * operacion: alquiler, venta.
  * permite_mascota: 1 (si), 0 (no). 
---
#### __Traer todas las propiedades ordenadas en base a un atributo__
Trae todas las propiedades de forma ordenada (ASC📈 o DESC📉) en base a un atributo (columna) especificado.

Ej. GET/propiedades?atributo=ambientes&orden=DESC

---
### __Agregar propiedad__
Dado un json de una propiedad (titulo, tipo, operacion, descripcion, precio, metros_cuadrados, ambientes, banios, permite_mascotas, propietario) pasado por el body, se agrega la nueva propiedad (el propietario ya debe existir en la BD)

Ej. POST/propiedades
``` json
  "titulo": "titulo por default",
  "tipo": "casa",
  "operacion": "alquiler",
  "descripcion": "lorem ipsum amet generet ap sured",
  "precio": 100000,
  "metros_cuadrados": 100,
  "ambientes": 4,
  "banios": 2,
  "permite_mascotas": 1,
  "propietario": 16345443
```
---
### __Eliminar propiedad__
Dado un id, elimina la propiedad.

Ej. DELETE/propiedades/:ID

---
### __Editar propiedad__
Dado un json de una propiedad (id, titulo, tipo, operacion, descripcion, precio, metros_cuadrados, ambientes, 
banios, permite_mascotas, propietario) edita los datos de la  propiedad (la propiedad y el propietario ya deben existir en la BD)

Ej. PUT/propiedades
``` json
  "id":"41",
  "titulo": "titulo editado",
  "tipo": "ph",
  "operacion": "alquiler",
  "descripcion": "descripcion editada",
  "precio": 300000,
  "metros_cuadrados": 200,
  "ambientes": 5,
  "banios": 1,
  "permite_mascotas": 0,
  "propietario": 30495876
```
---
## Propietarios
### __GET/propietarios__
### __Traer todos los propietarios__
Devuelve un arreglo de objetos con TODOS los propietarios.

Ej. GET/propietarios

---
### __Traer todos los propietarios ordenados por su apellido (ASC | DESC)__
Dado un orden (ASC📈 o DESC📉) trae todos los propietarios ordenados ascendente o descendetemente en base a su apellido.

Ej. GET/propietarios?orden=ASC

---
#### __Traer todos los propietarios donde un atributo sea igual a un valor__
Dado un atributo (columna) y valor (valor del campo de la columna especificada) devuelve todas las filas (propiedades) donde la columna tenga el valor indicado  

Ej. GET/propietarios?atributo=nombre&valor=esteban

Atributos validos😎 (❗ En caso de solicitar un atributo invalido tirará "error" ❌): 
  * dni
  * nombre
  * apellido
  * telefono
  * mail

---
#### __Traer todas los propietarios ordenados en base a un atributo__
Trae todos los propietarios de forma ordenada (ASC📈 o DESC📉) en base a un atributo (columna) especificado.

Ej. GET/propietarios?atributo=telefono&orden=DESC

---
### __Agregar propietario__
Dado un json de un propietario (dni, nombre, apellido, telefono, mail) pasado por el body, se agrega un nuevo propietario

Ej. POST/propietarios
``` json
  "dni": 42333567,
  "nombre": "miguel",
  "apellido": "garcia",
  "telefono": "2262-777656",
  "mail": "garciamiguel@hotmail.com"
```
---
### __Eliminar propietario__
Dado un dni, elimina un propietario (y por consecuente a todas sus propiedades).

Ej. DELETE/propietarios/:ID

---
### __Editar propietario__
Dado un json de un propietario (dni, nombre, apellido, telefono, mail) edita sus datos (ya debe existir algun propietario con dicho dni)

Ej. PUT/propietarios
``` json
  "dni": 42333567,
  "nombre": "jose",
  "apellido": "garcia",
  "telefono": "2262-654870",
  "mail": "eljose@gmail.com"
```
---