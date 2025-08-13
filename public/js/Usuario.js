Ext.define('App.model.Usuario',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'username', type: 'string'},
        {name: 'password_hash', type: 'string'},
        {name: 'estado', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'UsuarioStore',
    model: 'App.model.Usuario',
    proxy: {
        type: 'rest',
        url: '/api/Usuario.php',
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

const createUsuarioPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Usuarios',
        store: Ext.getStore('UsuarioStore'),
        itemId: 'UsuarioGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id' },
            { text: 'Username', width: 120, dataIndex: 'username' },
            { text: 'Password', width: 120, dataIndex: 'password_hash' },
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
window.createUsuarioPanel = createUsuarioPanel;