<template>
	<div>
		<v-toolbar class="white" flat style="z-index: 1">
			<v-text-field v-model="taskFilters.search" prepend-icon="search" hide-details single-line
				placeholder="Search Assignments"></v-text-field>
			<v-btn icon>
				<v-icon>filter_list</v-icon>
			</v-btn>
		</v-toolbar>
		<template v-for="(task, index) in tasks.data">
			<v-card tile flat class="" :class="{ 'light-blue is-active': activeTask && activeTask.id === task.id}" :key="task.id"
			        @mouseover="setActiveTask(task)"
			        @mouseout="activeTask = null">
				<v-card-title class="pb-0">
					<h6 class="ma-0"><a :href="`/app/assignments/${task.url_id}`">{{task.name}}</a></h6>
					<v-spacer></v-spacer>
					<v-menu bottom left>
						<v-btn icon slot="activator" ripple>
							<v-icon class="primary--text">more_horiz</v-icon>
						</v-btn>
						<v-list>
							<v-list-tile :href="`/app/assignments/${task.url_id}`">
								<v-list-tile-title>View</v-list-tile-title>
							</v-list-tile>
							<v-list-tile>
								<v-list-tile-title>Duplicate</v-list-tile-title>
							</v-list-tile>
							<v-list-tile @click="remove(task.id)">
								<v-list-tile-title>Delete</v-list-tile-title>
							</v-list-tile>
						</v-list>
					</v-menu>
				</v-card-title>
				<v-card-text class="pt-0">
					<p v-text="task.description"></p>
					<v-layout class="caption" align-center justify-center>
						<v-flex xs3>
							<v-icon>class</v-icon>
							<template v-if="false">
								{{task}}
							</template>
							<template v-else>No Classrooms</template>
						</v-flex>
						<v-flex xs3>
							<v-icon>timer_off</v-icon> {{task.closed_at|vmTimeAgo}}
						</v-flex>
						<v-flex xs3>
							<v-icon>{{task.public ? 'visibility' : 'visibility_off'}}</v-icon> {{task.public ? 'Visible' : 'Hidden'}}
						</v-flex>
						<v-flex xs3>
							<v-icon mdi>comment-question-outline</v-icon>
							{{task.questions_count}} Questions
						</v-flex>
					</v-layout>
				</v-card-text>

			</v-card>
			<v-divider></v-divider>
		</template>
	</div>
</template>
<style>
	.is-active * {
		color: #FFF !important;
	}
</style>
<script type="text/javascript">
  import _ from 'underscore'
  export default{
    name: 'tasks',
    data () {
      return {
        activeTask: null,
        taskFilters: {
          search: ''
        },
        tasks: {
          data: [],
          meta: {
            pagination: { current_page: 1 }
          }
        },
        TaskResource: this.$resource('assignments{/assignment}', {}),
        throttledSearchTask: _.throttle(this.getTasks, 800),
        lastSearchRequest: null,
      }
    },
    watch: {
      taskFilters: {
        handler (val) {
          this.throttledSearchTask()
        },
        deep: true
      },
    },
    methods: {
      isActiveTask (task) {
        return this.activeTask && this.activeTask.id === task.id
      },
      getTasks () {
        let params = {
          include: 'questions.answers,students,classrooms',
          per_page: 20
        }
        params = _.extend(params, this.taskFilters)
        return this.TaskResource.get(params, { before: function (xhr) {
          if (this.lastSearchRequest) {
            this.lastSearchRequest.abort()
          }
          this.lastSearchRequest = xhr;
        }}).then(response => {
          this.tasks = response.body
        }, this.$root.handleApiError)
      },
      setActiveTask (task) {
        this.activeTask = task
//        this.task.hover =''
        this.$root.$emit('set-active-task', task)
      },
      remove (id) {
        swal({
          title: 'Are you sure?',
          text: 'Once deleted, you will not be able to recover this assignment!',
          icon: 'warning',
          buttons: true,
          dangerMode: true
        }).then(willDelete => {
          if (willDelete) {
            this.TaskResource.delete({ assignment: id }).then(response => {
              this.tasks.data = _.reject(this.tasks.data, task => task.id === id)
              this.tasks.meta.pagination.count--
              this.tasks.meta.pagination.total--
              console.log(response)
            })
          }
        })
      }
    },
    mounted () {
      this.getTasks()
      this.$root.$on('Tasks:get', () => {
        this.getTasks()
      })
    }
  }
</script>
