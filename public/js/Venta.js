Ext.define('App.model.Venta',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_venta', type: 'int'},
        {name: 'fecha', type: 'date', dateFormat: 'Y-m-d'},
        {name: 'id_cliente', type: 'int'},
        {name: 'total', type: 'float'},
        {name: 'estado', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'VentaStore',
    model: 'App.model.Venta',
    proxy: {
        type: 'rest',
        url: '/api/Venta.php',
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

const createVentaPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Ventas',
        store: Ext.getStore('VentaStore'),
        itemId: 'VentaGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id_venta' },
            { text: 'Fecha', width: 120, dataIndex: 'fecha', xtype: 'datecolumn', format: 'Y-m-d' },
            { text: 'ID Cliente', width: 100, dataIndex: 'id_cliente' },
            { text: 'Total', width: 80, dataIndex: 'total', xtype: 'numbercolumn', format: '0.00' },
            { text: 'Estado', width: 120, dataIndex: 'estado' }
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
window.createVentaPanel = createVentaPanel;