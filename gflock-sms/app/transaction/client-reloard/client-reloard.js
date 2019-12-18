(function () {
    angular.module("clientReloadModule", ['ui-notification', 'ui.bootstrap']);
    angular.module("clientReloadModule")
            .controller("clientReloadController", function ($http, $scope, $filter, Notification) {
                $scope.clients = [];
                $scope.clientReloadSmsHistory = [];
                $scope.clientSmsAmount = 0;
                $scope.clientMessageDetailHistory = [];

                $scope.clientAccount = {
                    credit_amount: 0.0,
                    debit_amount: 0.0,
                    prasantage: 0
                };

                $scope.clientTempData = {
                    "index_no": null,
                    "name": null,
                    "user_name": null,
                    "password": null,
                    "api_key": null,
                    "status": null
                };

                $scope.clientLegerData = {
                    "index_no": null,
                    "date": null,
                    "client": null,
                    "debit_amount": null,
                    "cradit_amount": null
                };

                $scope.findClientDetailsByApiKey = function (clientData) {
                    $scope.clientTempData = clientData;
                    $scope.getClientReloardHistory(clientData.index_no);
                };

                $scope.findClientDetailsByName = function (clientData) {
                    $scope.clientTempData = clientData;
                    $scope.getClientReloardHistory(clientData.index_no);
                };

                $scope.clear = function () {
                    $scope.clientReloadSmsHistory = [];
                    $scope.clientMessageDetailHistory = [];
                    $scope.clientSmsAmount = 0;
                    $scope.newClientSmsAmount = 0;
                    $scope.searchApiKey = "";
                    $scope.searchName = "";
                    $scope.clientTempData = {};
                    $scope.clientLegerData = {};
                    $scope.clientAccount = {};
                };

                $scope.newSmsAmount = function () {
                    if (!$scope.clientTempData.index_no) {
                        Notification.error("please select client");
                    } else if (!$scope.newClientSmsAmount) {
                        Notification.error("please enter sms amount");
                    } else if ($scope.newClientSmsAmount && $scope.clientTempData.index_no) {

                        $scope.clientLegerData.client = $scope.clientTempData.index_no;
                        $scope.clientLegerData.date = $filter('date')(new Date(), 'yyyy-MM-dd HH:mm:ss');
                        $scope.clientLegerData.debit_amount = $scope.newClientSmsAmount;

                        $http.post('app/php-functions/clent-leger.php?action=f3', $scope.clientLegerData)
                                .success(function (data) {
                                    Notification.success("new client sms amount add success");
                                    $scope.clear();
                                })
                                .error(function (data) {
                                    console.log('error');
                                });
                    }
                };

                $scope.getClientReloardHistory = function (index_no) {
                    //get client sms amount history
                    $http.post('app/php-functions/clent-leger.php?action=f2', index_no)
                            .success(function (data) {
                                $scope.clientReloadSmsHistory = data;
                            })
                            .error(function (data) {
                                console.log('error');
                            });

                    //get client sms amount        
                    $http.post('app/php-functions/clent-leger.php?action=f1', index_no)
                            .success(function (data) {
                                $scope.clientSmsAmount = parseInt(data);
                            })
                            .error(function (data) {
                                console.log('error');
                            });

                    //get client sms history        
                    $http.post('app/php-functions/client_messages.php?action=f1', index_no)
                            .success(function (data) {
                                $scope.clientMessageDetailHistory = data;
                            })
                            .error(function (data) {
                                console.log('error');
                            });

                    //get cradit amount and debit amount        
//                    $http.post('app/php-functions/clent-leger.php?action=f6', index_no)
//                            .success(function (data) {
//                                $scope.clientAccount.credit_amount = parseInt(data[0].credit_amount);
//                                $scope.clientAccount.debit_amount = parseInt(data[0].debit_amount);
//                                $scope.clientAccount.prasantage = Math.round($scope.clientAccount.credit_amount * 100 / $scope.clientAccount.debit_amount);
//                                console.log($scope.clientAccount.credit_amount);
//                                console.log($scope.clientAccount.debit_amount);
//                                console.log($scope.clientAccount.prasantage);
//                            })
//                            .error(function (data) {
//                                console.log('error');
//                            });


                };

                $scope.init = function () {
                    $http.get('app/php-functions/client.php?action=f2').success(function (data) {
                        $scope.clients = data;
                    });
                };
                $scope.init();
            });
}());
