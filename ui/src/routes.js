import 'angular-route';
import './controllers/groceryListController';

const app = angular.module('mainRoutes', ['ngRoute', 'groceryList']);
 
app.config(function ($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: "templates/groceryListView.html",
        controller: 'groceryListController'
    });
});