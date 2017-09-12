var baseUrl = 'http://ontrack.dev/';
var linksReady = false;

function home() {
    return $.ajax({
        url: '/api/v1/users/islogged.json',
        type: 'get'
    }).done(function (responseData) {
        loadHeader().then(loadSidebar).then(events);
    }).fail(function () {
        $('#actions-sidebar').hide();
        loadHeader().then(loadWelcome).then(function () {
            window.history.pushState('home', "OnTrack", '/');
        });
    });
}

function logout() {
    $.get('/users/logout', function () {
        home();
    });
}

function loadHeader() {
    $('.top-bar').show();
    return loadElement('header', '.top-bar');
}

function loadSidebar() {

    $('#actions-sidebar').show();
    return loadElement('sidebar', '#actions-sidebar');
}

function loadWelcome() {
    return loadElement('welcome', '#main');
}

function events(from, to) {
    loadPage('/events', '#main', 'events').then();
}

function newEvent() {
    loadPage('/events/add', '#main', 'newEvent');
}

function viewEvent(id) {
    loadPage('/events/view/' + id, '#main', 'events');
}

function editEvent(id) {
    loadPage('/events/edit/' + id, '#main', 'events').then(function () {
        prepareEditEventForm(id)
    });
}

function deleteEvent(id) {
    var confirm = window.confirm('Are you sure?');

    if (confirm) {
        return $.ajax({
            url: '/api/v1/events/' + id + '.json',
            type: 'delete'
        }).done(function (responseData) {
            loadPage(window.location.href, '#main', window.location.href);
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    }
}

function dates() {
    loadPage('/events/dates', '#main', 'dates');
}

function report() {
    loadPage('/events/report', '#main', 'report');
}

function users() {
    loadPage('/users', '#main', 'users');
}

function invite() {
    loadPage('/users/invite', '#main', 'invite').then(prepareInviteForm);
}

function newUser() {
    loadPage('/users/add', '#main', 'newUser').then(prepareNewUserForm);
}

function viewUser(id) {
    loadPage('/users/view/' + id, '#main', 'users');
}

function editUser(id) {
    loadPage('/users/edit/' + id, '#main', 'users').then(function () {
        prepareEditUserForm(id)
    });
}

function deleteUser(id) {
    var confirm = window.confirm('Are you sure?');

    if (confirm) {
        return $.ajax({
            url: '/api/v1/users/' + id + '.json',
            type: 'delete'
        }).done(function (responseData) {
            loadPage(window.location.href, '#main', window.location.href);
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    }
}

function loadElement(elementName, selector) {
    return $.ajax({
        url: '/elements/display/' + elementName,
        type: 'get'
    }).done(function (responseData) {
        $(selector).empty();
        $(selector).append(responseData);
        preapareLinks();
    }).fail(function (responseData) {
        alert(responseData.responseJSON.message);
    });
}

function back(state) {
    window[state]();
}

function loadPage(url, selector, caller) {
    return $.ajax({
        url: url,
        type: 'get'
    }).done(function (responseData) {
        if (window.location.href != baseUrl + url) {
            window.history.pushState(caller, "OnTrack", url);
        }
        $(selector).empty();
        $(selector).append(responseData);
        preapareLinks();
    }).fail(function (responseData) {
        alert(responseData.responseJSON.message);
    });
}

function loadRecaptcha() {
    grecaptcha.render('ontrack-capthca', {
        'sitekey': '6LfVMS0UAAAAAMY4aIquS5dKE9NY3rUuizivCYSg'
    });
}

function login() {

    $('#actions-sidebar').hide();
    $('.top-bar').hide();

    loadPage('/login', '#main', 'login').then(prepareLoginForm);
}

function prepareLoginForm() {
    loadRecaptcha();
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/login',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            home();
            events();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareInviteForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/users/invite.json',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            users();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareResetForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/users/reset',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            login();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareForgotForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/users/forgot',
            type: 'post',
            data: $('form').serialize(),
        }).success(function (responseData) {
            alert(responseData.message);
            login();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareRegisterForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/users/register',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            login();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}


function prepareNewEventForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/api/v1/events.json',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            events();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareEditEventForm(id) {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/api/v1/events/' + id + '.json',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            events();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareNewUserForm() {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/api/v1/users.json',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            users();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function prepareEditUserForm(id) {
    $('form').off();
    $('form').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '/api/v1/users/' + id + '.json',
            type: 'post',
            data: $('form').serialize(),

        }).success(function (responseData) {
            users();
        }).fail(function (responseData) {
            alert(responseData.responseJSON.message);
        });
    });
}

function postinput() {
    $('#accomplish').empty();
    var from = new Date($("#from-data").val());
    var to = new Date($("#to-data").val());

    var monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    var fromDate = monthNames[from.getMonth()] + ' ' + from.getDate() + ", " + from.getFullYear();
    var toDate = monthNames[to.getMonth()] + ' ' + to.getDate() + ", " + to.getFullYear();

    if (fromDate == toDate) {
        $('.events h3').text(fromDate);
    }
    else {
        $('.events h3').text(fromDate + ' - ' + toDate);
    }

    var url = 'events?from=' + encodeURIComponent($("#from-data").val()) + '&to=' + encodeURIComponent($("#to-data").val());
    return loadPage(url, '#main', 'events');
}


function forgot() {
    $('#actions-sidebar').hide();
    $('.top-bar').hide();

    loadPage('/forgot', '#main', 'forgot').then(prepareForgotForm);
}

function prepareForms() {
    if (window.location.href == baseUrl + 'login') {
        //prepareLoginForm();
    }
    if (window.location.href == baseUrl + 'users/login') {
        //prepareLoginForm();
    }
    else if (window.location.href == baseUrl + 'forgot') {
        prepareForgotForm();
    }
    else if (window.location.href == baseUrl + 'events/add') {
        prepareNewEventForm();
    }
    else if (window.location.href == baseUrl + 'users/add') {
        prepareNewUserForm()
    }
    else if (window.location.href == baseUrl + 'users/invite') {
        prepareInviteForm();
    }
    else if (window.location.href.indexOf('users/edit') !== -1) {
        var start = window.location.href.indexOf('users/edit') + 'users/edit'.length;
        var id = window.location.href.substring(start + 1);
        //prepareEditUserForm(id);
    }
    else if (window.location.href.indexOf('events/edit') !== -1) {
        var start = window.location.href.indexOf('events/edit') + 'events/edit'.length;
        var id = window.location.href.substring(start + 1);
        prepareEditEventForm(id);
    }
}

function preapareLinks() {
    $('a').off();
    $('a').on('click', function (event) {
        event.preventDefault();
        if (this.href == baseUrl + 'login') {
            login();
        }
        else if (this.href == baseUrl + 'forgot') {
            forgot();
        }
        else if (this.href == baseUrl + 'events') {
            events();
        }
        else if (this.href == baseUrl + 'events/add') {
            newEvent();
        }
        else if (this.href == baseUrl + 'events/dates') {
            dates();
        }

        else if (this.href == baseUrl + 'events/report') {
            report();
        }
        else if (this.href == baseUrl + 'users') {
            users();
        }
        else if (this.href == baseUrl + 'users/add') {
            newUser();
        }
        else if (this.href == baseUrl + 'users/logout') {
            logout();
        }
        else if (this.href == baseUrl + 'logout') {
            logout();
        }
        else if (this.href == baseUrl + 'users/invite') {
            invite();
        }
        else if (this.onclick == undefined || this.onclick == null) {
            loadPage(this.href, '#main', 'home')
        }
    });

    prepareForms();


    $("#from-data").off();
    $("#to-data").off();
    $("#from-data").on('change', postinput);
    $("#to-data").on('change', postinput);
}

$(document).ready(function () {
    preapareLinks();
});
