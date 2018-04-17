<template>
	<div>
		<v-layout row wrap align-center v-if="classroomObj">
			<v-flex xs12 sm6 class="text-xs-center text-sm-left">
				<v-icon>{{classroomObj.public ? 'visibility' : 'visibility_off'}}</v-icon>&nbsp;{{classroomObj.public ? 'Public' : 'Private'}}
				&nbsp;
				<template v-if="classroomObj.opened_at">
					<v-icon>timer_on</v-icon>&nbsp;{{classroomObj.opened_at|vmTimeAgo}}
					&nbsp;
				</template>
				<template v-if="classroomObj.due_at">
					<v-icon>timer</v-icon>&nbsp;{{classroomObj.due_at|vmTimeAgo}}
					&nbsp;
				</template>
				<template v-if="classroomObj.opened_at">
					<v-icon>timer_off</v-icon>&nbsp;{{classroomObj.closed_at|vmTimeAgo}}
					&nbsp;
				</template>
			</v-flex>
			<v-flex xs12 sm6 class="text-xs-center text-sm-right">
				<!--<v-tooltip left>-->
				<v-btn tag="a" small icon fab class="success" dark :href="`/app/classrooms/${classroomObj.url_id}/preview`">
					<v-icon>play_arrow</v-icon>
				</v-btn>
				<!--<span>Preview</span>
			</v-tooltip>-->
				<v-dialog v-model="showSettingsDialog" lazy absolute width="600px">
					<!--<v-tooltip left>-->
					<v-btn small icon fab class="light-blue" dark slot="activator"><v-icon>mode_edit</v-icon></v-btn>
					<!--<span>Edit Settings</span>
				</v-tooltip>-->
					<v-card>
						<v-card-title>
							<div class="headline">Classroom Settings</div>
						</v-card-title>
						<v-card-text v-if="editClassroom">
							<v-text-field label="Name" v-model="editClassroom.name"></v-text-field>
							<v-select label="Tags" chips tags single-line persistent-hint prepend-icon=""
							          hint="Add tags to help classify this assignment"
							          append-icon="" clearable v-model="editClassroom.tags" @submit.prevent>
								<template slot="selection" slot-scope="data">
									<v-chip color="primary" text-color="white" label close
									        @input="removeTag(data.item)" :selected="data.selected">
										<strong>{{ data.item }}</strong>
									</v-chip>
								</template>
							</v-select>
							<!--<v-radio-group v-model="editClassroom.type" row>
								<v-radio label="Homework" value="homework" ></v-radio>
								<v-radio label="Test/Quiz" value="quiz"></v-radio>
							</v-radio-group>-->
							<br>
							<v-subheader>Advanced Settings</v-subheader>
							<v-expansion-panel class="elevation-0">
								<v-expansion-panel-content>
									<div slot="header">
										<v-layout row>
											<v-flex xs6>
												<h6 class="mb-0">Open Date & Time<small>(optional)</small></h6>
												<small class="caption grey--text">When the assignment is made available to students</small>
											</v-flex>
											<v-flex xs6 class="">
												<h5 class="mb-0"><v-icon>alarm_on</v-icon>
													<template v-if="editClassroom.opened_at">{{editClassroom.opened_at|mFormat('lll')}}</template>
													<template v-else>No Open Date</template>
												</h5>
											</v-flex>
										</v-layout>
									</div>
									<v-card>
										<v-switch label="No Open Date" v-model="openedAtMenu"></v-switch>
										<v-layout v-if="!openedAtMenu" row>
											<v-flex xs6>
												<v-date-picker v-model="opened_date.date" autosave :allowed-dates="allowedDates"></v-date-picker>
											</v-flex>
											<v-flex xs6>
												<v-time-picker v-model="opened_date.time" autosave :allowed-minutes="allowedMinutes"></v-time-picker>
											</v-flex>
										</v-layout>
									</v-card>
								</v-expansion-panel-content>
								<v-expansion-panel-content>
									<div slot="header">
										<v-layout row>
											<v-flex xs6>
												<h6 class="mb-0">Due Date & Time<small>(optional)</small></h6>
												<small class="caption grey--text">When the assignment is due for students</small>
											</v-flex>
											<v-flex xs6>
												<h5 class="mb-0"><v-icon>alarm</v-icon>
													<template v-if="editClassroom.due_at">{{editClassroom.due_at|mFormat('lll')}}</template>
													<template v-else>No Due Date</template>
												</h5>
											</v-flex>
										</v-layout>
									</div>
									<v-card>
										<v-switch label="No Due Date" v-model="dueAtMenu"></v-switch>
										<v-layout v-if="!dueAtMenu" row>
											<v-flex xs6>
												<v-date-picker v-model="due_date.date" autosave :allowed-dates="allowedEndDates"></v-date-picker>
											</v-flex>
											<v-flex xs6>
												<v-time-picker v-model="due_date.time" autosave :allowed-minutes="allowedMinutes"></v-time-picker>
											</v-flex>
										</v-layout>
									</v-card>
								</v-expansion-panel-content>
								<v-expansion-panel-content>
									<div slot="header">
										<v-layout row>
											<v-flex xs6>
												<h6 class="mb-0">Close Date & Time<small>(optional)</small></h6>
												<small class="caption grey--text">When the assignment is no longer available to students</small>
											</v-flex>
											<v-flex xs6>
												<h5 class="mb-0" :class=""><v-icon>alarm_off</v-icon>
													<template v-if="editClassroom.closed_at">{{editClassroom.closed_at|mFormat('lll')}}</template>
													<template v-else>No Close Date</template>
												</h5>
											</v-flex>
										</v-layout>
									</div>
									<v-card>
										<v-switch label="No Close Date" v-model="closedAtMenu"></v-switch>
										<v-layout v-if="!closedAtMenu" row>
											<v-flex xs6>
												<v-date-picker v-model="closed_date.date" autosave :allowed-dates="allowedEndDates"></v-date-picker>
											</v-flex>
											<v-flex xs6>
												<v-time-picker v-model="closed_date.time" autosave :allowed-minutes="allowedMinutes"></v-time-picker>
											</v-flex>
										</v-layout>
									</v-card>
								</v-expansion-panel-content>
							</v-expansion-panel>
							<v-switch label="Visible to other Instructors"
							          v-model="editClassroom.public"
							          color="primary"
							          hide-details></v-switch>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn class="green--text darken-1" flat @click.native="showSettingsDialog = false">cancel</v-btn>
							<v-btn class="green--text darken-1" flat @click.native="updateSettings">Save</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</v-flex>
		</v-layout>
	</div>
</template>
<style></style>
<script type="text/javascript">
  export default {
    name: 'classroom-settings',
    props: {
      classroom: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        classroomObj: null,
        showSettingsDialog: false,

        subjectSelect: '',
        grades: ['Pre-K', 'Kindergarten', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'],
        subjects: ['Math', 'Science', 'Social Studies', 'Physics', 'Chemistry', 'Biology', 'Health & Medicine', 'Engineering', 'History', 'Art', 'English Literature', 'Language', 'Other'],

        ClassroomResource: this.$resource('classrooms{/classroom}', {classroom: this.classroom.id}),

      }
    },
    methods: {
      updateSettings () {},
    },
    mounted () {
      this.classroomObj = this.classroom

    }
  }
</script>