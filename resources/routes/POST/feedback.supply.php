<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response) : \Psr\Http\Message\ResponseInterface {
    $schema = $resources->schema();
    $session = $resources->phpview()->csrfToken();
    $payload = $request->getParsedBody();

    $csrf_token = $session->getCsrfToken();
    if ($csrf_token->isValid($payload['__csrf_value']) === false) {
        return $response->send(403, "This looks like a cross-site request forgery.");
    } else {
        $contactmoment = $schema->readFirst('contactmoment', [],
            ['id' => $request->getAttribute('contactmomentIdentifier')]);
        $rating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);
        $rating->waarde = $payload['rating'];
        $rating->inhoud = $payload['explanation'];
        if ($rating->created_at === null) {
            $rating->created_at = date('Y-m-d H:i:s');
        }
        $rating->updated_at = date('Y-m-d H:i:s');
        return $response->send(201, 'Dankje!');
    }
};