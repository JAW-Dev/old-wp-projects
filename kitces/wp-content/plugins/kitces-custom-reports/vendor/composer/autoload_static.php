<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc947162961a57d235a48178fb8997417
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'CustomReports\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'CustomReports\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/classes',
        ),
    );

    public static $classMap = array (
        'CustomReports\\DashboardPage' => __DIR__ . '/../..' . '/includes/classes/DashboardPage.php',
        'CustomReports\\QuizTimeAverage\\Data' => __DIR__ . '/../..' . '/includes/classes/QuizTimeAverage/Data.php',
        'CustomReports\\QuizTimeAverage\\Main' => __DIR__ . '/../..' . '/includes/classes/QuizTimeAverage/Main.php',
        'CustomReports\\QuizTimeAverage\\Table' => __DIR__ . '/../..' . '/includes/classes/QuizTimeAverage/Table.php',
        'CustomReports\\QuizTimeAverage\\Template' => __DIR__ . '/../..' . '/includes/classes/QuizTimeAverage/Template.php',
        'CustomReports\\QuizTimeRaw\\Data' => __DIR__ . '/../..' . '/includes/classes/QuizTimeRaw/Data.php',
        'CustomReports\\QuizTimeRaw\\Main' => __DIR__ . '/../..' . '/includes/classes/QuizTimeRaw/Main.php',
        'CustomReports\\QuizTimeRaw\\Table' => __DIR__ . '/../..' . '/includes/classes/QuizTimeRaw/Table.php',
        'CustomReports\\QuizTimeRaw\\Template' => __DIR__ . '/../..' . '/includes/classes/QuizTimeRaw/Template.php',
        'CustomReports\\ReportAbstract' => __DIR__ . '/../..' . '/includes/classes/ReportAbstract.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc947162961a57d235a48178fb8997417::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc947162961a57d235a48178fb8997417::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc947162961a57d235a48178fb8997417::$classMap;

        }, null, ClassLoader::class);
    }
}