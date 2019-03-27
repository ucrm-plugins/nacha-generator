import Vue from "vue";
import Router from "vue-router";
import Create from "../components/views/Create";

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: "/",
            name: "Create",
            component: Create
        }
    ]
});
