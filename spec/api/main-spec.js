
var frisby = require('frisby');
var faker = require('faker');
var config = require('./config.js');
var fakeModels = require('./fake-models');
var TestRunner = require('./test-runner');

var auth = require('./services/auth');
var users = require('./services/users');
var product = require('./services/products');
var sale = require('./services/sale');
var buy = require('./services/buy');
var schedule = require('./services/schedule');
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
        baseUri: config.urlLogin,
        inspectOnFailure: true
    }
});

TestRunner.tests = [
    auth.auth,
    users.getLoggedUser,
    users.createUser,
    users.updateAUser,
    users.deleteUser,
    users.getADeletedUser,
    
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
//Pruebas para los clientes
    createASupplierCategory,
    updateASupplierCategory,
    deleteSupplierCategory,

    createASupplier,
    updateASupplier,


    createARole,
    updateARole,
    deleteRole,
    getADeletedRole,

    createABranchRole,
    updateABranchRole,
    deleteBranchRole,
    getADeleteBranchRole,

    inventory.getInventory,
    product.createProduct,
    product.addProductsToInventory,
    schedule.createASchedule,
    sale.prepareInventoryToSale,
    schedule.endASchedule,
    buy.prepareInventoryToBuy


];

TestRunner.next();






function createABrand(){
    var brand = fakeModels.brand();

    return frisby.create('Create a Brand')
        .post('product/brand?token='+config.token, brand)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('data.brand' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBrand = body.data.brand;
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
        .expectJSONTypes('data.brand' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.brand',{
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
        .expectJSONTypes('status', String)
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
            status:'success'
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
        .expectJSONTypes('data.category' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdCategory = body.data.category;
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
        .expectJSONTypes('status' , String)
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
            status:'success'
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
        .expectJSONTypes('data.category' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.category',{
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
        .expectJSONTypes('data.client' ,{
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
            createdClient = body.data.client;
            TestRunner.createdClient=body.data.client;

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
        .expectJSONTypes('data.client' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.client',{
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
        .expectJSONTypes('status', String)
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
            status:'success'
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
        .expectJSONTypes('data.supplierCategory' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplierCategory = body.data.supplierCategory;
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
        .expectJSONTypes('data.supplierCategory' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.supplierCategory',{
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
            status:'success'
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
        .expectJSONTypes('status', String)
        .afterJSON(function(body) {
            TestRunner.next();
        });
}




function createASupplier(){
    var supplier = fakeModels.supplier();
//supplier.supplier_category_id =
    return frisby.create('Create a Supplier')
        .post('supplier?token='+config.token, supplier)
        .expectStatus(200)
        .expectHeaderContains('content-type', 'application/json')
        .expectJSONTypes('data.supplier' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdSupplier = body.data.supplier;
            TestRunner.createdSupplier=body.data.supplier;
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
        .expectJSONTypes('data.supplier' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.supplier',{
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
        .expectJSONTypes('status', String)
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
            status:'success'
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
        .expectJSONTypes('data.role' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdRole = body.data.role;
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
        .expectJSONTypes('status', String)
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
            status:'success'
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
        .expectJSONTypes('data.role' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.role',{
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
        .expectJSONTypes('data.branchRole' ,{
            id: Number
        })
        .afterJSON(function(body) {
            createdBranchRole = body.data.branchRole;
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
        .expectJSONTypes('status', String)
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
            status:'success'
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
        .expectJSONTypes('data.branchRole' ,{
            id: Number,
            name: String
        })
        .expectJSON('data.branchRole',{
            name : branchRoleName
        })
        .afterJSON(function(body) {
            TestRunner.next();
        });
}





