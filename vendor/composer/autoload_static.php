<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc970f1a266f43a2118de462ce989b839
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc970f1a266f43a2118de462ce989b839::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc970f1a266f43a2118de462ce989b839::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc970f1a266f43a2118de462ce989b839::$classMap;

        }, null, ClassLoader::class);
    }
}