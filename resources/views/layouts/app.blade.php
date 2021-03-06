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
        @auth
        <v-navigation-drawer v-model="drawer" app>
            <v-list dense>
                <v-list-item to="/">
                    <v-list-item-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/function">
                    <v-list-item-action>
                        <v-icon>home</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Ambten</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/school">
                    <v-list-item-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Scholen</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/employee">
                    <v-list-item-action>
                        <v-icon>contacts</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Personeel</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/logout">
                    <v-list-item-action>
                        <v-icon>exit_to_app</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        @endauth

        <v-app-bar app color="indigo" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>TADD Calc</v-toolbar-title>
        </v-app-bar>
        <v-content>
            @auth
            <router-view></router-view>
            @else
            <v-container fluid>
                <v-row>
                    <v-col cols="12">
                        <v-row style="height: 300px;" justify="center" align="center">
                            <v-card class="ma-3 pa-6" >
                                <a href="{{ route('login') }}">
                                    <v-btn>Login</v-btn>
                                </a>
                            </v-card>
                            <v-card class="ma-3 pa-6" flat>
                                <a href="{{ route('register') }}">
                                    <v-btn>Register</v-btn>
                                </a>
                            </v-card>
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
            @endauth


            @yield('content')
        </v-content>


    </v-app>
    <script src="{{ asset('js/app.js') }}"></script>
</body>


</html>