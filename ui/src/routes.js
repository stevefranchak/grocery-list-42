import 'angular-route';
import './controllers/ShoppingListController';

const app = angular.module('mainRoutes', ['ngRoute', 'ShoppingList']);
 
app.config(function ($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: "templates/ShoppingListView.html",
        controller: 'ShoppingListController'
    });
});