(function() {
    var app = angular.module('etpa', []);

    app.controller('StoryController', function() {
        var that = this;
        this.stories = [
            {id:1, title:'Aventura #1', description:'My description'},
            {id:2, title:'Aventura #2', description:'My description'},
            {id:3, title:'Aventura #3', description:'My description'}
        ];

        this.story = {};
        this.addStory = function() {
            that.stories.push(that.story);
        };
    });

    app.directive('stories', function() {
        return {
            restrict: 'E',
            templateUrl: 'view-stories.html',
        };
    });
})();
