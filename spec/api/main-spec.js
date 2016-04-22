
var frisby = require('frisby');
var faker = require('faker');
var config = require('./config.js');
var fakeModels = require('./fake-models');
faker.locale = "es_MX";

var products = [];

var createdBrand = null;
var createdCategory = null;

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
    createABrand,
    updateABrand,
    createACategory,
    getADeletedCategory
    
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

function createABrand(){
    var brand = fakeModels.brand();

    return frisby.create('Create a Brand')
        .post('product/brand?token='+config.token, brand)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('brand' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBrand = body.brand;
            next();
        });
}

function updateABrand(){
    var productName = faker.commerce.productMaterial();
    return frisby.create('Update a brand')
        .put('product/brand/'+createdBrand.id+'?token='+config.token,
            {
                name:productName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('brand' ,{
            id: Number,
            name: String
        })
        .expectJSON('brand',{
            name : productName
        })
        .afterJSON(function(body) {
            next();
        });
}


function createACategory(){
    var category = fakeModels.category();

    return frisby.create('Create a Category')
        .post('product/category?token='+config.token, category)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdCategory = body.category;
            next();
        })
        //.inspectJSON();
}

function getADeletedCategory(){
    return frisby.create('Get a deleted Category')
        .get('product/category/222?token='+config.token,{
            json:false
        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('error' ,{
            exception: String
        })
        .afterJSON(function(body) {
            next();
        });
}

/*
function getADeletedCategory(){
    return frisby.create('delete Category')
        .delete('product/category/'+createdcategory.id+sólotoken{id}2?token='+config.token,{

        })
        .expectStatus(500)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            next();
        });
}*/



function updateACategory(){
    var categoryName = faker.commerce.productMaterial();
    return frisby.create('Update a category')
        .put('product/category/'+createdCategory.id+'?token='+config.token,
            {
                name:categoryName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('category' ,{
            id: Number,
            name: String
        })
        .expectJSON('category',{
            name : categoryName
        })
        .afterJSON(function(body) {
            next();
        });
}


