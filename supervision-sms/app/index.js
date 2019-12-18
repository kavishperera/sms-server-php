(function () {
    //create new module
    angular.module("appModule",
            ["ngRoute",
                "ngCookies",
                "clientModule",
                "clientReloadModule",
                "clientReloadHistoyModule",
                "clientMessageHistoyModule",
                "ui.bootstrap"
            ]);

    //create controller for module
    angular.module("appModule")
            .config(function ($routeProvider) {
                $routeProvider
                        .when('/manage-client', {
                            templateUrl: 'app/master/client/client.html',
                            controller: 'clientController'
                        })
                        .when('/client-sms-reload', {
                            templateUrl: 'app/transaction/client-reloard/client-reloard.html',
                            controller: 'clientReloadController'
                        })
                        .when('/client-sms-reload-history', {
                            templateUrl: 'app/transaction/client-reloard-history/client-reloard-history.html',
                            controller: 'clientReloadHistoyController'
                        })
                        .when('/client-sms-mesage-history', {
                            templateUrl: 'app/transaction/client-sms-mesage-history/client-sms-mesage-history.html',
                            controller: 'clientMessageHistoyController'
                        })
                        .when('/reports', {
                            templateUrl: 'view.html',
                            controller: ''
                        })
                        .when('/read-file', {
                            templateUrl: 'app/read/read_file.html',
                            controller: ''
                        })
                        .when('/login', {
                            templateUrl: 'app/system/login/login.html',
                            controller: 'LoginController'
                        })
                        .otherwise({
                            redirectTo: '/login'
                        });
            });

    angular.module("appModule")
            .run(function ($rootScope, $location, $cookieStore, $http) {
                // keep user logged in after page refresh
                $rootScope.globals = $cookieStore.get('globals') || {};
                if ($rootScope.globals.currentUser) {
                    $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
                }


                $rootScope.$on('$locationChangeStart', function (event, next, current) {
                    // redirect to login page if not logged in
                    var loggedIn = $rootScope.globals.currentUser;
                    if ($location.path() !== '/login' && !loggedIn) {
                        $rootScope.loginStatus = false;
                        $location.path('/login');
                    } else {
                        $rootScope.loginStatus = true;
                    }
                    
                    // redirect to login page if not logged in and trying to access a restricted page
//                    var restrictedPage = $.inArray($location.path(), ['/login', '/']) === -1;
//                    var loggedIn = $rootScope.globals.currentUser;
//                    if (restrictedPage && !loggedIn) {
//                        $location.path('/');
//                    }
                });
            });

}());