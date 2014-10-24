<?php

namespace Anorgan\QueryLanguage\Parser;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-10-24 at 00:57:35.
 */
class QueryParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var QueryParser
     */
    protected $object;
    
    /**
     *
     * @var QueryLexer
     */
    protected $lexer;
    

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->lexer  = new QueryLexer;
        $this->object = new QueryParser($this->lexer);
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser
     * @dataProvider dataProviderTestParse
     */
    public function testParse($inputQuery, $expectedQuery)
    {
        $this->assertEquals($expectedQuery, (string) $this->object->parse($inputQuery));
//        $this->assertEquals($expectedQuery, (string) $this->object->parse($inputQuery), print_r($this->lexer, true));
    }
    
    public function dataProviderTestParse()
    {
        $data = [];

        $i = 0;
        
        $data['simple query' . $i++] = [
            'input'     => 'alfa=beta',
            'expected'  => 'alfa=beta'
        ];
        
        $data['simple query quoted' . $i++] = [
            'input'     => 'a="b"',
            'expected'  => 'a=b'
        ];

        $data['simple query, value has space' . $i++] = [
            'input'     => 'field>="some value"',
            'expected'  => 'field>=some value'
        ];

        $data['simple query, value has escaped quote w\o space' . $i++] = [
            'input'     => 'field>="some\"value\""',
            'expected'  => 'field>=some"value"'
        ];

        $data['simple query, value has escaped quote with space' . $i++] = [
            'input'     => 'some.field>="some \"value\""',
            'expected'  => 'some.field>=some "value"'
        ];

        $data['complex query with AND' . $i++] = [
            'input'     => 'Parent.Relation.field != "174 systems" AND some_field > 13',
            'expected'  => '(Parent.Relation.field!=174 systems) AND (some_field>13)'
        ];

// Problems with OR 
//        $data['complex query with OR' . $i++] = [
//            'input'     => 'Parent.Relation.field != "174 systems" OR is_active < "2014-01-01"',
//            'expected'  => 'Parent.Relation.field!=174 systems OR is_active<2014-01-01'
//        ];
//
//        $data['complex query with both AND and OR' . $i++] = [
//            'input'     => '(Parent.Relation.field != "174 systems") OR (is_active < "2014-01-01" AND true!=false)',
//            'expected'  => 'Parent.Relation.field!=174 systems OR (is_active<2014-01-01 AND true!=false)'
//        ];


        return $data;
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::match
     * @todo   Implement testMatch().
     */
    public function testMatch()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::Query
     * @todo   Implement testQuery().
     */
    public function testQuery()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::ConditionalTerm
     * @todo   Implement testConditionalTerm().
     */
    public function testConditionalTerm()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::ConditionalPrimary
     * @todo   Implement testConditionalPrimary().
     */
    public function testConditionalPrimary()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::ComparisonExpression
     * @todo   Implement testComparisonExpression().
     */
    public function testComparisonExpression()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::Field
     * @todo   Implement testField().
     */
    public function testField()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::ComparisonOperator
     * @todo   Implement testComparisonOperator().
     */
    public function testComparisonOperator()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Anorgan\QueryLanguage\Parser\QueryParser::Value
     * @todo   Implement testValue().
     */
    public function testValue()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}