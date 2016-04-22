
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
    }
};