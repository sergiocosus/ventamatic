var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');


module.exports = {
    getInventory: function(){

        var inventory_obtained;
        var client = TestRunner.clients[0];
        var productos= TestRunner.products;


        var total = 0;
        for( productos in producto){
            total += producto.quantity * producto.price;
        }

        return frisby.create('Get a inventory')
            .get('branch/1/inventory')

            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('inventory' ,{
                id: Number
            })
            .afterJSON(function(body) {
                inventory_obtained = body.inventory;
                TestRunner.next();
            });
        //.inspectJSON();
    }
};