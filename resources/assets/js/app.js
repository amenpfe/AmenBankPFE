
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
                
                badgeContainer = $('#badge-container');
                console.log("Hello");
                console.log(notification);
                console.log((notification.projectRequest.requestable_type == "App\\NewProjectRequest" ? newRouteLink : optRouteLink) + notification.projectRequest.id);
                
                $.ajax('/getRequest/'+notification.projectRequest.id, {
                        success: function (request, status, xhr) {// success callback function
                            var notificationContainer = $('#notifications-container');
                            var notificationHTML = '<li><a href="';
                            if(notification.projectRequest.requestable_type == "App\\NewProjectRequest") {
                                if(request.status == 13) {
                                    var link = newRouteLink.slice(0, newRouteLink.length-1) + "p" + newRouteLink.slice(newRouteLink.length-1, newRouteLink.length) + notification.projectRequest.id;
                                    notificationHTML += link;
                                } else if(request.status == 14) {
                                    console.log(request.status + "new");
                                    var link = newRouteLink.slice(0, newRouteLink.length-1) + "d" + newRouteLink.slice(newRouteLink.length-1, newRouteLink.length) + notification.projectRequest.id;
                                    console.log(link);
                                    notificationHTML += link;
                                }else {
                                    notificationHTML += newRouteLink + notification.projectRequest.id;
                                }
                                notificationHTML += '">Demande de nouveau projet';
                            }else {
                                if(request.status == 13) {
                                    var link = optRouteLink.slice(0, optRouteLink.length-1) + "p" + optRouteLink.slice(optRouteLink.length-1, optRouteLink.length) + notification.projectRequest.id;
                                    notificationHTML += link;
                                } else if(request.status == 14) {
                                    console.log(request.status + "opt");
                                    var link = optRouteLink.slice(0, optRouteLink.length-1) + "d" + optRouteLink.slice(optRouteLink.length-1, optRouteLink.length) + notification.projectRequest.id;
                                    console.log(link);
                                    notificationHTML += link;
                                }else {
                                    notificationHTML += optRouteLink + notification.projectRequest.id;
                                }
                                notificationHTML += '">Demande d\'amélioration';
                            }
                            notificationHTML += '<span class="message">' + notification.projectRequest.created_at + '</span></a></li>';
                            console.log("Notification HTML: " + notificationHTML);
                            notificationContainer.prepend(notificationHTML);
                        }
                });
                
                /*$('#notifications-container').prepend('' + 
                '<li>' +
                    '<a href="' + (notification.projectRequest.requestable_type == "App\\NewProjectRequest" ? newRouteLink : optRouteLink) + notification.projectRequest.id +'">' +
                    (notification.projectRequest.requestable_type == "App\\NewProjectRequest" ? "Demande de nouveau projet" : "Demande d'amélioration") +
                    '<span class="message">' + notification.projectRequest.created_at + '</span>' +
                    '</a>' +
                '</li>' + 
                '');*/


                outerBadge = $('#notifications-outer-badge');
                outerBadge.html(+outerBadge.html() + 1);
                outerBadge.show();

                innerBadge = $('#notifications-inner-badge');
                innerBadge.html(+innerBadge.html() + 1);
            });
    }
});
