$.fn.wizzy.locale = {
    views: {
        welcome: {
            title: 'Welcome',
            subtitle: 'Wizzy will guide you through the installation process of your application',
            message: 'Let\'s start by checking your server requirements',
            php: 'PHP version',
            extensions: 'Required extensions',
            requiredVersion: 'Required php version is',
            preferredVersion: 'Preferred php version is',
            version: 'Your php version is correct',
        },
        environment: {
            title: 'Environment variables',
            subtitle: 'Second step, create envornment variables file',
            message: 'With this form you are able to create a .personal.env file with custom variables, don\'t forget to save the file before moving on!',
            filename: '.env filename',
            placeholder: '.production (this is optional)',
            add: 'Add variable',
            save: 'Save file',
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
            message: 'Wizzy will now run the database migrations, please be sure that the environment variables you have setted are correct. You can also choose to run the database refresh command and the table seed command',
            modal: {
                title: 'Are you sure?',
                body: 'You can anyway return back and modify your database variables',
                undo: 'Let me check again...',
                confirm: 'I\'m sure!'
            }
        },
        conclusion: {
            title: 'Welcome',
            subtitle: 'Wizzy will guide you through the installation process of your application',
            message: 'Let\'s start by checking your server requirements',
        },
    },
    interface: {
        previous: '<i class="fa fa-arrow-left"> Previous',
        next: 'Next <i class="fa fa-arrow-right">',
    }
}