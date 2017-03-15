<?php return function(\rikmeijer\Teach\Resources $bootstrap, \Aura\Router\Map $map) {
    $map->post('feedback.supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query, array $payload) use ($bootstrap) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();
        $session = $bootstrap->session();

        $csrf_token = $session->getCsrfToken();
        if ($csrf_token->isValid($payload['__csrf_value']) === false) {
            return $bootstrap->response(403, "This looks like a cross-site request forgery.");
        }
        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);
        $rating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);
        $rating->waarde = $payload['rating'];
        $rating->inhoud = $payload['explanation'];
        if ($rating->created_at === null) {
            $rating->created_at = date('Y-m-d H:i:s');
        }
        $rating->updated_at = date('Y-m-d H:i:s');
        return $bootstrap->response(201, 'Dankje!');
    });
};