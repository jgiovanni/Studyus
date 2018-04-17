<template>
	<div>
		<v-dialog v-model="dialog" lazy persistent width="800px">
			<v-btn absolute dark fab bottom right class="orange" slot="activator" v-show="isInstructorsTasks">
				<v-icon>add</v-icon>
			</v-btn>
			<v-card>
				<v-card-title>
					<div class="headline">Create Assignment</div>
				</v-card-title>
				<v-card-text>
					<v-text-field label="Name" persistent-hint
					              hint="Give this assignment a unique name"
					              v-model="newTask.name" autofocus
					              :error-messages="errors.collect('name')"
					              v-validate="'required|max:100'"
					              data-vv-name="name" required></v-text-field>
					<!--<v-text-field label="Description" multi-line persistent-hint
					              hint="Can be useful for adding contextual information when searching."
					              :rows="2" v-model="newTask.description"></v-text-field>-->
					<v-select label="Tags" chips tags single-line persistent-hint prepend-icon=""
					          hint="Add tags to help classify this assignment"
					          append-icon="" clearable v-model="newTask.tags" @submit.prevent>
						<template slot="selection" slot-scope="data">
							<v-chip color="primary" text-color="white" label close
							        @input="removeTag(data.item)" :selected="data.selected">
								<strong>{{ data.item }}</strong>
							</v-chip>
						</template>
					</v-select>
					<!--<v-radio-group v-model="newTask.type" row>
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
											<template v-if="newTask.opened_at">{{newTask.opened_at|mFormat('lll')}}</template>
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
											<template v-if="newTask.due_at">{{newTask.due_at|mFormat('lll')}}</template>
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
											<template v-if="newTask.closed_at">{{newTask.closed_at|mFormat('lll')}}</template>
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
					<!--<v-layout row wrap align-center justify-center>
						<v-flex xs12 sm6 class="text-center">
							<v-menu lazy :close-on-content-click="false" v-model="openedAtMenu"
							        offset-y
							        :nudge-left="40">
								<v-text-field slot="activator" label="Open Date & Time" v-model="newTask.opened_at"
								              persistent-hint hint="When the assignment is made available to students"
								              prepend-icon="timer" readonly></v-text-field>
								<v-layout row>
									<v-flex xs6>
										<v-date-picker v-model="opened_date" no-title
										               :allowed-dates="allowedDates"></v-date-picker>
									</v-flex>
									<v-flex xs6>
										<v-time-picker v-model="opened_time" no-title :allowed-minutes="allowedMinutes">
											<template slot-scope="{ save, cancel }">
												<v-card-actions>
													<v-spacer></v-spacer>
													<v-btn flat color="primary"
													       @click="newTask.opened_at = null;save()">Clear
													</v-btn>
													<v-btn flat color="primary" @click="cancel">Cancel</v-btn>
													<v-btn flat color="primary" @click="save">OK</v-btn>
												</v-card-actions>
											</template>
										</v-time-picker>
									</v-flex>
								</v-layout>
							</v-menu>
						</v-flex>
						<v-flex xs12 sm6 class="text-center">
							<v-menu lazy :close-on-content-click="false" v-model="dueAtMenu" offset-y
							        :nudge-left="40">
								<v-text-field slot="activator" label="Due Date & Time" v-model="newTask.due_at"
								              persistent-hint hint="When the assignment is due for students"
								              prepend-icon="timer_off" readonly></v-text-field>
								<v-layout row>
									<v-flex xs6>
										<v-date-picker v-model="due_date" no-title
										               :allowed-dates="allowedEndDates"></v-date-picker>
									</v-flex>
									<v-flex xs6>
										<v-time-picker v-model="due_time" no-title :allowed-minutes="allowedMinutes">
											<template slot-scope="{ save, cancel }">
												<v-card-actions>
													<v-spacer></v-spacer>
													<v-btn flat color="primary" @click="newTask.due_at = null;save()">
														Clear
													</v-btn>
													<v-btn flat color="primary" @click="cancel">Cancel</v-btn>
													<v-btn flat color="primary" @click="save">OK</v-btn>
												</v-card-actions>
											</template>
										</v-time-picker>
									</v-flex>
								</v-layout>
							</v-menu>
						</v-flex>
						<v-flex xs12 class="text-center">
							<v-menu lazy :close-on-content-click="false" v-model="closedAtMenu" offset-y
							        :nudge-left="40">
								<v-text-field slot="activator" label="Close Date & Time" v-model="newTask.closed_at"
								              persistent-hint
								              hint="When the assignment is no longer available to students"
								              prepend-icon="timer_off" readonly></v-text-field>
								<v-layout row>
									<v-flex xs6>
										<v-date-picker v-model="closed_date" no-title
										               :allowed-dates="allowedEndDates"></v-date-picker>
									</v-flex>
									<v-flex xs6>
										<v-time-picker v-model="closed_time" no-title :allowed-minutes="allowedMinutes">
											<template slot-scope="{ save, cancel }">
												<v-card-actions>
													<v-spacer></v-spacer>
													<v-btn flat color="primary"
													       @click="newTask.closed_at = null;save()">Clear
													</v-btn>
													<v-btn flat color="primary" @click="cancel">Cancel</v-btn>
													<v-btn flat color="primary" @click="save">OK</v-btn>
												</v-card-actions>
											</template>
										</v-time-picker>
									</v-flex>
								</v-layout>
							</v-menu>
						</v-flex>
					</v-layout>-->
					<v-switch label="Visible to other Instructors"
					          v-model="newTask.public"
					          color="primary"
					          hide-details></v-switch>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn class="secondary--text darken-1" flat @click.stop="cancel">Cancel</v-btn>
					<v-btn class="green--text darken-1" flat @click.stop="create">Create</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore'
  import moment from 'moment'

  export default {
    name: 'quick-create-assignment',
    data () {
      return {
        dialog: false,
        newTask: {
          user_id: this.$root.user.id,
          tags: [],
          name: null,
          description: null,
          opened_at: null,
          due_at: null,
          closed_at: null,
          public: false
        },
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
        TaskResource: this.$resource('assignments{/assignment}')
        // isInstructorsTasks: true
      }
    },
    watch: {
      openedAtMenu (val) {
        if (val) this.newTask.opened_at = null
      },
      dueAtMenu (val) {
        if (val) this.newTask.due_at = null
      },
      closedAtMenu (val) {
        if (val) this.newTask.closed_at = null
      },
      // Set Min End Date to avoid date overlap
      'newTask.opened_at' (val) {
        if (val) {
          this.allowedEndDates.min = moment(val)
        }
      },
      opened_date: {
        deep: true,
        handler (val) {
          if (!this.openedAtMenu) {
            this.newTask.opened_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      },
      due_date: {
        deep: true,
        handler (val) {
          if (!this.dueAtMenu) {
            this.newTask.due_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      },
      closed_date: {
        deep: true,
        handler (val) {
          if (!this.closedAtMenu) {
            this.newTask.closed_at = moment(`${val.date} ${val.time}`, 'YYYY-MM-DD HH:mma').format()
          }
        }
      },
    },
    methods: {
      cancel () {
        this.dialog = false
        this.reset()
      },
      create () {
        this.$validator.validateAll().then(result => {
          if (!result) {
//            this.$root.$emit('alert_user', {type: 'error', message: 'Please check the form.'})
            return false
          }
          // format data for API
          let data = _.extend({}, this.newTask)
          if (data.opened_at) data.opened_at = moment(data.opened_at).format('YYYY-MM-DD HH:mm:ss')
          if (data.due_at) data.due_at = moment(data.opened_at).format('YYYY-MM-DD HH:mm:ss')
          if (data.closed_at) data.closed_at = moment(data.closed_at).format('YYYY-MM-DD HH:mm:ss')
          // send data
          this.TaskResource.save(data).then(response => {
//            this.newTask = response.body.data
            swal({
              title: 'Awesome!',
              text: 'Assignment created!',
              icon: 'success',
              buttons: {
                open: { text: 'View Assignment', value: 'goto' },
                confirm: { text: 'Done', value: 'done' }
              }
            }).then(value => {
              switch (value) {
                case 'goto':
                  location.pathname = '/app/assignments/' + response.body.data.url_id
                  break
                case 'done':
                  this.cancel()
                  this.$root.$emit('Tasks:get')
                  break
              }
            })
          }, this.$root.handleApiError)
        })
      },
      removeTag (item) {
        this.newTask.tags.splice(this.newTask.tags.indexOf(item), 1)
      },
      reset () {
        this.newTask = {
          tags: [],
          user_id: this.$root.user.id,
          name: null,
          description: null,
          opened_at: null,
          due_at: null,
          closed_at: null,
          public: false
        }
      }
    },
    mounted () {

    }
  }
</script>
