
var frisby = require('frisby');
var config = require('./config.js');

frisby.globalSetup({ // globalSetup is for ALL requests
    request: {
        headers: {
            'Accept': 'application/json'
        },
        json:true,
        baseUri: config.url,
        inspectOnFailure: true
    }
});


var tests = [
    auth,
    getLoggedUser,
    createProduct,
];

function next(){
    var test = tests.shift();
    if(test){
        test().toss();
    }
}

next();

function auth(){
    return frisby.create('Auth Ventamatic APi')
        .post('auth',
            config.userCredentials
        )
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes({
            token: String
        })
        .afterJSON(function(body) {
            config.token = body.token;
            next();
        });
}



function getLoggedUser(){
    return frisby.create('Get')
        .get('user/1?token='+config.token,{
            json:false
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes({
            id: Number
        })
        .afterJSON(function(body) {
            next();
        });
}


function createProduct(){
    return frisby.create('Get')
        .post('product?token='+config.token,
            {
                description : "Papasas Fritas",
                bar_code : "343432assd4234324",
                global_minimum : 10,
                global_price : 23.32,
                unit_id: 1
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('product' ,{
            id: Number
        })
        .afterJSON(function(body) {
            next();
        });
}


