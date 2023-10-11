mocha  = require('mocha')
expect = require('chai').expect

describe("My project", function() { 
  it("should know its version", function() {
    expect('0.0.1').to.equal('0.0.1');
  });
});
