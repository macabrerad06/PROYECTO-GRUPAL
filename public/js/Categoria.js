/**
 * Ext.define define un modelo de datos.
 * Es como un plano para los objetos (registros) que manejará tu aplicación.
 * Aquí defines la estructura de tus datos, los campos que tienen y su tipo.
 * El nombre del modelo debe ser único y seguir una convención,
 * por ejemplo, 'App.model.NombreDeTuEntidad'.
 */
Ext.define('App.model.Categoria', {
    extend: 'Ext.data.Model',
    fields: [
        // 'id' es un campo común para la clave primaria. El tipo 'int' es para números enteros.
        {name: 'id_categoria', type: 'int'},

        // Aquí van los campos específicos de tu entidad.
        // Por ejemplo, para un proyecto, podrías tener 'nombre' y 'descripcion'.
        // Define el tipo de dato para cada campo (string, date, int, etc.).
        {name: 'nombre', type: 'string'},
        {name: 'descripcion', type: 'string'},
        {name: 'estado', type: 'bool'},
        {name: 'id_padre', type: 'int'},

        // Si tienes una fecha, es importante definir el tipo 'date' y el formato
        // en el que la recibirás del servidor ('Y-m-d' es para 'YYYY-MM-DD').
        // {name: 'fechaInicio', type: 'date', dateFormat: 'Y-m-d'},

        // Si necesitas un campo que se calcule a partir de otros, puedes usar 'convert'.
        // Por ejemplo, un 'estado' que dependa de la 'fechaFin'.
        // {
        //     name: 'estado',
        //     convert: (v, rec) => {
        //         //rec.get('fechaFin') te da acceso a otros campos del registro.
        //         const fechaFin = rec.get('fechaFin');
        //         return fechaFin ? 'Completado' : 'Pendiente';
        //     }
        // },

        // Si tu entidad tiene una relación con otra (como un artículo con un autor),
        // puedes usar 'mapping' para indicar de dónde viene el dato.
        // Aquí se asume que el servidor envía un objeto anidado 'relatedEntity'.
        // {name: 'relatedEntityId', mapping: 'relatedEntity.id', type: 'int'},
        // También puedes usar 'convert' para mostrar un nombre de esa entidad relacionada.
        // {
        //     name: 'relatedEntityName',
        //     convert: (v, rec) => {
        //         const related = rec.get('relatedEntity');
        //         return related ? related.name : '';
        //     }
        // }
    ]
});

/**
 * Ext.create crea una instancia de un objeto, en este caso, un Store.
 * Un Store es como una caché de datos en el cliente.
 * Se encarga de cargar, actualizar, y sincronizar los datos con el servidor.
 */
Ext.create('Ext.data.Store', {
    // storeId es un identificador único que te permite acceder a este Store desde cualquier lugar de tu app.
    storeId: 'CategoriaStore',
    // Le indicas al Store qué modelo de datos debe usar.
    model: 'App.model.Categoria',
    // El 'proxy' define cómo el Store se comunica con el backend.
    proxy: {
        // 'type: 'rest'' es para APIs que usan el estilo REST.
        type: 'rest',
        // La URL de tu endpoint en el servidor.
        url: 'api/Categoria.php',
        reader: {
            // 'type: 'json'' indica que los datos del servidor vienen en formato JSON.
            type: 'json',
            // 'rootProperty: '' ' significa que los datos están en la raíz del JSON, no anidados en un objeto.
            rootProperty: 'data'
        },
        writer: {
            type: 'json',
            rootProperty: 'data',
            // 'writeAllFields: true' envía todos los campos al servidor cuando guardas.
            writeAllFields: true
        },
        // 'appendId: false' evita que el proxy añada el ID al final de la URL en las peticiones (por ejemplo, /api/tu_entidad.php/123).
        appendId: false
    },
    // 'autoLoad: true' carga los datos del servidor en cuanto se crea el Store.
    autoLoad: true,
    // 'autoSync: false' significa que los cambios se guardan solo cuando tú lo indicas,
    // no de forma automática cada vez que modificas un registro.
    autoSync: false
});

/**
 * Aquí creas una función que devuelve un componente de la interfaz de usuario,
 * en este caso, un grid (una tabla) para mostrar los datos de tu entidad.
 * Esta función será llamada desde tu archivo 'app.js'.
 */
const createCategoriaPanel = () => {
    return Ext.create('Ext.grid.Panel', {
        title: 'Categorías',
        // Enlazas este grid con el Store que creaste antes.
        store: Ext.getStore('CategoriaStore'),
        // Un identificador para el componente de grid.
        itemId: 'CategoriaGrid',
        layout: 'fit',
        // Las columnas de la tabla.
        columns: [
            // Cada objeto en este array es una columna.
            // 'text' es el título de la columna.
            // 'width' o 'flex' definen el tamaño. 'flex: 1' lo hace flexible.
            // 'dataIndex' es el nombre del campo en tu modelo del que se tomará el dato.
            { text: 'ID', width: 40, dataIndex: 'id_categoria' },
            { text: 'Nombre', flex: 1, dataIndex: 'nombre' },
            { text: 'Descripción', flex: 1, dataIndex: 'descripcion' },
            { text: 'Estado', width: 80, dataIndex: 'estado', xtype: 'booleancolumn', trueText: 'Activo', falseText: 'Inactivo' },
            { text: 'ID Padre', width: 80, dataIndex: 'id_padre' },
            // Para las fechas, usa 'xtype: 'datecolumn'' para que se muestren correctamente.
            /*{
                text: 'Fecha Inicio',
                flex: 1,
                dataIndex: 'fechaInicio',
                xtype: 'datecolumn',
                format: 'Y-m-d'
            },
            */
            // Aquí puedes agregar más columnas según los campos de tu modelo.
        ],
        tbar: [
            // Botones para las acciones CRUD (Crear, Leer, Actualizar, Borrar).
            {
                text: 'Agregar',
                handler: () => {
                    // Aquí iría la lógica para abrir una ventana de formulario
                    // para añadir un nuevo registro.
                    // Por ejemplo, podrías abrir un formulario modal.
                    Ext.Msg.alert('Agregar', 'Aquí iría la lógica para agregar una nueva categoría.');
                    console.log('Botón Agregar clicado');
                }
            },
            {
                text: 'Actualizar',
                handler: function() {
                    // Aquí iría la lógica para obtener el registro seleccionado
                    // y abrir una ventana de formulario para editarlo.
                    const grid = this.up('grid');
                    const selectedRecord = grid.getSelectionModel().getSelection()[0];
                    if (selectedRecord) {
                        console.log('Botón Update clicado, registro:', selectedRecord);
                    } else {
                        Ext.Msg.alert('Selección', 'Por favor, selecciona un registro para actualizar.');
                    }
                }
            },
            {
                text: 'Eliminar',
                handler: function() {
                    // Aquí iría la lógica para eliminar el registro seleccionado.
                    const grid = this.up('grid');
                    const rec = grid.getSelectionModel().getSelection()[0];
                    if (rec) {
                        Ext.Msg.confirm('Confirmar', '¿Eliminar este registro?', btn => {
                            if (btn === 'yes') {
                                const store = Ext.getStore('tuEntidadStore');
                                store.remove(rec);
                                store.sync({
                                    success: () => Ext.Msg.alert('Éxito', 'Eliminado correctamente'),
                                    failure: () => Ext.Msg.alert('Error', 'Fallo al eliminar.')
                                });
                            }
                        });
                    } else {
                        Ext.Msg.alert('Selección', 'Por favor, selecciona un registro para eliminar.');
                    }
                }
            }
        ]
    });
};

// Exportas la función para que 'app.js' pueda usarla.
window.createCategoriaPanel = createCategoriaPanel;