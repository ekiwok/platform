<?php

namespace Oro\Bundle\ActivityListBundle\Tests\Unit\Entity;

use Symfony\Component\PropertyAccess\PropertyAccess;

use Oro\Bundle\ActivityListBundle\Entity\ActivityList;
use Oro\Bundle\ActivityListBundle\Entity\ActivityOwner;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

class ActivityListTest extends \PHPUnit_Framework_TestCase
{
    public function testIdGetter()
    {
        $obj = new ActivityList();

        $this->setId($obj, 1);
        $this->assertEquals(1, $obj->getId());
    }

    public function testCreatedAtGetter()
    {
        $date = new \DateTime('now');

        $obj = new ActivityList();

        $this->setCreatedAt($obj, $date);
        $this->assertEquals($date, $obj->getCreatedAt());
    }

    /**
     * @dataProvider getSetDataProvider
     * @param string $property
     * @param mixed  $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new ActivityList();

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($obj, $property, $value);
        $this->assertEquals($value, $accessor->getValue($obj, $property));
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        return [
            ['verb', 'testVerb'],
            ['subject', 'testSubject'],
            ['description', 'testDescription'],
            ['relatedActivityClass', 'testRelatedActivityClass'],
            ['relatedActivityId', 123],
            ['updatedAt', new \DateTime('now')],
            ['createdAt', new \DateTime('now')],
            ['owner', new User()],
            ['editor', new User()],
            ['organization', new Organization()]
        ];
    }

    public function testToString()
    {
        $obj = new ActivityList();
        $obj->setSubject('test subject');
        $this->assertEquals('test subject', (string)$obj);
    }

    public function testAutoGeneratedMethods()
    {
        $entity               = new ActivityList();
        $testClass            = new \stdClass();
        $reflection           = new \ReflectionClass($entity);
        $autoGeneratedMethods = [
            'supportActivityListTarget'     => '\stdClass',
            'removeActivityListTarget'      => $testClass,
            'hasActivityListTarget'         => $testClass,
            'getActivityListTargets'        => '\stdClass',
            'getActivityListTargetEntities' => null,
            'addActivityListTarget'         => $testClass,
        ];

        foreach ($autoGeneratedMethods as $methodName => $parameter) {
            $this->assertTrue($reflection->hasMethod($methodName));
            if (!is_null($parameter)) {
                $entity->$methodName($parameter);
            } else {
                $entity->$methodName();
            }
        }
    }

    /**
     * @param mixed $obj
     * @param mixed $val
     */
    protected function setId($obj, $val)
    {
        $class = new \ReflectionClass($obj);
        $prop  = $class->getProperty('id');
        $prop->setAccessible(true);

        $prop->setValue($obj, $val);
    }

    /**
     * @param mixed $obj
     * @param mixed $val
     */
    protected function setCreatedAt($obj, $val)
    {
        $class = new \ReflectionClass($obj);
        $prop  = $class->getProperty('createdAt');
        $prop->setAccessible(true);

        $prop->setValue($obj, $val);
    }

    public function testActivityOwner()
    {
        $user = new User();
        $user->setFirstName('First Name');
        $organization = new Organization();
        $organization->setName('Organization One');
        $activityOwner = new ActivityOwner();
        $activityOwner->setUser($user);
        $activityOwner->setOrganization($organization);
        $activityList = new ActivityList();
        $activityList->addActivityOwner($activityOwner);

        $this->assertCount(1, $activityList->getActivityOwners());
        $firstOwner = $activityList->getActivityOwners()->first();
        $this->assertEquals('First Name', $firstOwner->getUser()->getFirstName());
        $this->assertEquals('Organization One', $firstOwner->getOrganization()->getName());
    }

    public function testIsUpdatedFlags()
    {
        $user = $this->getMockBuilder('Oro\Bundle\UserBundle\Entity\User')
            ->disableOriginalConstructor()->getMock();
        $date = new \DateTime('2012-12-12 12:12:12');
        $activityList = new ActivityList();
        $activityList->setUpdatedBy($user);
        $activityList->setUpdatedAt($date);

        $this->assertTrue($activityList->isUpdatedBySet());
        $this->assertTrue($activityList->isUpdatedAtSet());
    }

    public function testIsNotUpdatedFlags()
    {
        $activityList = new ActivityList();
        $activityList->setUpdatedBy(null);
        $activityList->setUpdatedAt(null);

        $this->assertFalse($activityList->isUpdatedBySet());
        $this->assertFalse($activityList->isUpdatedAtSet());
    }

    public function testSetSubjectOnLongString()
    {
        $activityList = new ActivityList();
        $activityList->setSubject(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget elementum velit, ac tempor orci. '
            . 'Cras aliquet massa id dignissim bibendum. Interdum et malesuada fames ac ante ipsum primis in faucibus.'
            .' Aenean ac libero magna. Proin eu tristique est. Donec convallis pretium congue. Nullam sed.'
        );
        self::assertEquals(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eget elementum velit, ac tempor orci. '
            . 'Cras aliquet massa id dignissim bibendum. Interdum et malesuada fames ac ante ipsum primis in faucibus.'
            . ' Aenean ac libero magna. Proin eu tristiqu',
            $activityList->getSubject()
        );
    }
}
