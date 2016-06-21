var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');


module.exports = {
    getInventory: function(callback){
        return frisby.create('Get a inventory')
            .get('branch/1/inventory',{
                json:false
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('data.inventories' ,[{
                id: Number
            }])
            .afterJSON(function(body) {
                TestRunner.inventory=body.data.inventories;
                if(callback){
                    callback();
                } else {
                    TestRunner.next();
                }
            });
    }


};