/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';
import VueResource from 'vue-resource';
Vue.use(VueResource);

// intercepting requests and responses
Vue.http.interceptors.push((request, next) => {
    let token = document.cookie.split(';').find(indice => {
        return indice.includes('token=');
    });

    token = token.split('=')[1];
    token = 'Bearer ' + token;

    request.headers.set('Authorization', token);
    request.headers.set('Accept', 'application/json');

    next(function(response){
        if(response.status == 401 && response.body.message == 'Token has expired'){
            axios.post('http://localhost:8000/api/refresh')
                .then(response => {
                    document.cookie = 'token=' + response.data.token;
                    window.location.reload();
                });
        }
    });
});

import Vuex from 'vuex';
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        item: {},
        transaction: {
            status: '',
            message: '',
            data: ''
        }
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('login-component', require('./components/Login.vue').default);
Vue.component('register-component', require('./components/Register.vue').default);
Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('notes-component', require('./components/Notes.vue').default);
Vue.component('modal-component', require('./components/Modal.vue').default);
Vue.component('card-component', require('./components/Card.vue').default);
Vue.component('alert-component', require('./components/Alert.vue').default);
Vue.component('search-component', require('./components/Search.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading'));

Vue.filter('formatDateGlobal', function(d){
    if(!d) return '';

    d = d.split('T');

    let date = d[0];
    let time = d[1];

    // format date
    date = date.split('-');
    date = date[2] + '/' + date[1] + '/' + date[0];

    // format time
    time = time.split('.');
    time = time[0];

    return date + ' ' + time;
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
