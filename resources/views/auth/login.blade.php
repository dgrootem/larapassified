<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900%7CRoboto+Mono:500%7CMaterial+Icons" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <v-app id="app">
        <v-content>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <v-card class="elevation-12">
                            <v-toolbar color="primary" dark flat>
                                <v-toolbar-title>Aanmelden</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                                <v-tooltip right>
                                    <template v-slot:activator="{ on }">
                                        <v-btn href="http://www.skbl.be" icon large target="_blank" v-on="on">
                                            <v-img src="http://www.skbl.be/joomla/images/logo/logo.png" width="40px" height="40px"></v-img>
                                        </v-btn>
                                    </template>
                                    <span>SKBL</span>
                                </v-tooltip>
                            </v-toolbar>
                            <v-form method="POST" action="{{ route('login') }}">
                                <v-card-text>

                                    {{ csrf_field() }}
                                    <v-text-field label="Login" name="email" prepend-icon="person" type="text"></v-text-field>
                                    <v-text-field id="password" label="Password" name="password" prepend-icon="lock" type="password"></v-text-field>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me

                                </v-card-text>
                                <v-card-actions>
                                    <div class="flex-grow-1"></div>
                                    <v-btn color="primary" type="submit">Login</v-btn>
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-col>
                </v-row>
        </v-content>
    </v-app>
    <script src="{{ asset('js/app.js') }}"></script>

</body>


</html>