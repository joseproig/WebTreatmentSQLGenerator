<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $question = new Question();
        $question->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question->setProject($this->getReference('project'));
        $question->setCreator($this->getReference('user'));
        $manager->persist($question);


        $question2 = new Question();
        $question2->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question2->setProject($this->getReference('project'));
        $question2->setCreator($this->getReference('user'));
        $manager->persist($question2);

        $question3 = new Question();
        $question3->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question3->setProject($this->getReference('project'));
        $question3->setCreator($this->getReference('user2'));
        $manager->persist($question3);

        $question4 = new Question();
        $question4->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question4->setProject($this->getReference('project'));
        $question4->setCreator($this->getReference('user2'));
        $manager->persist($question4);

        $question5 = new Question();
        $question5->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question5->setProject($this->getReference('project2'));
        $question5->setCreator($this->getReference('user2'));
        $manager->persist($question5);

        $question6 = new Question();
        $question6->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question6->setProject($this->getReference('project2'));
        $question6->setCreator($this->getReference('user'));
        $manager->persist($question6);

        $question7 = new Question();
        $question7->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question7->setProject($this->getReference('project2'));
        $question7->setCreator($this->getReference('user'));
        $manager->persist($question7);

        $question8 = new Question();
        $question8->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question8->setProject($this->getReference('project2'));
        $question8->setCreator($this->getReference('user'));
        $manager->persist($question8);


        $question9 = new Question();
        $question9->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question9->setProject($this->getReference('project3'));
        $question9->setCreator($this->getReference('user'));
        $manager->persist($question9);

        $question10 = new Question();
        $question10->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question10->setProject($this->getReference('project3'));
        $question10->setCreator($this->getReference('user'));
        $manager->persist($question10);

        $question11 = new Question();
        $question11->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question11->setProject($this->getReference('project3'));
        $question11->setCreator($this->getReference('user2'));
        $manager->persist($question11);

        $question12 = new Question();
        $question12->setTemplateQuestion('List the {C2_T2_S}s of {T2} and the {C1_T1_S} of their {T1}s whose {c2_t1==literal} and {c1_t1==literal}, an order by {C2_T1_O}');
        $question12->setProject($this->getReference('project3'));
        $question12->setCreator($this->getReference('user2'));
        $manager->persist($question12);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProjectFixtures::class
        ];
    }
}
