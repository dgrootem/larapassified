<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TADD v2</title>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900%7CRoboto+Mono:500%7CMaterial+Icons" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/extra.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <v-app id="app">
        @auth
        @if(Auth::user()->isactive == 1)
        <v-navigation-drawer v-model="drawer" app>
            <v-list dense>
                
                <v-list-group prepend-icon="account_balance" value="true">
                    <template v-slot:activator>
                        <v-list-item-title>TADD</v-list-item-title>
                    </template>
                    <v-list-item to="/">
                        <v-list-item-action>
                            <v-icon>dashboard</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Dashboard</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/ingave">
                        <v-list-item-action>
                            @if(Auth::user()->readonly)
                            <v-icon>visibility</v-icon>
                            @else
                            <v-icon>edit</v-icon>
                            @endif
                        </v-list-item-action>
                        <v-list-item-content>
                            
                            <v-list-item-title>Opzoeken personeelsfiche</v-list-item-title>
                            
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
                <v-list-item>

                    <v-divider></v-divider>

                </v-list-item>

                <v-list-group prepend-icon="list">
                    <template v-slot:activator>
                        <v-list-item-title>Lijsten</v-list-item-title>
                    </template>
                    @if(!(Auth::user()->readonly))
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
                    @endif
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
                <v-list-group prepend-icon="settings" color="purple">
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

                    <v-list-item to="/admin/settings">
                        <v-list-item-action>
                            <v-icon>build</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Instellingen</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/admin/archief">
                        <v-list-item-action>
                            <v-icon>send</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Archivering</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item>
                        <v-divider></v-divider>
                    </v-list-item>
                </v-list-group>
                @endif
                
                <v-list-item :to="{name : 'myprofile', params: {user : {{Auth::user()->id}} } }">
                    <v-list-item-action>
                        <v-icon>account_circle</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Mijn profiel</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

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
        @endif
        @endauth

        <v-app-bar app color="orange" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>***** TEST **** TEST ***** TEST ***** TADD Calc ***** TEST **** TEST ***** TEST *****</v-toolbar-title>
        </v-app-bar>
        <v-content>
            @if(Auth::check() && (Auth::user()->isactive == 1))
            <router-view></router-view>
            @else
            <v-container fluid>
                <v-row>
                    <v-col cols="12">
                        <v-row style="height: 300px;" justify="center" align="center">
                            <v-card class="ma-3 pa-6">
                                <a href="{{ route('login') }}">
                                    <v-btn>Login</v-btn>
                                </a>
                            </v-card>
                            <!-- <v-card class="ma-3 pa-6" flat>
                                <a href="{{ route('register') }}">
                                    <v-btn>Register</v-btn>
                                </a>
                            </v-card> -->
                        </v-row>
                    </v-col>
                </v-row>
            </v-container>
            @endif

            @yield('content')
        </v-content>


    </v-app>
    <script src="{{ asset('js/app.js') }}"></script>
    @auth
    @if(Auth::user()->isactive == 1)
    <script>
        window.u53r = {
            id: {{ Auth::user()->id }},
            isadmin: {{ Auth::user()->isadmin}},
            isactive: {{Auth::user()->isactive}},
            readonly: {{Auth::user()->readonly}},
        };
    </script>
    @endif
    @endauth
</body>


</html>