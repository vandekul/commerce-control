<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf9c13d31b61ac948457a0bf63f857e3f
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Grav\\Plugin\\CommerceControl\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Grav\\Plugin\\CommerceControl\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Grav\\Plugin\\CommerceControlPlugin' => __DIR__ . '/../..' . '/commerce-control.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf9c13d31b61ac948457a0bf63f857e3f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf9c13d31b61ac948457a0bf63f857e3f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf9c13d31b61ac948457a0bf63f857e3f::$classMap;

        }, null, ClassLoader::class);
    }
}
