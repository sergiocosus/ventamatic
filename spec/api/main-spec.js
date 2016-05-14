
var frisby = require('frisby');
var faker = require('faker');
var config = require('./config.js');
var fakeModels = require('./fake-models');
var TestRunner = require('./test-runner');

var auth = require('./services/auth');
var users = require('./services/users');
var product = require('./services/products');
var sale = require('./services/sale');
var inventory = require('./services/inventory');


faker.locale = "es_MX";


var createdProduct = null;
var createdSupplier = null;
var createdBrand = null;
var createdCategory = null;
var createdClient = null;
var createdSupplierCategory=null;
var createdRole=null;
var createdBranchRole=null;
var createdUser=null;

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

TestRunner.tests = [
    auth.auth,
    users.getLoggedUser,
    users.createUser,
    product.createProduct,
    product.updateAProduct,
    product.addProductsToInventory,
    product.DeleteProduct,
    product.getADeletedProduct,
    createABrand,
    updateABrand,
    DeleteBrand,
    getADeletedBrand,
    createACategory,
    updateACategory,
    DeleteCategory,
    getADeletedCategory,
    createAClient,
    updateAClient,
    DeleteClient,
    getADeletedClient,
    createASupplierCategory,
    updateASupplierCategory,
    deleteSupplierCategory,
    createASupplier,
    updateASupplier,
    deleteSupplier,
    getADeletedSupplier,
    createARole,
    updateARole,
    deleteRole,
    getADeletedRole,
    createABranchRole,
    updateABranchRole,
    deleteBranchRole,
    getADeleteBranchRole,


    product.createProduct,
    product.addProductsToInventory,

    //sale.createSale

];

TestRunner.next();






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
            TestRunner.next();  
        });
}

function updateABrand(){
    var brandName = faker.commerce.productMaterial();
    return frisby.create('Update a brand')
        .put('product/brand/'+createdBrand.id+'?token='+config.token,
            {
                name:brandName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('brand' ,{
            id: Number,
            name: String
        })
        .expectJSON('brand',{
            name : brandName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


function getADeletedBrand(){
    return frisby.create('Get a deleted Brand')
        .get('product/brand/'+createdBrand.id+'?token='+config.token,{
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
}


function DeleteBrand(){
    return frisby.create('delete Brand')
        .delete('product/brand/'+createdBrand.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
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
            TestRunner.next();  
        });
    //.inspectJSON();
}

function getADeletedCategory(){
    return frisby.create('Get a deleted Category')
        .get('product/category/'+createdCategory.id+'?token='+config.token,{
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
}

function DeleteCategory(){
    return frisby.create('delete category')
        .delete('product/category/'+createdCategory.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}



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
            TestRunner.next();  
        });
}



function createAClient(){
    var client = fakeModels.client();
    TestRunner.clients.push(client);

    return frisby.create('Create a Client')
        .post('client?token='+config.token, client)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('client' ,{
            id: Number,
            name: String,
            last_name:String,
            last_name_2:String,
            email:String,
            phone:String,
            cellphone:String,
            address:String,
            rfc:String
        })
        .afterJSON(function(body) {
            createdClient = body.client;
            TestRunner.next();  
        });
    //.inspectJSON();
}


function updateAClient(){
    var clientName = faker.name.firstName();
    return frisby.create('Update a Client')
        .put('client/'+createdClient.id+'?token='+config.token,
            {
                name:clientName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('client' ,{
            id: Number,
            name: String
        })
        .expectJSON('client',{
            name : clientName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}



function getADeletedClient(){
    return frisby.create('Get a deleted Client')
        .get('client/'+createdClient.id+'?token='+config.token,{
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
}


function DeleteClient(){
    return frisby.create('delete client')
        .delete('client/'+createdClient.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


function createASupplierCategory(){
    var category = fakeModels.category();

    return frisby.create('Create a Supplier Category')
        .post('supplier/category?token='+config.token, category)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplierCategory' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplierCategory = body.supplierCategory;
            TestRunner.next();  
        });
    //.inspectJSON();
}

function updateASupplierCategory(){
    var categoryName = faker.commerce.productMaterial();
    return frisby.create('Update a supplier category')
        .put('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,
            {
                name:categoryName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplierCategory' ,{
            id: Number,
            name: String
        })
        .expectJSON('supplierCategory',{
            name : categoryName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}



function deleteSupplierCategory(){
    return frisby.create('delete supplier category')
        .delete('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}

function getADeletedSupplierCategory(){
    return frisby.create('Get a deleted SupplierCategory')
        .get('supplier/category/'+createdSupplierCategory.id+'?token='+config.token,{
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
}




function createASupplier(){
    var supplier = fakeModels.supplier();

    return frisby.create('Create a Supplier')
        .post('supplier?token='+config.token, supplier)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplier' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplier = body.supplier;
            TestRunner.next();  
        });
    //.inspectJSON();
}


function updateASupplier(){
    var supplierName = faker.commerce.productMaterial();
    return frisby.create('Update a supplier ')
        .put('supplier/'+createdSupplier.id+'?token='+config.token,
            {
                name:supplierName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('supplier' ,{
            id: Number,
            name: String
        })
        .expectJSON('supplier',{
            name : supplierName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}

function getADeletedSupplier(){
    return frisby.create('Get a deleted Supplier')
        .get('supplier/'+createdSupplier.id+'?token='+config.token,{
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
}

function deleteSupplier(){
    return frisby.create('delete supplier')
        .delete('supplier/'+createdSupplier.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


//           FIn Supplier

function createARole(){
    var roleSup = fakeModels.supplier();

    return frisby.create('Create a Role')
        .post('security/system/role?token='+config.token, roleSup)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('role' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdRole = body.role;
            TestRunner.next();  
        });
    //.inspectJSON();
}

function getADeletedRole(){
    return frisby.create('Get a deleted Role')
        .get('security/system/role/'+createdRole.id+'?token='+config.token,{
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
}

function deleteRole(){
    return frisby.create('delete Role')
        .delete('security/system/role/'+createdRole.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


function updateARole(){
    var roleName = faker.commerce.productMaterial();
    return frisby.create('Update a Role ')
        .put('security/system/role/'+createdRole.id+'?token='+config.token,
            {
                name:roleName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('role' ,{
            id: Number,
            name: String
        })
        .expectJSON('role',{
            name : roleName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


//FIN ROLE

function createABranchRole(){
    var branchRoleBR = fakeModels.supplier();

    return frisby.create('Create a Branch Role')
        .post('security/branch/role?token='+config.token, branchRoleBR)
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

function getADeleteBranchRole(){
    return frisby.create('Get a deleted Branch Role')
        .get('security/branch/role/'+createdBranchRole.id+'?token='+config.token,{
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
}

function deleteBranchRole(){
    return frisby.create('delete Branch Role')
        .delete('security/branch/role/'+createdBranchRole.id+'?token='+config.token,{

        })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSON({
            success:true
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}


function updateABranchRole(){
    var branchRoleName = faker.commerce.productAdjective();
    return frisby.create('Update a Branch Role ')
        .put('security/branch/role/'+createdBranchRole.id+'?token='+config.token,
            {
                name:branchRoleName
            })
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('branchRole' ,{
            id: Number,
            name: String
        })
        .expectJSON('branchRole',{
            name : branchRoleName
        })
        .afterJSON(function(body) {
            TestRunner.next();  
        });
}





