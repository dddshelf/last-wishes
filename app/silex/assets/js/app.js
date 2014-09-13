(function() {
    var app = angular.module('lw', []).config(function($interpolateProvider){
            $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        }
    );

    app.controller('StoryController', function($scope, $http) {
        var that = this;
        this.wish = {};

        $scope.addWish = function() {

            $scope.wishes.push(that.wish);
        };

        $scope.deleteWish = function(wish) {
            $http.delete('/wish/' + wish.id).success(function() {
                var index = $scope.wishes.indexOf(wish)
                $scope.wishes.splice(index, 1);
            });
        };

        $http({method: 'GET', url: '/wish/list'}).
            success(function(data, status, headers, config) {
                $scope.wishes = data;
            }).
            error(function(data, status, headers, config) {
            });

        return this;
    });
})();
