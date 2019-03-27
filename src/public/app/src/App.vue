<template>
    <div id="app" class="d-flex flex-column">
        <Header />
        <Content>
            <router-view/>
        </Content>
    </div>
</template>

<script>

import Header from "./components/Header";
import Content from "./components/Content";

export default {
    name: "App",
    components: {
        Header,
        Content
    }
}

</script>

<style>

* {
    font-family: Lato, "Helvetica Neue", Helvetica, Arial, sans-serif;
}

html, body {
    height: 100vh;
    position: relative;
    background-color: #edf0f3;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

#app {
    height: 100%;
    overflow-y: hidden;
}

</style>
