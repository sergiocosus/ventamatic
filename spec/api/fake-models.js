
var faker = require('faker');

module.exports = {
    product : function () {
        return {
            description: faker.commerce.productName(),
            bar_code: faker.random.number(100000000000),
            global_minimum: faker.random.number(20),
            global_price: faker.random.number(100000) / 100,
            unit_id: 1
        }
    },
    supplier : function () {
        return {
            name: faker.name.firstName(),
            last_name:faker.name.lastName(),
            last_name_2:faker.name.lastName(),
            email:faker.internet.email(),
            phone:faker.phone.phoneNumber(),
            cellphone:faker.phone.phoneNumber(),
            address:faker.address.streetAddress(),
            rfc:faker.internet.mac()
        }
    },
    brand : function () {
        return {
            name: faker.commerce.productMaterial()
        }
    },
    category : function () {
        return {
            name: faker.commerce.productMaterial()
        }
    },
    client : function () {
        return {
            name: faker.name.firstName(),
            last_name:faker.name.lastName(),
            last_name_2:faker.name.lastName(),
            email:faker.internet.email(),
            phone:faker.phone.phoneNumber(),
            cellphone:faker.phone.phoneNumber(),
            address:faker.address.streetAddress(),
            rfc:faker.internet.mac()
        }
    },
    user : function(){
        return {
            name: faker.name.firstName(),
            last_name:faker.name.lastName(),
            last_name_2:faker.name.lastName(),
            email:faker.internet.email(),
            password:faker.internet.password(),
            phone:faker.phone.phoneNumber(),
            cellphone:faker.phone.phoneNumber(),
            address:faker.address.streetAddress(),
            rfc:faker.internet.mac()
        }
    }

};