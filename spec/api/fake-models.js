
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
            name: faker.commerce.productMaterial(),
            last_name:faker.commerce.productName(),
            last_name_2:faker.commerce.productName(),
            email:faker.commerce.department(),
            phone:faker.commerce.price(),
            cellphone:faker.commerce.price(),
            address:faker.commerce.productName(),
            rfc:faker.commerce.productAdjective()
        }
    }
};