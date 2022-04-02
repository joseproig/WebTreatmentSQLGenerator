<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $project = new Project();
        $project->setName('Project AC3 - 21/22');
        $project->setDescription('Esta es la AC3 del curso 21/22, donde los alumnos trabajaran los where mas básicos');
        $project->setPathToDbFile('/Users/joseproigtorres/Desktop/TFG_BBDD_PHP/backpart_symphony/webtreatmentsqlgenerator/public/dbs/1648681005.db');
        $manager->persist($project);

        $project2 = new Project();
        $project2->setName('Project AC4 - 21/22');
        $project2->setDescription('Esta es la AC4 del curso 21/22, donde los alumnos trabajaran los where mas básicos');
        $project2->setPathToDbFile('/Users/joseproigtorres/Desktop/TFG_BBDD_PHP/backpart_symphony/webtreatmentsqlgenerator/public/dbs/1648681005.db');
        $manager->persist($project2);

        $project3 = new Project();
        $project3->setName('Project AC3 - 20/21');
        $project3->setDescription('Esta es la AC3 del curso 20/21, donde los alumnos trabajaran los where mas básicos');
        $project3->setPathToDbFile('/Users/joseproigtorres/Desktop/TFG_BBDD_PHP/backpart_symphony/webtreatmentsqlgenerator/public/dbs/1648681005.db');
        $manager->persist($project3);

        $manager->flush();

        $this->addReference('project', $project);
        $this->addReference('project2', $project2);
        $this->addReference('project3', $project3);
    }
}
