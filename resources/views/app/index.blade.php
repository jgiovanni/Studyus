@extends('master')

@section('template')
    <v-app standalone>
        <v-navigation-drawer hide-overlay persistent clipped dark enable-resize-watcher app width="230" v-model="mainDrawer.open">
            <v-list class="pt-0">
                <v-list-tile avatar tag="div">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/women/24.jpg"/>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>John Leider</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-btn icon @click.native.stop="mainDrawer.mini = !mainDrawer.mini">
                            <v-icon>chevron_left</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
            <v-list class="pt-0">
                <v-list-tile href="/app" class="{{ request()->is('app') ? 'list__tile--active white' : '' }}">
                    <v-list-tile-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content class="{{ request()->is('app') ? 'primary--text' : '' }}">
                        Dashboard
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile href="/app/assignments" class="{{ request()->is('app/assignments')|| request()->is('app/assignments/*') ? 'list__tile--active white' : '' }}">
                    <v-list-tile-action>
                        <v-icon>assignment</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content class="{{ request()->is('app/assignments')|| request()->is('app/assignments/*') ? 'primary--text' : '' }}">
                        Assignments
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile href="/app/classrooms"
                             class="{{ request()->is('app/classrooms') || request()->is('app/classrooms/*') ? 'list__tile--active white' : '' }}">
                    <v-list-tile-action>
                        <v-icon>class</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content class="{{ request()->is('app/classrooms') || request()->is('app/classrooms/*') ? 'primary--text' : '' }}">
                        Classrooms
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile href="/app/reports"
                             class="{{ request()->is('app/reports') || request()->is('app/reports/*') ? 'list__tile--active white' : '' }}">
                    <v-list-tile-action>
                        <v-icon>bubble_chart</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content class="{{ request()->is('app/reports') || request()->is('app/reports/*') ? 'primary--text' : '' }}">
                        Reports
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
            <v-list class="pt-0">
                <v-divider></v-divider>
                <v-list-tile @click.native="">
                    <v-list-tile-action>
                        <v-icon>exit_to_app</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Logout</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed clipped-left app class="primary">
            <v-toolbar-title class="white--text" style="width: 300px">
                <v-toolbar-side-icon class="white--text" @click.native.stop="mainDrawer.open = !mainDrawer.open"></v-toolbar-side-icon>
                <span class="headline amber--text" style="font-weight: 800;">STUDYUS</span>
            </v-toolbar-title>
            <v-toolbar-title class="white--text"><listen-text text="{!! SEO::getTitle() !!}" event="set-toolbar-title"></listen-text></v-toolbar-title>

            <v-spacer></v-spacer>
            <quick-create-task></quick-create-task>
            <quick-create-classroom></quick-create-classroom>
        </v-toolbar>
        <main>
            <v-content>
                @yield('layout')
            </v-content>
        </main>
        <v-footer class="pa-3">
            <v-spacer></v-spacer>
            <div>© {{ date('Y') }}</div>
        </v-footer>
    </v-app>
@endsection