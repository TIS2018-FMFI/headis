
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('vue-challenge', require('./components/Challenge.vue'));
Vue.component('match-component', require('./components/Match.vue'));
Vue.component('field-error', require('./components/FieldError.vue'));
Vue.component('vue-datetime-picker', VueCtkDateTimePicker);
Vue.component('vue-manager', require('./components/Manager.vue'));
Vue.component('edit-post', require('./components/EditPost.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

import {Form} from './Form.js';
import {Errors} from './Errors.js';

Vue.use(require('vue-moment'));

Vue.use( CKEditor );

import fontawesome  from '@fortawesome/fontawesome';
import solid from '@fortawesome/fontawesome-free-solid';
import regular from '@fortawesome/fontawesome-free-regular';
import { faFacebookF }  from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

fontawesome.library.add(solid, regular, faFacebookF);

Vue.component('font-awesome-icon', FontAwesomeIcon);

import VueScrollactive from 'vue-scrollactive';
Vue.use(VueScrollactive);

import VueCropper from 'vue-cropperjs';
Vue.component(VueCropper);


 /**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
