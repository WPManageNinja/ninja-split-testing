<template>
	<div>
		<h2>Help</h2>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstHelp',
		mounted() {
			this.componentEmit('navIndexing')
		}
	}
</script>