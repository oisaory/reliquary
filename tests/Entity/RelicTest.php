<?php

namespace App\Tests\Entity;

use App\Entity\Relic;
use App\Entity\Saint;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RelicTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $relic = new Relic();
        
        // Test location
        $location = 'Vatican City';
        $relic->setLocation($location);
        $this->assertEquals($location, $relic->getLocation());
        
        // Test saint relationship
        $saint = $this->createMock(Saint::class);
        $relic->setSaint($saint);
        $this->assertSame($saint, $relic->getSaint());
        
        // Test creator relationship
        $creator = $this->createMock(User::class);
        $relic->setCreator($creator);
        $this->assertSame($creator, $relic->getCreator());
    }
}