$.fn.wizzy.locale = {
    views: {
        welcome: {
            title: 'Welcome',
            subtitle: 'Wizzy will guide you through the installation process of your application',
            message: 'Let\'s start by checking the server requirements',
            php: 'PHP version',
            extensions: 'Required extensions',
            requiredVersion: 'Required PHP version is',
            preferredVersion: 'Preferred PHP version is',
            version: 'Your php version is correct'
        },
        environment: {
            title: 'Environment variables',
            subtitle: 'Second step, create envornment variables file',
            message: 'With this form you can create a .personal.env file with custom variables, the file will be automatically saved!',
            filename: '.env filename',
            placeholder: '.production (this is optional)',
            add: 'Add variable',
            modal: {
                title: 'Are you sure?',
                body: 'You can anyway return back and modify your environment variables',
                undo: 'Let me check again...',
                confirm: 'I\'m sure!'
            }
        },
        database: {
            title: 'Database',
            subtitle: 'Third step, database refresh - seed - migration',
            message: 'Wizzy will now run the database migrations, please be sure that the environment variables you have setted are correct. You can also choose to run the database refresh command with the --seed attribute',
            refresh: 'Run migrate:refresh command',
            seed: 'With --seed flag',
            migrations: 'Wizzy is running the migrations, please wait...',
            modal: {
                title: 'Are you sure?',
                body: 'You can anyway return back and run again the migrations',
                undo: 'Let me check again...',
                confirm: 'I\'m sure!'
            }
        },
        conclusion: {
            title: 'Installation completed!',
            subtitle: 'Your application is installed and ready to use!',
            message: 'Wizzy has setted the "WIZZY_ENABLED" variable to false, if you need to run this setup again change it to true!'
        }
    },
    interface: {
        previous: '<i class="fa fa-arrow-left"> Previous',
        next: 'Next <i class="fa fa-arrow-right">',
        complete: 'Installation completed!'
    }
};