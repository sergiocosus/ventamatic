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
            .expectJSONTypes('data.user',{
                id: Number
            })
            .afterJSON(function(body) {
                TestRunner.next();
            });
    },

    createUser: function(){
            var user=fakeModels.user();
        return frisby.create('Create a User')
            .post('user', user)
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('data.user' ,{
                id: Number,
                name: String,
                last_name:String,
                last_name_2:String,
                email:String,
                password:undefined,
                phone:String,
                cellphone:String,
                address:String,
                rfc:String
            })
            .afterJSON(function(body) {
                createdUser = body.data.user;
                TestRunner.createdUser=body.data.user;
                TestRunner.next();
            });
    },


    updateAUser: function(){
    var userName = faker.name.firstName();
    return frisby.create('Update a User')
        .put('user/'+createdUser.id,
            {
                name:userName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('data.user' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.user',{
            name : userName
        })
        .afterJSON(function(body) {
            TestRunner.next();
        });
},

    deleteUser:function(){
    return frisby.create('Delete user')
        .delete('user/'+createdUser.id,{})
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            status:'success'
        })
        .afterJSON(function(body) {
            TestRunner.next();
        });
},

    getADeletedUser:function(){
    return frisby.create('Get a deleted User')
        .get('user/'+createdUser.id,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('status', String)
        .afterJSON(function(body) {
            TestRunner.next();
        });
    }
};