var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');


module.exports = {
    getInventory: function(){
        // var branchRoleBR = fakeModels.supplier();
        var client = TestRunner.clients[0];
        var productos= TestRunner.products;


        var total = 0;
        for( var product in products){
            total += product.quantity * product.price;
        }

        return frisby.create('Get a branch')
            .get('branch/1',

            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('branchRole' ,{
                id: Number
            })
            .afterJSON(function(body) {
                createdBranchRole = body.branchRole;
                TestRunner.next();
            });
        //.inspectJSON();
    }
};