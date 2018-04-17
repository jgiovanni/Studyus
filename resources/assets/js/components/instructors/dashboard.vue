<template>
	<div>
		<v-layout row wrap>
			<v-flex xs6>
				<v-card class="">
					<v-card-title primary-title class="indigo" style="position: relative">
						<div>
							<h3 class="headline mb-0 white--text">My Assignments</h3>
							<!--<div>Located two hours south of Sydney in the <br>Southern Highlands of New South Wales, ...</div>-->
						</div>
						<v-btn tag="a" href="/app/assignments" fab dark absolute hover small right bottom class="light-blue"><v-icon>assignment</v-icon></v-btn>
					</v-card-title>
					<v-list two-line class="">
						<template v-for="(task, index) in tasks.data">
							<v-divider v-if="index>0" inset></v-divider>
							<v-list-tile :href="'/app/assignments/' + task.url_id">
								<v-list-tile-content>
									<v-list-tile-title v-text="task.name"></v-list-tile-title>
									<v-list-tile-sub-title v-html="task.description"></v-list-tile-sub-title>
								</v-list-tile-content>
								<v-list-tile-action>
									<v-list-tile-action-text>{{task.questions_count}} <v-icon mdi>comment-question-outline</v-icon></v-list-tile-action-text>
								</v-list-tile-action>
							</v-list-tile>
						</template>
					</v-list>
				</v-card>
			</v-flex>

			<v-flex xs6>
				<v-card class="">
					<v-card-title primary-title class="indigo" style="position: relative">
						<div>
							<h3 class="headline mb-0 white--text">My Classrooms</h3>
							<!--<div>Located two hours south of Sydney in the <br>Southern Highlands of New South Wales, ...</div>-->
						</div>
						<v-btn tag="a" href="/app/classrooms" fab dark absolute hover small right bottom class="light-blue"><v-icon>class</v-icon></v-btn>
					</v-card-title>
					<v-list two-line class="">
						<template v-for="(classroom, index) in classrooms.data">
							<v-divider v-if="index>0" inset></v-divider>
							<v-list-tile :href="'/app/classrooms/' + classroom.url_id">
								<v-list-tile-content>
									<v-list-tile-title v-text="classroom.name.toUpperCase()"></v-list-tile-title>
									<v-list-tile-sub-title v-html="classroom.description"></v-list-tile-sub-title>
								</v-list-tile-content>
								<v-list-tile-action>
									<v-list-tile-action-text>{{classroom.students_count}} <v-icon mdi>account-multiple</v-icon></v-list-tile-action-text>
								</v-list-tile-action>
							</v-list-tile>
						</template>
					</v-list>
				</v-card>
			</v-flex>
		</v-layout>
	</div>
</template>
<style></style>
<script type="text/javascript">
  export default{
    name: 'dashboard',
    data () {
      return {
        tasks: {
          data: [],
          meta: {
            pagination: { current_page: 1 }
          }
        },
        classrooms: {
          data: [],
          meta: {
            pagination: { current_page: 1 }
          }
        },
        TaskResource: this.$resource('assignments{/task}', {}),
        ClassroomResource: this.$resource('classrooms{/classroom}', {}),
      }
    },
    methods: {
      getTasks () {
        let params = {
          per_page: 5
        }
        return this.TaskResource.get(params).then(response => {
          this.tasks = response.body
        }, this.$root.handleApiError)
      },
      getClassrooms () {
        let params = {
          // replace with [this.$root.user.id]
          instructors: ['25ee2b4e-a599-4f3e-91a8-33fe9b81f891'],
          per_page: 5
        }
        return this.ClassroomResource.get(params).then(response => {
          this.classrooms = response.body
        }, this.$root.handleApiError)
      },
      /* getStudents () {
        let params = {}
        return this.StudentResource.get({ role: 'students' }).then(response => {

        }, this.$root.handleApiError)
      }, */
    },
    mounted () {
      this.getTasks()
      this.getClassrooms()
    }
  }
</script>
