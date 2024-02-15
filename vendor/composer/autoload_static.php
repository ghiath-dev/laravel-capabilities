<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit74ec456ce3ad9cbeaaebad61894cfb89
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'Holoultek\\Capabilities\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Holoultek\\Capabilities\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit74ec456ce3ad9cbeaaebad61894cfb89::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit74ec456ce3ad9cbeaaebad61894cfb89::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit74ec456ce3ad9cbeaaebad61894cfb89::$classMap;

        }, null, ClassLoader::class);
    }
}
