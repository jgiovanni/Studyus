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
                <v-container fluid class="pa-0">
                    <div class="panel panel-default">
                    <div class="panel-heading">Login</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </v-container>
            </v-content>
        </main>
        <v-footer class="pa-3">
            <v-spacer></v-spacer>
            <div>© {{ date('Y') }}</div>
        </v-footer>
    </v-app>
@endsection
