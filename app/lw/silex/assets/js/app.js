(function() {
    var app = angular.module('lw', ['ngProgress', 'ngResource']).config(function($interpolateProvider){
            $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        }
    );

    app.factory('User', function($resource) {
        return $resource('/users/:id');
    });

    app.factory('Wish', function($resource) {
        return $resource('/wishes/:id');
    });

    app.controller('StoryController', function($scope, $http, ngProgress) {
        var that = this;
        this.wish = {};

        $scope.addWish = function() {
            $scope.wishes.push(that.wish);
        };

        $scope.updateWish = function(wish) {
            $http.put('/wish/' + wish.id, wish, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).success(function() {

            }).error(function() {

            });

            // $scope.wishes.push(that.wish);
        };

        $scope.deleteWish = function(wish) {
            $http.delete('/wish/' + wish.id).success(function() {
                var index = $scope.wishes.indexOf(wish)
                $scope.wishes.splice(index, 1);
            });
        };

        ngProgress.start();
        $http({method: 'GET', url: '/wish/list'}).
            success(function(data, status, headers, config) {
                $scope.wishes = data;
                ngProgress.complete();
            }).
            error(function(data, status, headers, config) {
        });

        return this;
    });

    app.controller('SignInController', function($scope, $http, ngProgress, User, $location) {
        $scope.signIn = function(userForm) {
            ngProgress.start();
            User.save(userForm);
        };
    });

    app.controller('MakeWishController', function($scope, $http, ngProgress, Wish) {
        $scope.makeWish = function(wishForm) {
            ngProgress.start();
            Wish.save(wishForm);
            $scope.wish = {};
        };
    });
})();
