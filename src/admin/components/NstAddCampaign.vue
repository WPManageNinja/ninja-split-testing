<template>
	<div>
		<h2>Add Campaign</h2>
	</div>
</template>

<script type="text/babel">
	export default {
		name: 'NstAddCampaign',
		mounted() {
			this.componentEmit('navIndexing')
		}
	}
</script>