Ext.onReady(() => {
    // 1. Instancias de tus paneles
    const categoriaPanel = createCategoriaPanel();
    const detalleVentaPanel = createDetalleVentaPanel();
    const facturaPanel = createFacturaPanel();
    const permisoPanel = createPermisoPanel();
    const personaJuridicaPanel = createPersonaJuridicaPanel();
    const personaNaturalPanel = createPersonaNaturalPanel();
    const productoDigitalPanel = createProductoDigitalPanel();
    const productoFisicoPanel = createProductoFisicoPanel();
    const rolPanel = createRolPanel();
    const rolPermisoPanel = createRolPermisoPanel();
    const usuarioPanel = createUsuarioPanel();
    const ventaPanel = createVentaPanel();

    // 2. Crea el panel principal que usará el layout de tarjeta (card)
    // Este panel contendrá todas tus tablas, pero mostrará solo una a la vez.
    const mainCard = Ext.create('Ext.Panel', {
        id: 'mainCardPanel',
        layout: 'card', // layout 'card' es la clave para la navegación entre tablas
        region: 'center', // Ocupará la región central del Viewport
        items: [
            categoriaPanel,
            detalleVentaPanel,
            facturaPanel,
            permisoPanel,
            personaJuridicaPanel,
            personaNaturalPanel,
            productoDigitalPanel,
            productoFisicoPanel,
            rolPanel,
            rolPermisoPanel,
            usuarioPanel,
            ventaPanel
        ]
    });

    // 3. Configura el Viewport
    Ext.create('Ext.container.Viewport', {
        id: "mainViewport",
        layout: 'border',
        items: [
            {
                // Este es el panel de la barra de herramientas que estará en la parte superior.
                region: 'north',
                xtype: 'toolbar',
                items: [
                    {
                        text: 'Categorías',
                        handler: () => mainCard.getLayout().setActiveItem(categoriaPanel)
                    },
                    {
                        text: 'Detalle de Ventas',
                        handler: () => mainCard.getLayout().setActiveItem(detalleVentaPanel)
                    },
                    {
                        text: 'Facturas',
                        handler: () => mainCard.getLayout().setActiveItem(facturaPanel)
                    },
                    {
                        text: 'Permisos',
                        handler: () => mainCard.getLayout().setActiveItem(permisoPanel)
                    },
                    {
                        text: 'Personas Jurídicas',
                        handler: () => mainCard.getLayout().setActiveItem(personaJuridicaPanel)
                    },
                    {
                        text: 'Personas Naturales',
                        handler: () => mainCard.getLayout().setActiveItem(personaNaturalPanel)
                    },
                    {
                        text: 'Productos Digitales',
                        handler: () => mainCard.getLayout().setActiveItem(productoDigitalPanel)
                    },
                    {
                        text: 'Productos Físicos',
                        handler: () => mainCard.getLayout().setActiveItem(productoFisicoPanel)
                    },
                    {
                        text: 'Roles',
                        handler: () => mainCard.getLayout().setActiveItem(rolPanel)
                    },
                    {
                        text: 'Roles y Permisos',
                        handler: () => mainCard.getLayout().setActiveItem(rolPermisoPanel)
                    },
                    {
                        text: 'Usuarios',
                        handler: () => mainCard.getLayout().setActiveItem(usuarioPanel)
                    },
                    {
                        text: 'Ventas',
                        handler: () => mainCard.getLayout().setActiveItem(ventaPanel)
                    }
                ]
            },
            // El panel principal 'mainCard' se coloca en la región 'center'.
            // Su contenido cambiará según el botón que se presione arriba.
            mainCard
        ]
    });
});