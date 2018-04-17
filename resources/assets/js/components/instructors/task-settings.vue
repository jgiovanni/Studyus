<template>
	<div>
		<v-layout row wrap align-center v-if="taskObj">
			<v-flex xs12 sm6 class="text-xs-center text-sm-left">
				<v-icon>{{taskObj.public ? 'visibility' : 'visibility_off'}}</v-icon>&nbsp;{{taskObj.public ? 'Public' : 'Private'}}
				&nbsp;
				<template v-if="taskObj.opened_at">
					<v-icon>timer_on</v-icon>&nbsp;{{taskObj.opened_at|vmTimeAgo}}
					&nbsp;
				</template>
				<template v-if="taskObj.due_at">
					<v-icon>timer</v-icon>&nbsp;{{taskObj.due_at|vmTimeAgo}}
					&nbsp;
				</template>
				<template v-if="taskObj.opened_at">
					<v-icon>timer_off</v-icon>&nbsp;{{taskObj.closed_at|vmTimeAgo}}
					&nbsp;
				</template>
			</v-flex>
			<v-flex xs12 sm6 class="text-xs-center text-sm-right">
				<!--<v-tooltip left>-->
					<v-btn tag="a" small icon fab class="success" dark :href="`/app/assignments/${taskObj.url_id}/preview`">
						<v-icon>play_arrow</v-icon>
					</v-btn>
					<!--<span>Preview</span>
				</v-tooltip>-->

				<v-dialog v-model="showDuplicateDialog" lazy absolute width="600px">
					<v-btn small icon fab class="secondary" slot="activator" dark><v-icon>content_copy</v-icon></v-btn>
					<!--<span>Duplicate</span>-->

					<v-card>
						<v-card-title>
							<div class="headline">Duplicate Task</div>
						</v-card-title>
						<v-card-text>
							<p>This will duplicate the task along with its questions and answers</p>
							<v-text-field label="Duplicate Name" v-model="duplicateTaskData.name" required></v-text-field>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn class="green--text darken-1" flat @click.native="showDuplicateDialog = false">Cancel</v-btn>
							<v-btn class="green--text darken-1" flat @click.native="duplicateTask">Duplicate</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<v-dialog v-model="showSettingsDialog" lazy absolute width="600px">
					<!--<v-tooltip left>-->
						<v-btn small icon fab class="light-blue" dark slot="activator"><v-icon>mode_edit</v-icon></v-btn>
						<!--<span>Edit Settings</span>
					</v-tooltip>-->
					<v-card>
						<v-card-title>
							<div class="headline">Assignment Settings</div>
						</v-card-title>
						<v-card-text v-if="editTask">
							<v-text-field label="Name" v-model="editTask.name"></v-text-field>
							<v-select label="Tags" chips tags single-line persistent-hint prepend-icon=""
							          hint="Add tags to help classify this assignment"
							          append-icon="" clearable v-model="editTask.tags" @submit.prevent>
								<template slot="selection" slot-scope="data">
									<v-chip color="primary" text-color="white" label close
									        @input="removeTag(data.item)" :selected="data.selected">
										<strong>{{ data.item }}</strong>
									</v-chip>
								</template>
							</v-select>
							<!--<v-radio-group v-model="editTask.type" row>
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
													<template v-if="editTask.opened_at">{{editTask.opened_at|mFormat('lll')}}</template>
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
													<template v-if="editTask.due_at">{{editTask.due_at|mFormat('lll')}}</template>
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
													<template v-if="editTask.closed_at">{{editTask.closed_at|mFormat('lll')}}</template>
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
							          v-model="editTask.public"
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
				<v-dialog v-model="showDeleteDialog" lazy absolute persistent>
					<!--<v-tooltip left>-->
						<v-btn small icon fab class="red" dark slot="activator"><v-icon>delete_forever</v-icon></v-btn>
						<!--<span>Delete</span>
					</v-tooltip>-->
					<v-card>
						<v-card-title>
							<div class="headline">Delete Assignment</div>

						</v-card-title>
						<v-card-text>
							<p>Are you sure you want to delete '{{taskObj.name}}'?</p>
							<v-alert info value="true">
								<ul>
									<li>The questions and answers will be deleted also.</li>
									<li>This will not affect copied questions</li>
									<li>This will not affect duplicated assignments</li>
								</ul>
							</v-alert>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn class="green--text darken-1" flat @click.native="showDeleteDialog = false">Nah</v-btn>
							<v-btn class="green--text darken-1" flat @click.native="updateSettings">Confirm</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</v-flex>
		</v-layout>
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore'
  import moment from 'moment'
  export default{
    name: 'task-settings',
    props: {
      task: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        showDuplicateDialog: false,
        showSettingsDialog: false,
        showDeleteDialog: false,
        taskObj: null,
        duplicateTaskData: {
          name: null,
          public: false
        },
        editTask: null,
        openedAtMenu: true,
        dueAtMenu: true,
        closedAtMenu: true,
        opened_date: { date: null, time: moment().startOf('day').format('HH:mma') },
        due_date: { date: null, time: moment().startOf('day').format('HH:mma') },
        closed_date: { date: null, time: moment().startOf('day').format('HH:mma') },
        allowedDates: {
          min: moment().toDate(),
          max: moment().add(1, 'years').toDate()
        },
        allowedEndDates: {
          min: moment().toDate(),
          max: moment().add(1, 'years').toDate()
        },
        allowedMinutes: [0, 10, 20, 30, 40, 50, 60],
        TaskResource: this.$resource('assignments{/task}', {task: this.task.id})
      }
    },
    watch: {
      showSettingsDialog (val) {
        if (val) {
          let editObj = _.extend({}, this.taskObj)
//          editObj.opened_at = moment(editObj.opened_at, 'YYYY-MM-DD HH:mm:ss').format('lll')
//          editObj.closed_at = moment(editObj.closed_at, 'YYYY-MM-DD HH:mm:ss').format('lll')
          this.editTask = editObj
        }
      },
      openedAtMenu (val) {
        if (val) this.editTask.opened_at = null
      },
      dueAtMenu (val) {
        if (val) this.editTask.due_at = null
      },
      closedAtMenu (val) {
        if (val) this.editTask.closed_at = null
      },
      opened_date: {
        deep: true,
        handler (val) {
          if (!this.openedAtMenu) {
            this.editTask.opened_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      },
      due_date: {
        deep: true,
        handler (val) {
          if (!this.dueAtMenu) {
            this.editTask.due_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      },
      closed_date: {
        deep: true,
        handler (val) {
          if (!this.closedAtMenu) {
            this.editTask.closed_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      }
    },
    methods: {
      updateSettings () {
        // format data for API
        let data = _.extend({}, this.editTask)
        data.opened_at = moment(data.opened_at, 'lll').format('YYYY-MM-DD HH:mm:ss')
        data.closed_at = moment(data.closed_at, 'lll').format('YYYY-MM-DD HH:mm:ss')
        // send data
        this.TaskResource.update(null, data).then(response => {
          this.taskObj = response.body.data
          this.$root.$emit('set-task-title', this.taskObj.name)
          this.$root.$emit('set-task-description', this.taskObj.description)
          this.editTask = null
          this.showSettingsDialog = false
        }, this.$root.handleApiError)
      },
      duplicateTask () {
        let task = _.extend({}, this.taskObj, this.duplicateTaskData)
        delete task.id
        this.TaskResource.save(task).then(response => {
          location.pathname = '/app/assignments/' + response.body.data.url_id
        }, this.$root.handleApiError)
      },
      deleteTask () {
        this.TaskResource.delete().then(() => {
          this.$root.$emit('alert_user', {type: 'info', message: 'Task Deleted'})
          location.pathname = '/app/assignments'
        }, this.$root.handleApiError)
      }
    },
    mounted () {
      this.taskObj = this.task
      this.taskObj.tags = _.chain(this.taskObj.tags).pluck('name').pluck('en').value()
    }
  }
</script>
