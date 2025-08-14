Ext.define('App.model.DetalleVenta',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_venta', type: 'int'},
        {name: 'line_number', type: 'int'},
        {name: 'id_producto', type: 'int'},
        {name: 'cantidad', type: 'int'},
        {name: 'precio_unitario', type: 'float'},
        {name: 'subtotal', type: 'float'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'DetalleVentaStore',
    model: 'App.model.DetalleVenta',
    proxy: {
        type: 'rest',
        url: '/api/DetalleVenta.php', 
        reader: {
            type: 'json',
            rootProperty: 'data'
        },
        writer:{
            type: 'json',
            rootProperty: '',
            writeAllFields: true
        },
        appendId: false
    },
    autoLoad: true,
    autoSync: false
});


const createDetalleVentaPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Detalle de Ventas',
        store: Ext.getStore('DetalleVentaStore'),
        itemId: 'DetalleVentaGrid',
        layout: 'fit',
        columns: [
            { text: 'ID Venta', width: 80, dataIndex: 'id_venta' },
            { text: 'Número de Línea', width: 120, dataIndex: 'line_number' },
            { text: 'ID Producto', width: 100, dataIndex: 'id_producto' },
            { text: 'Cantidad', width: 80, dataIndex: 'cantidad' },
            { text: 'Precio Unitario', width: 120, dataIndex: 'precio_unitario' },
            { text: 'Subtotal', width: 120, dataIndex: 'subtotal' }
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
window.createDetalleVentaPanel = createDetalleVentaPanel;