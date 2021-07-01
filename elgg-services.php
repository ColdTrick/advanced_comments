<?php

use ColdTrick\AdvancedComments\DI\ThreadPreloader;

return [
	ThreadPreloader::name() => Di\get(ThreadPreloader::class),
];
