<?php
namespace tests;

use yii\codeception\TestCase as YiiTestCase;

class TestCase extends YiiTestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        TestHelper::recreateSchema();
    }
}