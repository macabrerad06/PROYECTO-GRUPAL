Ext.define('App.model.ProductoDigital',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_producto', type: 'int'},
        {name: 'url_descarga', type: 'string'},
        {name: 'licencia', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'ProductoDigitalStore',
    model: 'App.model.ProductoDigital',
    proxy: {
        type: 'rest',
        url: '/api/ProductoDigital.php',
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


const createProductoDigitalPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Productos Digitales',
        store: Ext.getStore('ProductoDigitalStore'),
        itemId: 'ProductoDigitalGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id_producto' },
            { text: 'URL de Descarga', width: 120, dataIndex: 'url_descarga' },
            { text: 'Licencia', width: 120, dataIndex: 'licencia' }
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
window.createProductoDigitalPanel = createProductoDigitalPanel;