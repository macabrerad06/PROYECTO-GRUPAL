Ext.define('App.model.Rol',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'nombre', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'RolStore',
    model: 'App.model.Rol',
    proxy: {
        type: 'rest',
        url: '/api/Rol.php',
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


const createRolPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Roles',
        store: Ext.getStore('RolStore'),
        itemId: 'RolGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id' },
            { text: 'Nombre', width: 120, dataIndex: 'nombre' }
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
window.createRolPanel = createRolPanel;