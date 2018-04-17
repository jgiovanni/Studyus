@extends('app.index')

@section('layout')
    <v-breadcrumbs icons divider="chevron_right">
        <v-breadcrumbs-item href="/app/">
            Dashboard
        </v-breadcrumbs-item>
        <v-breadcrumbs-item href="/app/assignments">
            Assignments
        </v-breadcrumbs-item>
        <v-breadcrumbs-item>
            <listen-text text="{{ $task->name }}" event="set-task-title"></listen-text>
        </v-breadcrumbs-item>
    </v-breadcrumbs>

    <v-container fluid>
        <v-layout column align-left class="white pa-3 ma-3 ">
            <h4 class="ma-0">
                <listen-text text="{{ $task->name }}" event="set-task-title"></listen-text>
            </h4>
            <p class="caption">
                <listen-text text="{{ $task->description }}" event="set-task-description"></listen-text>
            </p>
            <instructors-task-settings :task="{{ json_encode($task) }}"></instructors-task-settings>
        </v-layout>
        <v-subheader>
            <v-icon mdi>comment-question-outline</v-icon>&nbsp;
            <listen-text text="{{ $task->questions_count }}" event="set-task-question_count"></listen-text>&nbsp;
            Questions
            <v-spacer></v-spacer>
            <action-button classes="light-blue" text="Add Question" dark size="small" event="task-create-question"></action-button>
        </v-subheader>
        <instructors-task id="{{ $task->id }}"></instructors-task>
    </v-container>

@endsection