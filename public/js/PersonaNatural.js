Ext.define('App.model.PersonaNatural',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_cliente', type: 'int'},
        {name: 'nombres', type: 'string'},
        {name: 'apellidos', type: 'string'},
        {name: 'cedula', type: 'string'}
    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'PersonaNaturalStore',
    model: 'App.model.PersonaNatural',
    proxy: {
        type: 'rest',
        url: '/api/PersonaNatural.php',
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


const createPersonaNaturalPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Personas Naturales',
        store: Ext.getStore('PersonaNaturalStore'),
        itemId: 'PersonaNaturalGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id_cliente' },
            { text: 'Nombres', width: 120, dataIndex: 'nombres' },
            { text: 'Apellidos', width: 120, dataIndex: 'apellidos' },
            { text: 'Cédula', width: 120, dataIndex: 'cedula' }
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
window.createPersonaNaturalPanel = createPersonaNaturalPanel;