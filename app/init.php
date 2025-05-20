<?php
spl_autoload_register(function ($class) {
    $prefix = 'Core\\';
    $base_dir = __DIR__ . '/core/';

    // إذا بدأ الكلاس بـ Core\
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . $relative_class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // تحميل بقية الكلاسات (models + controllers)
    $className = basename(str_replace('\\', '/', $class));
    $paths = [
        __DIR__ . '/models/' . $className . '.php',
        __DIR__ . '/controllers/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }

    die("Autoloading error: class '$class' not found.");
});