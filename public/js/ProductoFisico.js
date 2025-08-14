Ext.define('App.model.ProductoFisico',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_producto', type: 'int'},
        {name: 'nombre', type: 'string'},
        {name: 'peso', type: 'float'},
        {name: 'alto', type: 'float'},
        {name: 'ancho', type: 'float'},
        {name: 'profundidad', type: 'float'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'ProductoFisicoStore',
    model: 'App.model.ProductoFisico',
    proxy: {
        type: 'rest',
        url: '/api/ProductoFisico.php',
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


const createProductoFisicoPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Productos Físicos',
        store: Ext.getStore('ProductoFisicoStore'),
        itemId: 'ProductoFisicoGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id_producto' },
            { text: 'Nombre', width: 200, dataIndex: 'nombre' },
            { text: 'Peso', width: 120, dataIndex: 'peso' },
            { text: 'Alto', width: 120, dataIndex: 'alto' },
            { text: 'Ancho', width: 120, dataIndex: 'ancho' },
            { text: 'Profundidad', width: 120, dataIndex: 'profundidad' }
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
window.createProductoFisicoPanel = createProductoFisicoPanel;