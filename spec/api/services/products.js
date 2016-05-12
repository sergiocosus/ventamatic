var frisby = require('frisby');
var config = require('../config.js');
var fakeModels = require('../fake-models');
var faker = require('faker');
var TestRunner = require('../test-runner');

module.exports = {
    createProduct: function(){
        var product = fakeModels.product();

        return frisby.create('Get')
            .post('product', product)
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('product' ,{
                id: Number
            })
            .afterJSON(function(body) {
                TestRunner.products.push(body.product);
                TestRunner.next();
            });
    },

    updateAProduct: function(){
        var productName = faker.commerce.productName();
        return frisby.create('Update a Product')
            .put('product/'+TestRunner.lastProduct().id,
                {
                    description:productName
                })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('product' ,{
                id: Number,
                description: String
            })
            .expectJSON('product',{
                description : productName
            })
            .afterJSON(function(body) {
                TestRunner.next();
            });
    },
    addProductsToInventory: function(){
        var that = this;
        return frisby.create('Add Products to Inventory')
            .put('branch/1/inventory/product/'+TestRunner.lastProduct().id, {
                quantity : faker.random.number(20)
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSON({
                success: true
            })
            .afterJSON(function(body) {
                TestRunner.next();
            });
    },
    getADeletedProduct: function(){
        return frisby.create('Get a deleted Product')
            .get('product/'+TestRunner.lastDeletedProduct().id,{
                json:false
            })
            .expectStatus(500)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSONTypes('error' ,{
                exception: String
            })
            .afterJSON(function(body) {
                TestRunner.next();
            });
    },


    DeleteProduct: function(){
        return frisby.create('delete Product')
            .delete('product/'+TestRunner.lastProduct().id,{
        
            })
            .expectStatus(200)
            .expectHeaderContains('content-type', 'application/json')
            .expectJSON({
                success:true
            })
            .afterJSON(function(body) {
                TestRunner.deleteProduct();
                TestRunner.next();
            });
    }
};