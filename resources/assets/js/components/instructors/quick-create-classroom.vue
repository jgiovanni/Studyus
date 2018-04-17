<template>
	<div>
		<v-dialog v-model="dialog" lazy persistent width="800px">
			<v-btn absolute dark fab bottom right class="orange" slot="activator" v-show="isInstructorsClassrooms">
				<v-icon>add</v-icon>
			</v-btn>
			<v-card>
				<v-card-title>
					<div class="headline">Create Classroom</div>
				</v-card-title>
				<v-card-text>
					<v-text-field label="Name" persistent-hint hint="Give this classroom a descriptive name"
					              v-model="newClassroom.name"
					              :error-messages="errors.collect('name')"
					              v-validate="'required|max:100'"
					              data-vv-name="name" required></v-text-field>
					<v-layout row wrap>
						<v-flex xs12 sm5>
							<v-select
									:items="grades" chips tags
									v-model="newClassroom.grades"
									label="Select Grades"
									persistent-hint hint="Grade levels in this classroom"
									class=""
									:error-messages="errors.collect('grades')"
									v-validate="'required|min:1'"
									data-vv-name="grades" required></v-select>
						</v-flex>
						<v-flex xs12 sm2>
							<v-spacer></v-spacer>
						</v-flex>
						<v-flex xs12 sm5>
							<v-select
									:items="subjects" autocomplete
									v-model="subjectSelect"
									label="Select Subject"
									persistent-hint hint="What Subject for this classroom"
									class=""
									:error-messages="errors.collect('subject')"
									v-validate="'required|min:1'"
									data-vv-name="subject" required></v-select>

							<v-text-field v-if="subjectSelect === 'Other'" label="Enter Subject" persistent-hint hint="What Subject for this classroom"
							              v-model="newClassroom.subject"
							              :error-messages="errors.collect('other-subject')"
							              v-validate="'required|max:100'"
							              data-vv-name="other-subject" required></v-text-field>
						</v-flex>
					</v-layout>
					<!--<v-text-field label="Description" multi-line :rows="2" v-model="newClassroom.description"></v-text-field>-->
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn class="secondary--text darken-1" flat @click.native="dialog = false">Cancel</v-btn>
					<v-btn class="green--text darken-1" flat @click.native="create">Create</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore'
  let grades = []
  for (let i = 1; i < 13; i++) {
    grades.push(i)
  }

  export default {
    name: 'quick-create-classroom',
    data () {
      return {
        dialog: false,
        newClassroom: {
          name: null,
//          description: null,
          grades: [],
          subject: '',
          instructors: [{ permissions: 'manage', user_id: this.$root.user.id }]
        },
        subjectSelect: '',
        grades: ['Pre-K', 'Kindergarten', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'],
        subjects: ['Math', 'Science', 'Social Studies', 'Physics', 'Chemistry', 'Biology', 'Health & Medicine', 'Engineering', 'History', 'Art', 'English Literature', 'Language', 'Other'],
        ClassroomResource: this.$resource('classrooms{/classroom}')

      }
    },
    watch: {
      subjectSelect (val) {
        if (val === 'Other') {

        } else {
          this.newClassroom.subject = val
        }
      }
    },
    methods: {
      cancel () {
        this.newClassroom = {
          name: null,
          grades: [],
          subject: '',
          instructors: [{ permissions: 'manage', user_id: this.$root.user.id }]
        }
        this.dialog = false
      },
      create () {
        this.$validator.validateAll().then(result => {
          if (!result) {
            return false
          }
          // format data for API
          let data = _.extend({}, this.newClassroom)
          // send data
          this.ClassroomResource.save(data, { params: { include: 'instructors' } }).then(response => {
            swal({
              title: 'Awesome!',
              text: 'Classroom created!',
              icon: 'success',
              buttons: {
                open: { text: 'View Classroom', value: 'goto' },
                confirm: { text: 'Done', value: 'done' }
              }
            }).then(value => {
              switch (value) {
                case 'goto':
                  location.pathname = '/app/classrooms/' + response.body.data.url_id
                  break
                case 'done':
                  this.cancel()
                  this.$root.$emit('Classrooms:get')
                  break
              }
            })
          }, this.$root.handleApiError)
        })
      }

    },
    mounted () {

    }
  }
</script>