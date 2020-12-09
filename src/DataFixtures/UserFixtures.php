<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$dTu/h0z9uyrINTwSyIW/lQ$WyVp1CSE0jxicdMJrNYft8Xc+ZRHesFe0S0hFE6pMeA');
        
        $manager->persist($user);
        $manager->flush();
    }
}
