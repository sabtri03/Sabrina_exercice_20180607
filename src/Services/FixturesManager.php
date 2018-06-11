<?php

namespace App\Services;

use Faker;
use Faker\Generator;

/**
 * Class FixturesManager
 * @package App\Services
 *
 * Utilise la librairie Faker pour fournir un service qui génèrera des données factices pouvant être utilisées pour le développement
 */
class FixturesManager{

    /**
     * @var Generator
     */
    protected $faker;

    public function __construct() {
        $this->faker = Faker\Factory::create();
    }

    /**
     * @return Generator
     */
    public function getFaker(): Generator {
        return $this->faker;
    }
}