(function () {
    angular.module("clientReloadHistoyModule", ['ui-notification', 'ui.bootstrap']);
    angular.module("clientReloadHistoyModule")
            .controller("clientReloadHistoyController", function ($http, $scope) {
                $scope.clients = [];
                $scope.clientReloadSmsHistory = [];
                $scope.allClientReloadSmsHistory = [];

                $scope.client = function (index_no) {
                    var data = "";
                    angular.forEach($scope.clients, function (values) {
                        if (values.index_no === index_no) {
                            data = values;
                            return;
                        }
                    });
                    return data;
                };

                $scope.init = function () {
                    //get client sms amount history
                    $http.post('app/php-functions/clent-leger.php?action=f4')
                            .success(function (data) {
                                $scope.clientReloadSmsHistory = data;
                            })
                            .error(function (data) {
                                console.log('error');
                            });
                            
                    //get client sms amount history
                    $http.post('app/php-functions/clent-leger.php?action=f5')
                            .success(function (data) {
                                console.log(data);
                                $scope.allClientReloadSmsHistory = data;
                            })
                            .error(function (data) {
                                console.log('error');
                            });

                    $http.get('app/php-functions/client.php?action=f2').success(function (data) {
                        $scope.clients = data;
                    });
                };
                $scope.init();
            });
}());
