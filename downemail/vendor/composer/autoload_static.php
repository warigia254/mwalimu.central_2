<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit26c4cd65f7530b1b962295507c728974
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

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit26c4cd65f7530b1b962295507c728974::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit26c4cd65f7530b1b962295507c728974::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
