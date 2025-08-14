Ext.define('App.model.Factura',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'idVenta', type: 'int'},
        {name: 'numero', type: 'string'},
        {name: 'claveAcceso', type: 'string'},
        {name: 'fechaEmision', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'estado', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'FacturaStore',
    model: 'App.model.Factura',
    proxy: {
        type: 'rest',
        url: '/api/Factura.php',
        reader: {
            type: 'json',
            rootProperty: 'data'
        },
        writer:{
            type: 'json',
            rootProperty: 'data',
            writeAllFields: true
        },
        appendId: false
    },
    autoLoad: true,
    autoSync: false
});


const createFacturaPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Facturas',
        store: Ext.getStore('FacturaStore'),
        itemId: 'FacturaGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id' },
            { text: 'ID Venta', width: 80, dataIndex: 'idVenta' },
            { text: 'Número', width: 120, dataIndex: 'numero' },
            { text: 'Clave de Acceso', width: 150, dataIndex: 'claveAcceso' },
            { text: 'Fecha de Emisión', width: 120, dataIndex: 'fechaEmision' },
            { text: 'Estado', width: 100, dataIndex: 'estado' }
        ],
        tbar: [
            {
                text: 'Agregar',
                handler: () => {
                    // Lógica para agregar un nuevo detalle de venta
                }
            },
            {
                text: 'Editar',
                handler: () => {
                    // Lógica para editar el detalle de venta seleccionado
                }
            },
            {
                text: 'Eliminar',
                handler: () => {
                    // Lógica para eliminar el detalle de venta seleccionado
                }
            }
        ]
    });
}

// Exportas la función para que 'app.js' pueda usarla.
window.createFacturaPanel = createFacturaPanel;