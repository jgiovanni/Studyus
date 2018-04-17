<template>
	<div>
		<v-layout row wrap>
			<v-flex xs12 md6 v-for="(question, index) in questions.data">
				<v-card tile flat class="ma-3" v-if="questions.data.length" :key="question.id">
					<v-card-title>
						<v-btn dark icon class="primary white--text" disabled>
							#{{getPaginatedQuestionIndex(index)}}
						</v-btn>
						<div class="subheading">
							<!--<span class="caption">Question</span><br>-->
							<froalaView v-model="question.body"></froalaView>
						</div>
						<v-spacer></v-spacer>
						<v-tooltip left>
							<v-btn slot="activator" icon class="primary--text" @click.native.stop="selectQuestion(question, index)">
								<v-icon>mode_edit</v-icon>
							</v-btn>
							<span>Edit Question</span>
						</v-tooltip>

						<v-tooltip left>
							<v-btn slot="activator" icon class="red--text" @click.native.stop="confirmDeleteQuestion(question, index)">
								<v-icon>delete_forever</v-icon>
							</v-btn>
							<span>Delete Question</span>
						</v-tooltip>

					</v-card-title>
					<!--<v-divider></v-divider>-->
					<v-list dense>
						<v-subheader class="primary white--text">
							{{question.answers_count}} Answers
						</v-subheader>

						<v-list-tile v-for="answer in question.answers.data" :key="answer.id">
							<v-list-tile-action>
								<v-icon :class="{'green--text': answer.correct, 'red--text': !answer.correct}">{{answer.correct ? 'check' : 'close'}}</v-icon>
							</v-list-tile-action>
							<v-list-tile-content>
								<v-list-tile-title><froalaView v-model="answer.body"></froalaView></v-list-tile-title>
							</v-list-tile-content>
						</v-list-tile>
					</v-list>
				</v-card>
			</v-flex>
		</v-layout>

		<br>
		<v-layout align-center justify-center v-if="questions.meta.pagination.total_pages > 1">
			<v-pagination :length.number="questions.meta.pagination.total_pages" v-model="questions.meta.pagination.current_page" total-visible="3"></v-pagination>
		</v-layout>

		<v-dialog v-model="showQuestionDialog" @change.native="" lazy absolute fullscreen>
			<v-toolbar dark color="primary">
				<v-btn icon @click.native="showQuestionDialog = false" dark>
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title v-if="selectedQuestion">{{selectedQuestion.id ? 'Edit' : 'Create'}} Question #{{selectedQuestionIndex}}</v-toolbar-title>
				<v-spacer></v-spacer>
				<v-toolbar-items>
					<v-btn dark flat @click.native="saveQuestion">Save</v-btn>
				</v-toolbar-items>
			</v-toolbar>

			<v-card v-if="selectedQuestion">
				<!--<v-card-title>
					<v-btn icon class="" @click.native="showQuestionDialog = false">
						<v-icon>close</v-icon>
					</v-btn>
					<div class="subheading">
						<span class="headline">{{selectedQuestion.id ? 'Edit' : 'Create'}} Question #{{selectedQuestionIndex}}</span>
					</div>
				</v-card-title>-->
				<v-tabs dark fixed centered>
					<v-tabs-bar class="cyan">
						<v-tabs-slider class="yellow"></v-tabs-slider>
						<v-tabs-item href="#content">Question Content</v-tabs-item>
						<v-tabs-item href="#answers">Answers</v-tabs-item>
					</v-tabs-bar>
					<v-tabs-items>
						<v-tabs-content id="content">
							<froala :tag="'textarea'" :config="froalaConfig" v-model="selectedQuestion.body">Init text</froala>
						</v-tabs-content>
						<v-tabs-content id="answers">
							<v-card flat>
								<v-list>
									<v-subheader>
										{{selectedQuestion.answers_count}} Answers
									</v-subheader>
									<v-divider></v-divider>
									<template v-for="(answer, answerIndex) in selectedQuestion.answers.data">
										<v-card tile flat :key="answer.id">
											<v-card-text class="pb-0">
												<v-layout>
													<v-flex xs2 align-center justify-center>
														<v-switch v-model="answer.correct" color="success" hide-details @change="saveAnswer(answer)"></v-switch>
														{{ answer.correct ? 'Correct' : 'Incorrect' }}
													</v-flex>
													<v-flex xs10>
														<!--<froala :tag="'input'" :config="froalaConfig" v-model="selectedQuestion.body">Init text</froala>-->
														<v-text-field :label="'Answer #' + (answerIndex+1)" multi-line full-width :rows="1" required v-model="answer.body" @change="saveAnswer(answer)"></v-text-field>
														<v-divider></v-divider>
													</v-flex>
												</v-layout>
											</v-card-text>
											<v-card-actions>
												<v-spacer></v-spacer>
												<v-btn small flat @click.native.stop="duplicateAnswer(answer)">Duplicate <v-icon>content_copy</v-icon></v-btn>
												<v-btn small flat class="red--text" @click.native.stop="deleteAnswer(answer, answerIndex)">Delete <v-icon>delete_forever</v-icon></v-btn>
											</v-card-actions>
										</v-card>
										<v-divider></v-divider>
									</template>
									<v-btn block flat @click.native.stop="addAnswer">Add an answer</v-btn>
								</v-list>
							</v-card>
						</v-tabs-content>
					</v-tabs-items>
				</v-tabs>
				<!--<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn class="red&#45;&#45;text darken-1" flat @click.native="cancelQuestion">Cancel</v-btn>
					<v-btn class="green&#45;&#45;text darken-1" flat @click.native="saveQuestion">Save</v-btn>
				</v-card-actions>-->
			</v-card>
		</v-dialog>

		<v-dialog v-model="showDeleteDialog" lazy absolute persistent>
			<v-card>
				<v-card-title>
					<div class="headline">Delete Task</div>

				</v-card-title>
				<v-card-text v-if="selectedQuestion && selectedQuestionIndex !== null">
					<p>Are you sure you want to delete <br> Question #{{selectedQuestionIndex + 1}}?</p>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn class="green--text darken-1" flat @click.native="showDeleteDialog = false,selectedQuestion = null, selectedQuestionIndex = null">Nah</v-btn>
					<v-btn class="green--text darken-1" flat @click.native="deleteQuestion(selectedQuestion, selectedQuestionIndex)">Confirm</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style></style>
<script type="text/javascript">
  import _ from 'underscore'
  export default{
    name: 'task',
    props: {
      id: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        questions: {
          data: [],
          meta: {pagination: {current_page: 1}}
        },
        showQuestionDialog: false,
        showDeleteDialog: false,
        selectedQuestion: null,
        selectedQuestionIndex: null,
        QuestionResource: this.$resource('questions{/question}', {include: 'answers'}),
        AnswerResource: this.$resource('answers{/answer}'),
        froalaConfig: {
          events: {
            'froalaEditor.initialized': function (e, editor) {
//              console.log(e)
//              console.log(editor)
//              console.log('initialized')
            }
          },
          placeholderText: 'Write Question Here',
          charCounterCount: false,
          // options
          height: 200,
          toolbarButtons: ['fullscreen', 'bold', 'italic', 'underline', 'subscript', 'superscript', 'formatOL', 'formatUL', '|', 'undo', 'redo', '|', 'color', 'insertTable', 'insertImage', 'insertVideo', 'quote', '|', 'wirisEditor', 'wirisChemistry'],
          toolbarButtonsSM: ['fullscreen', 'bold', 'italic', 'underline', 'subscript', 'superscript', 'formatOL', 'formatUL', '|', 'undo', 'redo', '|', 'color', 'insertTable', 'insertImage', 'insertVideo', 'quote', '|', 'wirisEditor', 'wirisChemistry'],
          toolbarButtonsXS: ['bold', 'italic', 'underline', 'subscript', 'superscript', '|', 'undo', 'redo', '|', 'color', 'insertTable', 'insertImage', 'insertVideo', 'quote', '|', 'wirisEditor', 'wirisChemistry'],
          imageEditButtons: ['wirisEditor', 'wirisChemistry'],
          htmlAllowedTags: ['.*'],
          htmlAllowedAttrs: ['.*'],
        }
      }
    },
    watch: {
      'questions.meta.pagination.current_page' (val, oldVal) {
        if (val !== oldVal) this.getQuestions()
      }
    },
    methods: {
      QuestionFactory () {
        return {
          body: '',
          explanation: null,
          task_id: this.id,
          answers: {data: []}
        }
      },
      getPaginatedQuestionIndex (index) {
        if (this.questions.meta.pagination.current_page > 1) return (this.questions.meta.pagination.current_page * this.questions.meta.pagination.per_page - this.questions.meta.pagination.per_page) + 1 + index
        else return index + 1
      },
      getQuestions () {
        let params = {
          task: this.id,
          page: this.questions.meta.pagination.current_page,
          sort: 'created_at',
          per_page: 50
        }
        this.QuestionResource.get(params).then(response => {
          this.questions = response.body
        }, this.$root.handleApiError)
      },
      selectQuestion (question, index) {
        let obj = _.extend({}, question)
        this.selectedQuestion = obj
        this.selectedQuestionIndex = index + 1
        this.showQuestionDialog = true
      },
      addQuestion () {
        this.selectedQuestion = _.extend({}, this.QuestionFactory())
        this.selectedQuestionIndex = (this.questions.data.length + 1) || 1
        this.showQuestionDialog = true
      },
      duplicateQuestion (question) {
        let dup = _.extend({}, question)
        delete dup.id
        this.selectedQuestion = dup
        this.selectedQuestionIndex = (this.questions.data.length + 1) || 1
        this.showQuestionDialog = true
      },
      saveQuestion () {
        let promise
        let data = _.extend({}, this.selectedQuestion)
        delete data.answers_count
        if (this.selectedQuestion.id) {
          delete data.answers
          promise = this.QuestionResource.update({question: data.id}, data).then(response => {
            this.questions.data[this.selectedQuestionIndex - 1] = response.body.data
          })
        } else {
          data.answers = data.answers.data
          promise = this.QuestionResource.save({question: data.id}, data).then(response => {
            this.selectedQuestion = response.body.data
            this.questions.data.push(response.body.data)
            this.getQuestions()
          })
        }

        promise.then(() => {
          this.showQuestionDialog = false
          this.selectedQuestion = null
          this.selectedQuestionIndex = null
          this.$root.$emit('set-task-question_count', this.questions.data.length)
        }, this.$root.handleApiError)
      },
      confirmDeleteQuestion (question, index) {
        this.selectedQuestion = question
        this.selectedQuestionIndex = index
        this.showDeleteDialog = true
      },
      deleteQuestion (question, index) {
        if (question.id) {
          this.QuestionResource.delete({question: question.id}).then(() => {
            this.questions.data.splice(index, 1)
            this.$root.$emit('alert_user', {message: 'Question Deleted', type: 'info'})
            this.$root.$emit('set-task-question_count', this.questions.data.length)
            this.showDeleteDialog = false
          }, this.$root.handleApiError)
        } else {
          this.questions.data.splice(index, 1)
          this.$root.$emit('alert_user', {message: 'Question Deleted', type: 'info'})
          this.$root.$emit('set-task-question_count', this.questions.data.length)
          this.showDeleteDialog = false
        }
      },
      cancelQuestion () {
        this.showQuestionDialog = false
        this.selectedQuestion = null
        this.selectedQuestionIndex = null
      },
      addAnswer () {
        this.selectedQuestion.answers.data.push({
          question_id: this.selectedQuestion.id || undefined,
          correct: false,
          body: null
        })
        this.selectedQuestion.answers_count = this.selectedQuestion.answers.data.length
      },
      saveAnswer: _.debounce(function (answer) {
        let promise
        if (!answer.body || !answer.question_id) return
        if (answer.id) {
          promise = this.AnswerResource.update({answer: answer.id}, answer).then(response => {
            _.extend(answer, response.body.data)
          })
        } else {
          promise = this.AnswerResource.save({}, answer).then(response => {
            _.extend(answer, response.body.data)
          })
        }
        promise.then(() => {
          console.log('Answer Saved')
        }, this.$root.handleApiError)
      }, 800),
      duplicateAnswer (answer) {
        let newAnswer = _.extend({}, answer)
        delete newAnswer.id
        this.selectedQuestion.answers.data.push(newAnswer)
        this.selectedQuestion.answers_count = this.selectedQuestion.answers.data.length
      },
      deleteAnswer (answer, index) {
        if (answer.id) {
          this.AnswerResource.delete({answer: answer.id}).then(() => {
            this.selectedQuestion.answers.data.splice(index, 1)
            this.selectedQuestion.answers_count = this.selectedQuestion.answers.data.length
            this.$root.$emit('alert_user', {message: 'Answer Deleted Successfully', type: 'success'})
          }, this.$root.handleApiError)
        } else {
          this.selectedQuestion.answers.data.splice(index, 1)
          this.selectedQuestion.answers_count = this.selectedQuestion.answers.data.length
          this.$root.$emit('alert_user', {message: 'Answer Deleted Successfully', type: 'success'})
        }
      },
    },
    mounted () {
      this.getQuestions()
      this.$root.$on('task-create-question', this.addQuestion)
    }
  }
</script>
