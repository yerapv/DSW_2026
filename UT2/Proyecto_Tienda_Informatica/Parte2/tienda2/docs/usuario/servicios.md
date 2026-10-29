# Usuario: envío, montaje y financiación

[Índice de diagramas](../README.md) · **Área Usuario**

Estos cálculos no crean encargos ni financiación real. Los servicios no son vistas ni controladores, y se distinguen de los modelos Eloquent. El nodo M indica que no hay persistencia en estos flujos.

## Tarifas de envío

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /envios"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["EnvioController.index"]
        ret["Preparar tarifa normal y urgente"]
    end
    subgraph S_CAPA["SERVICIOS Y DOMINIO · APOYO A MVC"]
        s["CalculadoraEnvio.calcular: 3 + peso por 0,80; sumar 5 si urgente y redondear"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        none["Sin modelo Eloquent ni consultas a MySQL"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        v["usuario/envios: 2,5 kg dan 5 euros normal y 10 euros urgente"]
    end
    get --> c
    c --> s
    s --> ret
    ret --> v
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,ret claseC;
    classDef claseS fill:#E8F3FF,stroke:#518BC4,color:#213247;
    class s claseS;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class none claseM;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class v claseV;
```

Clase de dominio: `app/Dominio/Envios/CalculadoraEnvio.php`. Se calculan dos casos fijos de 2,5 kg.

## Presupuesto de montaje

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /montaje"]
        post["POST /montaje · CSRF; validar equipos 1 a 100 y opciones booleanas"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["MontajeController.create"]
        calc["MontajeController.calculate"]
        ret["Entregar totalCentimos al resultado"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/montaje/form: equipos, instalación, urgencia y @csrf"]
        view["usuario/montaje/resultado: importe en euros"]
    end
    subgraph S_CAPA["SERVICIOS Y DOMINIO · APOYO A MVC"]
        s["ServicioPrecioMontaje.calcular: tarifas de config/tienda.php; 2500 por equipo, 1000 por instalación y 1500 por urgencia"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        none["Sin modelo Eloquent: el cálculo no se guarda"]
    end
    get --> c
    c --> form
    form --> post
    post --> calc
    calc --> s
    s --> ret
    ret --> view
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,post claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,calc,ret claseC;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,view claseV;
    classDef claseS fill:#E8F3FF,stroke:#518BC4,color:#213247;
    class s claseS;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class none claseM;
```

Los errores de formato vuelven al formulario. La instalación se cobra por equipo y la urgencia una vez por encargo. Dos equipos con instalación y urgencia suman 8500 céntimos, es decir, 85 €.

## Simular financiación

```mermaid
flowchart TD
    subgraph R_CAPA["LARAVEL · RUTAS, VALIDACIÓN HTTP Y MIDDLEWARE"]
        get["GET /financiacion"]
        post["POST /financiacion · CSRF; validar enteros y valores no negativos"]
    end
    subgraph C_CAPA["C · CONTROLADOR · USUARIO"]
        c["FinanciacionController.create"]
        store["FinanciacionController.store: normalizar documentación completa"]
        ret["Preparar valid, errors y warnings"]
    end
    subgraph V_CAPA["V · VISTA BLADE · USUARIO"]
        form["usuario/financiacion/form: edad, importe, antigüedad y documentación"]
        view["usuario/financiacion/resultado: validez, errores y advertencias"]
    end
    subgraph S_CAPA["SERVICIOS Y DOMINIO · APOYO A MVC"]
        s["ValidadorSolicitudFinanciacion.validar"]
        rules["Errores: menor de 18, importe mayor de 3000 o documentación incompleta"]
        warn["Advertencia: antigüedad menor de 6 meses; válida si no hay errores"]
    end
    subgraph M_CAPA["M · MODELO ELOQUENT"]
        none["Sin modelo Eloquent: no se guarda ni se tramita la solicitud"]
    end
    get --> c
    c --> form
    form --> post
    post --> store
    store --> s
    s --> rules
    rules --> warn
    warn --> ret
    ret --> view
    classDef claseR fill:#F1F4F8,stroke:#8797B1,color:#213247;
    class get,post claseR;
    classDef claseC fill:#E8F6EE,stroke:#54A17A,color:#213247;
    class c,store,ret claseC;
    classDef claseV fill:#F4EAFF,stroke:#A579D0,color:#213247;
    class form,view claseV;
    classDef claseS fill:#E8F3FF,stroke:#518BC4,color:#213247;
    class s,rules,warn claseS;
    classDef claseM fill:#FFF0D9,stroke:#D4A053,color:#213247;
    class none claseM;
```

Los límites son reglas ficticias de la actividad. Los errores de formato HTTP y los errores de negocio son comprobaciones distintas; una advertencia por sí sola no invalida la solicitud.

## Archivos para seguir el recorrido

- **Entrada HTTP:** [Rutas web](../../routes/web.php).
- **Controladores:** [EnvioController](../../app/Http/Controllers/EnvioController.php) · [MontajeController](../../app/Http/Controllers/MontajeController.php) · [FinanciacionController](../../app/Http/Controllers/FinanciacionController.php).
- **Vistas Blade:** [usuario/envios](../../resources/views/usuario/envios.blade.php) · [usuario/montaje/form](../../resources/views/usuario/montaje/form.blade.php) · [usuario/montaje/resultado](../../resources/views/usuario/montaje/resultado.blade.php) · [usuario/financiacion/form](../../resources/views/usuario/financiacion/form.blade.php) · [usuario/financiacion/resultado](../../resources/views/usuario/financiacion/resultado.blade.php).
- **Servicios, reglas y configuración:** [Cálculo de envíos](../../app/Dominio/Envios/CalculadoraEnvio.php) · [Precio de montaje (ubicación orientativa)](../../app/Dominio/Montaje/ServicioPrecioMontaje.php) · [Validador de financiación (ubicación orientativa)](../../app/Dominio/Financiacion/ValidadorSolicitudFinanciacion.php) · [Tarifas de montaje](../../config/tienda.php).
- **Layout público:** [Layout de Usuario](../../resources/views/layouts/usuario.blade.php) · [Layout general](../../resources/views/layouts/app.blade.php).
