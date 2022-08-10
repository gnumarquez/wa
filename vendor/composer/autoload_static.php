<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteeb097a07188509887c5111054765f4a
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'libphonenumber\\' => 15,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
        'G' => 
        array (
            'Gnumarquez\\Wa\\' => 14,
            'Giggsey\\Locale\\' => 15,
        ),
        'B' => 
        array (
            'Brick\\PhoneNumber\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'libphonenumber\\' => 
        array (
            0 => __DIR__ . '/..' . '/giggsey/libphonenumber-for-php/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Gnumarquez\\Wa\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Giggsey\\Locale\\' => 
        array (
            0 => __DIR__ . '/..' . '/giggsey/locale/src',
        ),
        'Brick\\PhoneNumber\\' => 
        array (
            0 => __DIR__ . '/..' . '/brick/phonenumber/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteeb097a07188509887c5111054765f4a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteeb097a07188509887c5111054765f4a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticIniteeb097a07188509887c5111054765f4a::$classMap;

        }, null, ClassLoader::class);
    }
}