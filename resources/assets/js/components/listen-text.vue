<template>
	<span>{{finalText}}</span>
</template>
<style></style>
<script type="text/javascript">
  export default{
    name: 'listen-text',
    props: {
      text: {
        type: String,
        default: ''
      },
      event: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        finalText: ''
      }
    },
    methods: {},
    mounted () {
      this.finalText = this.text
      this.$root.$on(this.event, function (val) {
        this.finalText = val
      }.bind(this))
    }
  }
</script>
