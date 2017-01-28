<?php
    
    class BootEmbryoTest extends \PHPUnit_Framework_TestCase {
        
        public function setUp(){
            parent::setUp();
        }
        
        public function tearDown(){
            parent::tearDown();
        }
        
        public function testBoot(){
            require __DIR__.'/../index.php';
        }

    }