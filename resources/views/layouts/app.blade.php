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
            <v-list-group
                    prepend-icon="account_balance"
                    value="true"
                >
                    <template v-slot:activator>
                    <v-list-item-title>TADD</v-list-item-title>
                    </template>
                <v-list-item to="/">
                    <v-list-item-action>
                        <v-icon>edit</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Ingave</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                </v-list-group>
                <v-list-item>
                <v-divider></v-divider>
                </v-list-item>
                <v-list-group
                    prepend-icon="list"
                    
                >
                    <template v-slot:activator>
                    <v-list-item-title>Lijsten</v-list-item-title>
                    </template>
                <v-list-item to="/function">
                    <v-list-item-action>
                        <v-icon>contact_mail</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Ambten</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item to="/school">
                    <v-list-item-action>
                        <v-icon>school</v-icon>
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
                </v-list-group>
                <v-list-item>
            
                <v-divider></v-divider>
                </v-list-item>
                @if (Auth::check() && Auth::user()->isadmin)
                <v-list-group
                    prepend-icon="settings"
                    
                    color="purple"
                >
                    <template v-slot:activator>
                        <v-list-item-title>Beheer</v-list-item-title>
                    </template>
                    <v-list-item to="/admin/users">
                        <v-list-item-action>
                            <v-icon>account_circle</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Gebruikers</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-divider></v-divider>
                    </v-list-item>
                </v-list-group>
                @endif
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                <v-list-item>
                    <v-list-item-action>
                    
                {{ csrf_field() }}
                    <v-icon>exit_to_app</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>
                        <button type="submit" class="logoutbutton">Logout</button>
                                            
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                </form>
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