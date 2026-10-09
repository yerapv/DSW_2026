# Enunciado — Listado de productos

Una tienda de informática necesita una aplicación Laravel para consultar su catálogo de productos.

## Requisitos

Cada producto tendrá:

- SKU; (indice)
- nombre;
- categoría;
- descripción opcional;
- precio;
- stock;
- estado activo/inactivo.

La aplicación deberá:

- mostrar `/productos`;
- buscar por nombre o SKU con `?q=`;
- filtrar por categoría con `?categoria=`;
- ordenar inicialmente por nombre;
- indicar cuando no existen resultados.

## Categorías de ejemplo

- Portátiles
- Monitores
- Periféricos
- Almacenamiento
- Componentes

## Fuera de alcance

No implementar:

- carrito;
- usuarios;
- pedidos;
- pagos;
- administración;
- API REST.
