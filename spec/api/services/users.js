var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

module.exports = {
    getLoggedUser: function(){
        return frisby.create('Get')
            .get('user/me',{
                json:false
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('user',{
                id: Number
            })
            .afterJSON(function(body) {
                TestRunner.next();
            });
    }
};