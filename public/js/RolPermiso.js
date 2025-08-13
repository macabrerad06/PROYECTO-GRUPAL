Ext.define('App.model.RolPermiso',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_rol', type: 'int'},
        {name: 'id_permiso', type: 'int'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'RolPermisoStore',
    model: 'App.model.RolPermiso',
    proxy: {
        type: 'rest',
        url: '/api/RolPermiso.php',
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


const createRolPermisoPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Roles y Permisos',
        store: Ext.getStore('RolPermisoStore'),
        itemId: 'RolPermisoGrid',
        layout: 'fit',
        columns: [
            { text: 'ID Rol', width: 80, dataIndex: 'id_rol' },
            { text: 'ID Permiso', width: 120, dataIndex: 'id_permiso' }
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
window.createRolPermisoPanel = createRolPermisoPanel;