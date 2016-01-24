<?php
// Route configuration

$app->get('/trying', 'Trying\Me:out')->setName('trying-me-out');
// $app->get('/trying', function($request, $response, $args) {
//     echo 'Hello, world!';
// })->setName('trying-me-out');
$app->get('/queues[/]', 'Queues\Queues:listQueues')->setName('queues-list');
$app->post('/queues[/]', 'Queues\Message:addMessage')->setName('message-add');

$app->group('/queues/{id}', function () use ($app) {
	$app->get('[/]', 'Queues\Queues:getInformation' )->setName('queue-information');
	$app->group( '/messages', function () use ($app) {
		$app->get( '[/]', 'Queues\Queue:listMessages');
		$app->get( '/available', 'Queues\Queue:listAvailableMessages')->setName('queue-list-available-messages');
		$app->get( '/in-flight', 'Queues\Queue:listInFlightMessages')->setName('queue-list-in-flight-messages');
		$app->get( '/finished', 'Queues\Queue:listFinishedMessages')->setName('queue-list-finished-messages');
		$app->group( '/{messageId}', function () use ($app) {
			$app->get( '[/]', 'Queues\Message:getMessage')->setName('message-get');
			$app->get( '/information', 'Queues\Message:information')->setName('message-information');
			$app->get( '/touch', 'Queues\Message:touch')->setName('message-touch');
			$app->get( '/release', 'Queues\Message:release')->setName('message-release');
		});
	});
}); // END group
