<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\controllers\FacturaController;

(new FacturaController())->handle();