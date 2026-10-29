# Usuario: elegir cliente y abrir Tu Espacio

[Índice de diagramas](../README.md) · **Área Usuario**

`UsuarioController.index` atiende GET `/usuario`. Sin selección muestra clientes disponibles; con `?cliente_id=...` muestra el espacio de ese cliente. Elegir cliente no equivale a iniciar sesión.

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        r["GET /usuario con cliente_id opcional"]
        val["Validar cliente_id: entero y existente"]
        err["Dato no válido: volver con errores"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["UsuarioController.index"]
        choose{"¿Hay cliente seleccionado?"}
        ret["Preparar datos y resumen del espacio"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        all["Cliente: lista ordenada por nombre"]
        one["Cliente: withCount de presupuestos y pedidos; cargar carrito.lineas"]
    end
    subgraph D_CAPA["INFRAESTRUCTURA · MYSQL Y ARCHIVOS"]
        db[("MySQL: clientes, carritos, lineas_carrito, presupuestos y pedidos")]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        list["usuario/index: selector y tabla de clientes"]
        space["usuario/index: datos, unidades, presupuestos, pedidos y servicios"]
        links["Enlaces con cliente_id a ficha, catálogo, carrito e histórico"]
    end
    r --> val
    val -->|Error| err
    val -->|Correcto| c
    c --> all
    all --> db
    c --> choose
    choose -->|No| list
    choose -->|Sí| one
    one --> db
    db --> ret
    ret --> space
    space --> links
    list -->|Abrir un espacio: nueva petición GET| r
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class r,val,err claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,choose,ret claseC;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class all,one claseM;
    classDef claseD fill:#F1F2F4,stroke:#8E99AA,color:#213247;
    class db claseD;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class list,space,links claseV;
```

La selección viaja en la URL y en los formularios, sin una sesión o cookie de selección. La página principal de Usuario no muestra Volver. Las páginas interiores sí lo mantienen. `layouts/app` aporta Usuario, Admin y Carrito; `layouts/usuario` aporta la navegación pública; `usuario/_menu-espacio` reúne los accesos del espacio.
