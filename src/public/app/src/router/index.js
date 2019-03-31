import Vue from "vue";
import Router from "vue-router";
import Generate from "../components/views/Generate";
import History from "../components/views/History";
import Settings from "../components/views/Settings";

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
        },
        {
            path: "/history",
            name: "History",
            component: History
        },
        {
            path: "/settings",
            name: "Settings",
            component: Settings
        }
    ]
});
