var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

module.exports = {
    auth : function (){
    return frisby.create('Auth Ventamatic APi')
        .post('',
            config.userCredentials
        )
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes({
            access_token: String
        })
        .afterJSON(function(body) {
            config.token = body.access_token;

            frisby.globalSetup({ // globalSetup is for ALL requests
                request: {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer '+ body.access_token
                    },
                    json:true,
                    baseUri: config.url,
                    inspectOnFailure: true
                }
            });
            TestRunner.next();
        });
    }   
};
