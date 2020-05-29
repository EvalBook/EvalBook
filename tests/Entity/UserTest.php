<?php

namespace App\Tests\Entity;

use App\Entity\Activity;
use App\Entity\Classe;
use App\Entity\Student;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\SetUpTearDownTrait;

class UserTest extends TestCase {

    protected $user;

    use SetUpTearDownTrait;

    private function doSetup():void
    {
        $this->user = new User();
    }


    /**
     * Test user setters and getters.
     */
    public function testBasicMethods()
    {
        // User id should be null while not inserted to the db.
        $this->assertNull($this->user->getId());

        // Testing user mail.
        $this->user->setEmail('fake@evalbook.dev');
        $this->assertEquals('fake@evalbook.dev', $this->user->getEmail());

        // Testing user last name.
        $this->user->setLastName("user");
        $this->assertEquals('user', $this->user->getLastName());

        // Testing user first name
        $this->user->setFirstName("fake");
        $this->assertEquals('fake', $this->user->getFirstName());

        // ROLE_USER should ALWAYS be included by getRoles().
        $this->user->setRoles(['ROLE_CLASS_LIST_ALL']);
        $roles = $this->user->getRoles();
        $this->assertIsArray($roles);
        $this->assertContainsEquals('ROLE_USER', $roles); // Checking 'no role role'
        $this->assertContainsEquals('ROLE_CLASS_LIST_ALL', $roles); // Checking added role.
        $this->assertNotContainsEquals('ROLE_ADMIN', $roles); // Assert does not contains admin role.

        // Testing user clases list is empty.
        $this->assertCount(0, $this->user->getClasses()->toArray());
        // Testing students list is empty.
        $this->assertCount(0, $this->user->getEleves());
        // Testing activities list is empty.
        $this->assertCount(0, $this->user->getActivites()->toArray());
    }


    /**
     * Testing user can be populated by critical items.
     */
    public function testObjectsMethods()
    {
        for($i = 0; $i < 3; $i++) {
            $classe = new Classe();
            $eleve = new Student();
            $activity = new Activity();

            // Adding new items to user.
            $this->user->addClasse($classe);
            // Adding a student to that class.
            $classe->addEleve($eleve);
            $this->user->addActivite($activity);
        }

        $this->assertCount(3, $this->user->getClasses()->toArray());
        $this->assertCount(3, $this->user->geteleves());
        $this->assertCount(3, $this->user->getActivites()->toArray());
    }

}