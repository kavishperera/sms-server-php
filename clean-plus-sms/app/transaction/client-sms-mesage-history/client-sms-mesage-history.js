(function () {
    angular.module("clientMessageHistoyModule", ['ui-notification', 'ui.bootstrap', 'angularUtils.directives.dirPagination']);
    angular.module("clientMessageHistoyModule")
            .controller("clientMessageHistoyController", function ($http, $scope) {
                $scope.clients = [];
                $scope.clienrSmsMessageHistory = [];
                $scope.totalSms = 0;

                $scope.searchFunctions = {
                    "client": null,
                    "fromDate": null,
                    "toDate": null,
                    "receiveNumber": null,
                    "charactorCount": null,
                    "smsCount": null
                };

                //get client sms amount history
                $scope.findMessageDetails = function () {
                    $http.post('app/php-functions/client_messages.php?action=f2', $scope.searchFunctions)
                            .success(function (data) {
                                $scope.clienrSmsMessageHistory = data;
                            })
                            .error(function (data) {
                                console.log('error');
                            });
                };


                $scope.clear = function () {
                    
                    $scope.searchFunctions = {
                        "client": null,
                        "fromDate": null,
                        "toDate": null,
                        "receiveNumber": null,
                        "charactorCount": null,
                        "smsCount": null
                    };

                    $scope.clienrSmsMessageHistory = [];
                };

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

                $scope.totalSmsCount = function () {
                    var totalSms = 0;
                    angular.forEach($scope.clienrSmsMessageHistory, function (val) {
                        totalSms += parseInt(val.sms_count);
                        return;
                    });
                    return totalSms;
                };

                $scope.messageView = function ($index, message) {
                    $scope.isVisible = $scope.isVisible == 0 ? true : false;
                    $scope.messagePosition = $scope.messagePosition == $index ? -1 : $index;
                    $scope.message = message;
                };

                $scope.init = function () {
                    $http.get('app/php-functions/client.php?action=f2').success(function (data) {
                        $scope.clients = data;
                    });
                };
                $scope.init();
            });
}());
