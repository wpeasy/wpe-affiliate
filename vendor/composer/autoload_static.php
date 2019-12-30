<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4dbc37403b2d081e071b4145f2ada290
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPEasyLibrary\\' => 14,
            'WPEAffiliate\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPEasyLibrary\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpeasy/wpeasy-library/src/WPEasyLibrary',
        ),
        'WPEAffiliate\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/WPEAffiliate',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4dbc37403b2d081e071b4145f2ada290::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4dbc37403b2d081e071b4145f2ada290::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
