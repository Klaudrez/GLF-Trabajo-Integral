import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import moment from 'moment';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        log: "",
    },
    mutations: {
        writeLog: (state, { level, message }) => {
            var today = moment().format("YYYY-MM-DD HH:mm:ss");
            var newlog = today + " [" + level + "]: " + message;
            axios({
                method: "post",
                url: window.location.origin + window.location.pathname + "/api.php",
                data: { newlog: newlog },
            }).catch(err => console.log(err));
            state.log = state.log + newlog + "\n";
        },
    },
    getters: {
        getLog: state => {
            return state.log;
        },
    },
    actions: {},
    modules: {}
});