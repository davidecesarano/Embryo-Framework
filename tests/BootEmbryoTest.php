<?php
    
    class BootEmbryoTest extends \PHPUnit\Framework\TestCase {
        
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