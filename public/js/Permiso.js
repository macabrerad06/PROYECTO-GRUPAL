Ext.define('App.model.Permiso',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'codigo', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'PermisoStore',
    model: 'App.model.Permiso',
    proxy: {
        type: 'rest',
        url: '/api/Permiso.php',
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


const createPermisoPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Permisos',
        store: Ext.getStore('PermisoStore'),
        itemId: 'PermisoGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id' },
            { text: 'Código', width: 120, dataIndex: 'codigo' }
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
window.createPermisoPanel = createPermisoPanel;