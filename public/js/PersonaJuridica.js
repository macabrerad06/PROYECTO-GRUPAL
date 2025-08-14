Ext.define('App.model.PersonaJuridica',{
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'razon_social', type: 'string'},
        {name: 'ruc', type: 'string'},
        {name: 'representante_legal', type: 'string'}

    ]
})

Ext.create('Ext.data.Store', {
    storeId: 'PersonaJuridicaStore',
    model: 'App.model.PersonaJuridica',
    proxy: {
        type: 'rest',
        url: '/api/PersonaJuridica.php',
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


const createPersonaJuridicaPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Personas Jurídicas',
        store: Ext.getStore('PersonaJuridicaStore'),
        itemId: 'PersonaJuridicaGrid',
        layout: 'fit',
        columns: [
            { text: 'ID', width: 80, dataIndex: 'id' },
            { text: 'Razón Social', width: 120, dataIndex: 'razon_social' },
            { text: 'RUC', width: 120, dataIndex: 'ruc' },
            { text: 'Representante Legal', width: 120, dataIndex: 'representante_legal|' }
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
window.createPersonaJuridicaPanel = createPersonaJuridicaPanel;