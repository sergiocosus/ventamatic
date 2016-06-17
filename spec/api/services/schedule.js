/**
 * Created by eric on 8/06/16.
 */
var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

var createdSchedule;
var initialAmount=1000;

module.exports = {

    createASchedule: function () {
        console.log("create a Schedule");

        // var schedule = fakeModels.schedule();
        return frisby.create('Create a Schedule')
            .post('user/me/schedule/1', {
                initial_amount: initialAmount
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('data.schedule', {
                id: Number
            })
            .afterJSON(function (body) {
                createdSchedule = body.data.schedule;
                TestRunner.next();
            });
    },

    endASchedule:function()
{
    console.log("end a Schedule");
    var total_amount = TestRunner.total_sale + initialAmount;
    console.log("end a Schedule2"+total_amount);
    return frisby.create('End a Schedule')
        .patch('user/me/schedule', {
            final_amount: total_amount
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('data.schedule', {
            id: Number
        })
        .afterJSON(function (body) {
            createdSchedule = body.data.schedule;
            TestRunner.next();
        });

}
};


