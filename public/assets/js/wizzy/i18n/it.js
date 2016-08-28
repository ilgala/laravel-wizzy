$.fn.wizzy.locale = {
    views: {
        welcome: {
            title: 'Benvenuto',
            subtitle: 'Wizzy ti guiderà attraverso il processo di installazione della tua applicazione',
            message: 'Iniziamo controllando i requisiti di sistema',
            php: 'Versione PHP',
            extensions: 'Estensioni richieste',
            requiredVersion: 'La versione richiesta di PHP è',
            preferredVersion: 'La versione preferita di PHP è',
            version: 'La tua versione di PHP è corretta'
        },
        environment: {
            title: 'Variabili ambientali',
            subtitle: 'Secondo step, creazione del file delle variabili ambientali',
            message: 'Con questo form potrai creare un file .personal.env con variabili costumizzabili, il file verrà salvato automaticamente!',
            filename: '.env filename',
            placeholder: '.production (questo è opzionale)',
            add: 'Aggiungi una variabile',
            modal: {
                title: 'Sei sicuro?',
                body: 'Potrai comunque tornare indietro e modificare le tue variabili ambientali',
                undo: 'Fammi controllare di nuovo...',
                confirm: 'Sono sicuro!'
            }
        },
        database: {
            title: 'Database',
            subtitle: 'Terzo step, database refresh - seed - migration',
            message: 'Wizzy lancerà ora le migrazioni al database, per favore assicurati che le variabili ambientali che hai impostato siano corrette. Puoi anche scegliere di lanciare il comando database refresh con l\'attributo --seed',
            refresh: 'Lancia il comando migrate:refresh',
            seed: 'Con il flag --seed',
            migrations: 'Wizzy sta eseguendo le migrazioni, ti preghiamo di attendere...',
            modal: {
                title: 'Sei sicuro?',
                body: 'Potrai comunque tornare indietro e lanciare nuovamente le migrazioni',
                undo: 'Fammi controllare di nuovo...',
                confirm: 'Sono sicuro!'
            }
        },
        conclusion: {
            title: 'Installazione completata!',
            subtitle: 'La tua applicazione è installata e pronta all\'uso!',
            message: 'Wizzy ha impostato la variabile "WIZZY_ENABLED" a false, se hai bisogno di lanciare nuovamente questo wizard, dovrai impostarla a true!'
        }
    },
    interface: {
        previous: '<i class="fa fa-arrow-left"> Precedente',
        next: 'Successivo <i class="fa fa-arrow-right">',
        complete: 'Installazione completata!'
    }
};