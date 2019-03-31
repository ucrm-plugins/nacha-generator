import Vue from "vue";
import Router from "vue-router";
import Generate from "../components/views/Generate";

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: "/",
            name: "Home",
            redirect: "generate"
        },
        {
            path: "/generate",
            name: "Generate",
            component: Generate
        }
    ]
});
