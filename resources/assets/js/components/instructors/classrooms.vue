<template>
	<div>
		<v-toolbar class="white" flat style="z-index: 1">
			<v-text-field v-model="classroomFilters.search" prepend-icon="search" hide-details single-line
			              placeholder="Search Classrooms"></v-text-field>
			<v-btn icon>
				<v-icon>filter_list</v-icon>
			</v-btn>
		</v-toolbar>
		<template v-for="(classroom, index) in classrooms.data">
			<v-card tile flat class="my-3" :class="classroom.hover" :key="classroom.id"
		        @mouseover.native="classroom.hover ='light-blue is-active'" @mouseout.native="classroom.hover = ''">
			<v-card-title>
				<h6 class="ma-0">
					<a :href="`/app/classrooms/${classroom.url_id}`">{{classroom.name}}</a>
					<v-chip small class="darken-4" v-for="grade in classroom.grades" color="secondary" text-color="white">
						{{grade}}
					</v-chip>
					<v-chip small color="orange" text-color="white">
						<!--<v-avatar><v-icon>account_circle</v-icon></v-avatar>-->
						{{classroom.subject}}
					</v-chip>
				</h6>
				<v-spacer></v-spacer>
				<v-menu bottom left>
					<v-btn icon slot="activator" ripple>
						<v-icon class="primary--text">more_horiz</v-icon>
					</v-btn>
					<v-list>
						<v-list-tile :href="`/app/classrooms/${classroom.url_id}`">
							<v-list-tile-title>View</v-list-tile-title>
						</v-list-tile>
						<v-list-tile @click="remove(classroom.id)">
							<v-list-tile-title>Delete</v-list-tile-title>
						</v-list-tile>
					</v-list>
				</v-menu>
			</v-card-title>
			<v-card-text>
				<v-layout class="subheading">
					<v-flex xs4>
						<v-icon>contacts</v-icon>&nbsp;{{classroom.students_count}} Students
					</v-flex>
					<v-flex xs4>
					</v-flex>
					<v-flex xs4>
					</v-flex>
				</v-layout>
			</v-card-text>
		</v-card>
			<v-divider></v-divider>
		</template>
	</div>
</template>
<style></style>
<script type="text/javascript">
  export default{
    name: 'classrooms',
    data () {
      return {
        classroomFilters: {
          search: ''
        },
        lastSearchRequest: null,
        classrooms: {
          data: [],
          meta: {
            pagination: { current_page: 1 }
          }
        },
        ClassroomResource: this.$resource('classrooms{/classroom}', {}),
        throttledSearchClassroom: _.throttle(this.getClassrooms, 800),

      }
    },
    watch: {
      classroomFilters: {
        handler (val) { return this.throttledSearchClassroom() },
        deep: true
      }
    },
    methods: {
      getClassrooms () {
        let params = {
          instructors: this.$root.user.id,
          include: 'instructors',
          per_page: 20
        }
        params = _.extend(params, this.classroomFilters)
        return this.ClassroomResource.get(params, { before: function (xhr) {
          if (this.lastSearchRequest) {
            this.lastSearchRequest.abort()
          }
          this.lastSearchRequest = xhr;
        }}).then(response => {
          this.classrooms = response.body
        }, this.$root.handleApiError)
      },
      remove (id) {
        swal({
          title: 'Are you sure?',
          text: 'Once deleted, you will not be able to recover this classroom!',
          icon: 'warning',
          buttons: true,
          dangerMode: true
        }).then(willDelete => {
          if (willDelete) {
            this.ClassroomResource.delete({ assignment: id }).then(response => {
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
      this.getClassrooms()
      this.$root.$on('Classrooms:get', () => {
        this.getClassrooms()
      })
    }
  }
</script>
