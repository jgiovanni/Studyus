<template>
	<v-btn :class="classes" @click.native.stop="execute" :small="size === 'small'" :large="size === 'large'" :icon="!!icon && !text" :dark="dark" :fab="fab">
		{{text}}
		<v-icon v-if="icon">{{icon}}</v-icon>
	</v-btn>
</template>
<style></style>
<script type="text/javascript">
  export default{
    name: 'action-button',
    props: {
      text: {
        type: String,
        default: null
      },
      event: {
        type: String,
        required: true
      },
      classes: {
        type: String,
        default: null
      },
      icon: {
        type: String,
        default: null
      },
      size: {
        type: String,
        default: null
      },
      type: {
        type: String,
        default: 'btn-primary'
      },
      dark: {
        type: Boolean,
        default: false
      },
      fab: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {}
    },
    methods: {
      execute () {
        this.$root.$emit(this.event)
      }

    },
    mounted () {

    }
  }
</script>
