<?php
namespace Yabacon\Paystack\Tests;

use Yabacon\Paystack\MetadataBuilder;
use Yabacon\Paystack\Exception\BadMetaNameException;

class MetadataBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testWith()
    {
        $builder = new MetadataBuilder();
        $meta = $builder->withQuoteId(10)->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"quote_id":10,"custom_fields":[{"display_name":'
        .'"Blah","variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withQuoteId(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"quote_id":{"fail":"not"},"custom_fields":[{"display_name":'
        .'"Blah","variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withokay(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"okay":{"fail":"not"},"custom_fields":[{"display_name":"Blah"'
        .',"variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withokay_able(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"okay_able":{"fail":"not"},"custom_fields":[{"display_name":"'
        .'Blah","variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withOkay(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"okay":{"fail":"not"},"custom_fields":[{"display_name":"Blah"'
        .',"variable_name":"Blah","value":"bla"}]}', $meta);
    }

    public function testNoDirectCustomFields()
    {
        $builder = new MetadataBuilder();
        $this->expectException(BadMetaNameException::class);
        $meta = $builder->withCustomFields(['cause'=>'error'])->withCustomField('Blah', 'bla')->build();
    }

    public function testFailForAnUndefinedMothod()
    {
        $builder = new MetadataBuilder();
        $this->expectException(\BadMethodCallException::class);
        $meta = $builder->withCustomField('Blah', 'bla')->builday();
    }

    public function testAfterSnakeCaseOff()
    {
        MetadataBuilder::$auto_snake_case = false;
        $builder = new MetadataBuilder();
        $meta = $builder->withQuoteId(10)->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"QuoteId":10,"custom_fields":[{"display_name":"Blah","variab'
        .'le_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withQuoteId(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"QuoteId":{"fail":"not"},"custom_fields":[{"display_name":"Blah'
        .'","variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withokay(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"okay":{"fail":"not"},"custom_fields":[{"display_name":"Blah","v'
        .'ariable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withokay_able(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"okay_able":{"fail":"not"},"custom_fields":[{"display_name":"Blah'
        .'","variable_name":"Blah","value":"bla"}]}', $meta);
        $builder = new MetadataBuilder();
        $meta = $builder->withOkay(['fail'=>'not'])->withCustomField('Blah', 'bla')->build();
        $this->assertEquals('{"Okay":{"fail":"not"},"custom_fields":[{"display_name":"Blah","var'
        .'iable_name":"Blah","value":"bla"}]}', $meta);
    }
}
