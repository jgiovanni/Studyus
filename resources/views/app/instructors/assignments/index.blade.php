@extends('app.index')

@section('layout')
    <v-container fluid class="pa-0">
        <v-layout wrap class="light-blue">
            <v-flex sm6 xs12>
                <instructors-tasks></instructors-tasks>
            </v-flex>
            <v-flex sm6 xs12>
                <instructors-active-task></instructors-active-task>
            </v-flex>
            <v-divider></v-divider>
        </v-layout>
    </v-container>
@endsection