# Usuario: inicio, novedades, categorías, avisos y escaparate

[Índice de diagramas](../README.md) · **Área Usuario**

Estas páginas son de lectura. Los controles para crear, editar o borrar avisos, fotografías y productos están en Admin.

## Contenidos persistentes

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /categorias, /avisos o /imagenes"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        cat["CategoriaController.index"]
        avis["TiendaController.avisos"]
        img["TiendaController.imagenes"]
        ret["Entregar los datos a la vista correspondiente"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        mc["Categoria: with productos y ordenar por nombre"]
        ma["Aviso: latest y get"]
        mi["Imagen: latest y get; tabla escaparate_imagenes"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: categorias, productos, avisos y escaparate_imagenes")]
        file["Storage public: fotografías y URLs"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        vc["usuario/categorias/index: categorías y enlaces a productos"]
        va["usuario/avisos/index: títulos, contenido y fecha"]
        vi["usuario/imagenes/index: fotografía, nombre y autor"]
    end
    r -->|Categorías| cat
    cat --> mc
    r -->|Avisos| avis
    avis --> ma
    r -->|Escaparate| img
    img --> mi
    mc --> db
    ma --> db
    mi --> db
    db --> ret
    ret --> vc
    ret --> va
    ret --> vi
    file -.-> vi
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class cat,avis,img,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class mc,ma,mi claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db,file claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class vc,va,vi claseV;
```

## Presentación sin consultas a modelos

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET / o /novedades?tipo=..."]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        home["BienvenidaController.index: preparar presentación"]
        news["NovedadController.index: array de cuatro novedades y filtro por texto"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        none["Sin modelos Eloquent: estas dos páginas no consultan MySQL"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        vh["usuario/bienvenida: presentación y accesos a categorías y montaje"]
        vn["usuario/novedades: filtro conservado, resultados o mensaje vacío"]
    end
    r -->|Inicio| home
    home --> vh
    r -->|Novedades| news
    news --> vn
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class home,news claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class none claseM;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class vh,vn claseV;
```

En novedades, `tipo` filtra un array definido en el controlador con textos como `periferico`. Es distinto del catálogo persistente, donde `TipoProducto` usa códigos enteros. El nodo de Modelo indica expresamente que no hay persistencia en este flujo.
