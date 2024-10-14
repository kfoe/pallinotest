<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method GuzzleHttp\\\\ClientInterface\\:\\:get\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Downloader/Strategies/CsvDownloadStrategy.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method GuzzleHttp\\\\ClientInterface\\:\\:get\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Downloader/Strategies/JsonDownloadStrategy.php',
];
$ignoreErrors[] = [
	// identifier: parameter.phpDocType
	'message' => '#^PHPDoc tag @param for parameter \\$guards with type array\\<string\\> is incompatible with native type string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/app/Http/Middleware/RedirectIfAuthenticated.php',
];
$ignoreErrors[] = [
	// identifier: larastan.noUnnecessaryCollectionCall
	'message' => '#^Called \'take\' on Laravel collection, but could have been retrieved as a query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/tests/Feature/OfferControllerTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
