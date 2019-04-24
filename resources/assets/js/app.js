
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap-p');

//window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});*/

$(document).ready(function() {
    console.log(window.Laravel.userId);
    if(window.Laravel.userId) {
        console.log('here');
        chan = 'App.User.'+window.Laravel.userId;
        console.log(chan);
        window.Echo.private(chan)
            .notification((notification) => {
                console.log("Hello");
                console.log(notification);
                console.log((notification.projectRequest.requestable_type == "App\\NewProjectRequest" ? newRouteLink : optRouteLink) + notification.projectRequest.id);
                $('#notifications-container').prepend('' + 
                '<li>' +
                    '<a href="' + (notification.projectRequest.requestable_type == "App\\NewProjectRequest" ? newRouteLink : optRouteLink) + notification.projectRequest.id +'">' +
                        notification.projectRequest.id +
                    '</a>' +
                '</li>' + 
                '');

                outerBadge = $('#notifications-outer-badge');
                outerBadge.html(+outerBadge.html() + 1);

                innerBadge = $('#notifications-inner-badge');
                innerBadge.html(+innerBadge.html() + 1);
            });
    }
});
