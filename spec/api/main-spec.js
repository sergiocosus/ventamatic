
var frisby = require('frisby');
var faker = require('faker');
var config = require('./config.js');
var fakeModels = require('./fake-models');
faker.locale = "es_MX";

var products = [];

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
    addProductsToInventory,
    
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
    var product = fakeModels.product();
    
    return frisby.create('Get')
        .post('product?token='+config.token, product)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('product' ,{
            id: Number
        })
        .afterJSON(function(body) {
            products.push(body.product);
            next();
        });
}

function addProductsToInventory(){
    return frisby.create('Add Products to Inventory')
        .put('branch/1/inventory/product/'+products[0].id+'?token='+config.token, {
            quantity : faker.random.number(20)
        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success: true
        })
        .afterJSON(function(body) {
            next();
        });
}


