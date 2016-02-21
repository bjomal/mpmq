<?php
// Route configuration

$app->get('/', 'Malmanger\Mpmq\Util\Misc:root')->setName('root');

$app->get('/trying', 'Trying\Me:out')->setName('trying-me-out');
$app->get('/queues[/]', 'Malmanger\Mpmq\Queues\Queues:listQueues')->setName('queues-list');
$app->post('/queues[/]', 'Malmanger\Mpmq\Queues\Queues:newQueue')->setName('queue-new');

$app->group('/queues/{id}', function () use ($app) {
    $app->get('[/]', 'Malmanger\Mpmq\Queues\Queues:getInformation' )->setName('queue-information');
    $app->put('[/]', 'Malmanger\Mpmq\Queues\Queues:updateQueue' )->setName('queue-update-information');
    $app->delete('[/]', 'Malmanger\Mpmq\Queues\Queues:deleteQueue' )->setName('queue-delete');

    $app->group( '/messages', function () use ($app) {
        $app->get( '[/]', 'Malmanger\Mpmq\Queues\Queue:listMessages');
        $app->post( '[/]', 'Malmanger\Mpmq\Queues\Queue:newMessage');
        $app->get( '/in-flight', 'Malmanger\Mpmq\Queues\Queue:listInFlightMessages')->setName('queue-list-in-flight-messages');
        $app->group( '/{messageId}', function () use ($app) {
            $app->get( '[/]', 'Malmanger\Mpmq\Queues\Queue:getMessage')->setName('message-get');
            $app->put( '[/]', 'Malmanger\Mpmq\Queues\Queue:updateMessage')->setName('message-update');
            $app->delete( '[/]', 'Malmanger\Mpmq\Queues\Queue:deleteMessage')->setName('message-delete');
            $app->get( '/information', 'Malmanger\Mpmq\Queues\Queue:getMessageInformation')->setName('message-information');
            $app->get( '/touch', 'Malmanger\Mpmq\Queues\Queue:touchMessage')->setName('message-touch');
            $app->get( '/release', 'Malmanger\Mpmq\Queues\Queue:releaseMessage')->setName('message-release');
        });
    });
}); // END group
