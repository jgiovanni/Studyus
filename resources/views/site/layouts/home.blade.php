@extends('site.index')

@section('layout')
    <v-parallax src="http://lorempixel.com/1280/720/business">
        <v-layout column align-center justify-center>
            <h1 class="white--text">Vuetify.js</h1>
            <h4 class="white--text">Build your application today!</h4>
            <br>
            <br>
            <br>
        </v-layout>

        <v-layout align-end justify-center>
            <h1 class="white--text">Test</h1>
        </v-layout>
    </v-parallax>
@endsection