(function () {
    'use strict';
    angular.module("appModule")
            .controller("LoginController", function ($scope, $rootScope, Notification, $location, AuthenticationService) {
                // reset login status
                AuthenticationService.ClearCredentials();

                $scope.login = function () {
                    AuthenticationService.Login($scope.username, $scope.password, function (response) {
                        if (response) {
                            AuthenticationService.SetCredentials($scope.username, $scope.password);
                            $rootScope.user = $scope.username;
                            $location.path('/client-sms-reload-history');
                        } else {
                            Notification.error('Username or password is incorrect');
                        }
                    });
                };

            });
}());
