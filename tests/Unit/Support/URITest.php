<?php

use Archetype\Support\URI;

class URITest extends Archetype\Tests\FileTestCase
{
    /** @test */
    public function it_can_enterpret_input_as_path_or_name()
    {
        $this->assertTrue(URI::make('')->isPath());
        $this->assertTrue(URI::make('car')->isPath());
        $this->assertTrue(URI::make('car.php')->isPath());
        $this->assertTrue(URI::make('Car.php')->isPath());
        $this->assertTrue(URI::make('app/Car')->isPath());
        $this->assertTrue(URI::make('/Car')->isPath());
        
        $this->assertFalse(URI::make('Car')->isPath());
        $this->assertFalse(URI::make('\\Car')->isPath());
        $this->assertFalse(URI::make('App\\Car')->isPath());
        $this->assertFalse(URI::make('\\App\\Car')->isPath());
    }
}
