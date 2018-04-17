@extends('app.index')

@section('layout')
    <v-breadcrumbs icons divider="chevron_right">
        <v-breadcrumbs-item href="/app/">
            Dashboard
        </v-breadcrumbs-item>
        <v-breadcrumbs-item href="/app/classrooms">
            Classrooms
        </v-breadcrumbs-item>
        <v-breadcrumbs-item>
            <listen-text text="{{ $classroom->name }}" event="set-classroom-title"></listen-text>
        </v-breadcrumbs-item>
    </v-breadcrumbs>

    <v-container fluid>
        <v-layout column align-left class="white pa-3 ma-3 ">
            <h4 class="ma-0">
                <listen-text text="{{ $classroom->name }}" event="set-classroom-title"></listen-text>
            </h4>
            <p class="caption">
                <v-chip small class="darken-4" v-for="grade in {{ json_encode($classroom->grades) }}" :key="grade" color="secondary" text-color="white" v-text="grade"></v-chip>
                <v-chip small color="orange" text-color="white">{{ $classroom->subject }}</v-chip>
            </p>
            <instructors-classroom-settings :classroom="{{ json_encode($classroom) }}"></instructors-classroom-settings>
        </v-layout>
    </v-container>
@endsection