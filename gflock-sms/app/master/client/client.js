(function () {
    angular.module("clientModule", ['ui-notification', 'ui.bootstrap']);
    angular.module("clientModule")
            .controller("clientController", function ($http, $scope, Notification) {
                $scope.clients = [];
                $scope.clientTempData = {
                    "index_no": null,
                    "name": null,
                    "user_name": null,
                    "password": null,
                    "api_key": null,
                    "sender_id": null,
                    "status": null
                };

                $scope.validate = function () {
                    if (!$scope.clientTempData.name) {
                        Notification.error("enter client name");
                        return false;
                    } else if (!$scope.clientTempData.user_name) {
                        Notification.error("enter client user name");
                        return false;
                    } else if (!$scope.clientTempData.password) {
                        Notification.error("enter client password");
                        return false;
                    } else if (!$scope.clientTempData.sender_id) {
                        Notification.error("enter client sender id");
                        return false;
                    } else if ($scope.clientTempData.name
                            && $scope.clientTempData.user_name
                            && $scope.clientTempData.sender_id
                            && $scope.clientTempData.password) {
                        return true;
                    }
                };

                $scope.clear = function () {
                    $scope.clientTempData = {};
                };

                $scope.editClient = function (client) {
                    $scope.clientTempData = {};
                    var id = -1;
                    for (var i = 0; i < $scope.clients.length; i++) {
                        if ($scope.clients[i].index_no === client.index_no) {
                            id = i;
                        }
                    }
                    $scope.clients.splice(id, 1);
                    $scope.clientTempData = client;
                };

                $scope.updateClient = function () {
                    if ($scope.validate()) {
                        $http.post('app/php-functions/client.php?action=f3', $scope.clientTempData)
                                .success(function (data)
                                {
                                    $scope.clients.push($scope.clientTempData);
                                    $scope.clear();
                                    Notification.success("client update success");
                                })
                                .error(function (data)
                                {
                                    console.log('error');
                                });
                    }
                };

                $scope.saveClient = function () {
                    if ($scope.validate()) {
                        $scope.clientTempData.status = "ACTIVE";
                        $http.post('app/php-functions/client.php?action=f1', $scope.clientTempData)
                                .success(function (data)
                                {
                                    $scope.clientTempData.index_no = data;
                                    $scope.clients.push($scope.clientTempData);
                                    Notification.success("new client success");
                                    $scope.clear();
                                })
                                .error(function (data)
                                {
                                    console.log('error');
                                });
                    }
                };

                $scope.deleteClient = function (index) {
                    $http.post('app/php-functions/client.php?action=f4', index)
                            .success(function (data)
                            {
                                var id = -1;
                                for (var i = 0; i < $scope.clients.length; i++) {
                                    if ($scope.clients[i].index_no === data) {
                                        id = i;
                                    }
                                }
                                $scope.clients.splice(id, 1);
                                Notification.error("client delete success");
                            })
                            .error(function (data)
                            {
                                console.log('error');
                            });
                };

                $scope.getApiKey = function () {
                    $http.get('app/php-functions/get_api_key.php').success(function (data) {
                        //Notification.success("new api");
                        $scope.clientTempData.api_key = data;
                    });
                };

                $scope.init = function () {
                    $http.get('app/php-functions/client.php?action=f2').success(function (data) {
                        $scope.clients = data;
                    });
                    $scope.clientTempData = {};
                    $scope.getApiKey();
                };
                $scope.init();
            });
}());
