<!doctype html>
<html lang="{{ config('app.locale') }}">
@include('shared._head')
<body>
<section id="app" v-cloak>
    @yield('template')

    <div id="alerts">
        <v-alert v-for="alert in globalAlerts" :key="alert.id"
                 :primary="alert.type === 'primary'"
                 :secondary="alert.type === 'secondary'"
                 :success="alert.type === 'success'"
                 :info="alert.type === 'info'"
                 :error="alert.type === 'error'"
                 :warning="alert.type === 'warning'"
                 dismissible v-model="alert.show">
            @{{ alert.message }}

        </v-alert>
    </div>

    <v-snackbar v-for="message in globalToasts" :key="message.id"
            :timeout="message.timeout" bottom
            :multi-line="message.mode === 'multi-line'"
            :vertical="message.mode === 'vertical'"
            :success="message.type === 'success'"
            :info="message.type === 'info'"
            :warning="message.type === 'warning'"
            :error="message.type === 'error'"
            :primary="message.type === 'primary'"
            :secondary="message.type === 'secondary'"
            v-model="message.show"
    >
        @{{ message.text }}
        <v-btn flat class="pink--text" @click.native="message.show = false">Close</v-btn>
    </v-snackbar>
</section>

@include('shared._foot')
</body>
</html>
