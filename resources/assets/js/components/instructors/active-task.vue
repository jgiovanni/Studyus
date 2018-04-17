    <template>
	<div>
		<v-list v-if="task" class="white">
			<v-dialog v-model="showQuestionDialog" @change.native="" lazy absolute width="600px">
				<v-list-tile slot="activator" @click.native="selectedQuestion = question" avatar v-for="question in task.questions.data" :key="question.id">
					<v-list-tile-avatar>
						<v-icon mdi>comment-question-outline</v-icon>
					</v-list-tile-avatar>
					<v-list-tile-content>
						<v-list-tile-title><froalaView v-model="question.body"></froalaView></v-list-tile-title>
						<v-list-tile-sub-title>{{ question.answers_count }} answers</v-list-tile-sub-title>
					</v-list-tile-content>
				</v-list-tile>

				<v-card v-if="selectedQuestion">
					<v-card-title>
						<v-btn icon class="" @click.native="showQuestionDialog = false">
							<v-icon>close</v-icon>
						</v-btn>
						<div class="subheading">
							<span class="caption">Question</span><br>
							<froalaView v-model="selectedQuestion.body"></froalaView>
						</div>
					</v-card-title>
					<v-card-text>Let Google help apps determine location. This means sending anonymous location data to Google, even when no apps are running.</v-card-text>
					<v-list>
						<v-subheader>{{selectedQuestion.answers_count}} Answers</v-subheader>
						<v-list-tile v-for="answer in selectedQuestion.answers.data" :key="answer.id">
							<v-list-tile-action>
								<v-icon :class="{'green--text': answer.correct, 'red--text': !answer.correct}">{{answer.correct ? 'check' : 'close'}}</v-icon>
							</v-list-tile-action>
							<v-list-tile-content>
								<v-list-tile-title>{{answer.body}}</v-list-tile-title>
							</v-list-tile-content>
						</v-list-tile>
					</v-list>
					<v-card-actions>
						<v-spacer></v-spacer>
						<!--<v-btn class="green&#45;&#45;text darken-1" flat="flat" @click.native="showQuestionDialog = false">Disagree</v-btn>-->
						<v-btn class="green--text darken-1" flat="flat" @click.native="showQuestionDialog = false">Close</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</v-list>
	</div>
</template>
<style></style>
<script type="text/javascript">
  export default{
    name: 'active-task',
    data () {
      return {
        task: null,
        selectedQuestion: null,
        showQuestionDialog: false,
      }
    },
    methods: {},
    created () {
      let self = this
      this.$root.$on('set-active-task', task => {
        self.task = task
      })
    },
    mounted () {

    }
  }
</script>