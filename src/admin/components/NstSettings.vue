<template>
	<div>
		<h2>Settings</h2>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstSettings',
		methods: {
		},
		mounted() {
			this.componentEmit('navIndexing')
		}
	}
</script>