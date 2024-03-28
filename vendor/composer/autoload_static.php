<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita778d34cb39d07c571c56e044cbc53c3 {

	public static $prefixLengthsPsr4 = array(
		'A' =>
		array(
			'Aikya\\PostViewCount\\' => 20,
		),
	);

	public static $prefixDirsPsr4 = array(
		'Aikya\\PostViewCount\\' =>
		array(
			0 => __DIR__ . '/../..' . '/src',
		),
	);

	public static $classMap = array(
		'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
	);

	public static function getInitializer( ClassLoader $loader ) {
		return \Closure::bind(
			function () use ( $loader ) {
				$loader->prefixLengthsPsr4 = ComposerStaticInita778d34cb39d07c571c56e044cbc53c3::$prefixLengthsPsr4;
				$loader->prefixDirsPsr4    = ComposerStaticInita778d34cb39d07c571c56e044cbc53c3::$prefixDirsPsr4;
				$loader->classMap          = ComposerStaticInita778d34cb39d07c571c56e044cbc53c3::$classMap;
			},
			null,
			ClassLoader::class
		);
	}
}
