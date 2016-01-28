<?php
// Route configuration

$app->get('/trying', 'Trying\Me:out')->setName('trying-me-out');
// $app->get('/trying', function($request, $response, $args) {
//     echo 'Hello, world!';
// })->setName('trying-me-out');
$app->get('/queues[/]', 'Queues\Queues:listQueues')->setName('queues-list');
$app->post('/queues[/]', 'Queues\Queues:newQueue')->setName('queue-new');

$app->group('/queues/{id}', function () use ($app) {
	$app->get('[/]', 'Queues\Queues:getInformation' )->setName('queue-information');
	$app->put('[/]', 'Queues\Queues:updateQueue' )->setName('queue-update-information');
	$app->delete('[/]', 'Queues\Queues:deleteQueue' )->setName('queue-delete');

	$app->group( '/messages', function () use ($app) {
		$app->get( '[/]', 'Queues\Queue:listMessages');
		$app->post( '[/]', 'Queues\Queue:newMessage');
		$app->get( '/in-flight', 'Queues\Queue:listInFlightMessages')->setName('queue-list-in-flight-messages');
		$app->group( '/{messageId}', function () use ($app) {
			$app->get( '[/]', 'Queues\Queue:getMessage')->setName('message-get');
			$app->put( '[/]', 'Queues\Queue:updateMessage')->setName('message-update');
			$app->delete( '[/]', 'Queues\Queue:deleteMessage')->setName('message-delete');
			$app->get( '/information', 'Queues\Queue:getMessageInformation')->setName('message-information');
			$app->get( '/touch', 'Queues\Queue:touchMessage')->setName('message-touch');
			$app->get( '/release', 'Queues\Queue:releaseMessage')->setName('message-release');
		});
	});
}); // END group
