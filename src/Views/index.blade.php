<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wizzy</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Laravel Wizzy">
        <meta name="author" content="IlGala">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="/assets/css/wizzy.css">


        <style>
            body {
                font-family: 'Lato';
            }

            .fa-btn {
                margin-right: 6px;
            }
        </style>
    </head>
    <body id="app-layout">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/')}}">
                        Wizzy
                    </a>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div id="wizzy" class="panel panel-default">
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <script src="/assets/js/wizzy.js"></script>
        <script src="/assets/js/locale/en.js"></script>
        <script>
        $(document).ready(function () {
$('#wizzy').wizzy({
environment: {{ Wizzy::isEnvironmentStepEnabled() }},
        database: {{ Wizzy::isDatabaseStepEnabled() }},
        welcomeRoute: "{{ route(Wizzy::getPrefix() . '.wizzy') }}",
        environmentRoute: "{{ route(Wizzy::getPrefix() . '.environment') }}",
        databaseRoute: "{{ route(Wizzy::getPrefix() . '.database') }}",
        storeSettingsRoute: "{{ route(Wizzy::getPrefix() . '.store') }}",
        });
        });
        </script>
    </body>
</html>
