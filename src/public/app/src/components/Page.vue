<template>
    <div id="content-container">
        <div id="content" class="container-fluid mx-0 p-3 p-sm-4">
            <slot></slot>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Page",
        data() {
            return {
                pluginName: "NACHA Generator",
                githubRepo: "https://github.com/ucrm-plugins/nacha-generator"
            }
        }
    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

    #content-container {
        flex: auto;
        overflow-y: hidden;
    }

    #content {
        overflow-y: auto;
    }

</style>
