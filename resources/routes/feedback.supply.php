<?php return function(\Aura\Router\Map $map, \rikmeijer\Teach\Resources $resources) {
    $map->get('feedback.prepare-supply', '/feedback/{contactmomentIdentifier}/supply', function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        $session = $resources->session();

        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);

        $ipRating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);

        if ($ipRating->waarde !== null) {
            $data = [
                'rating' => $ipRating->waarde,
                'explanation' => $ipRating->inhoud
            ];
        } else {
            $data = null;
        }

        if ($data !== null) {
            $rating = $data['rating'];
            $explanation = $data['explanation'] !== null ? $data['explanation'] : '';
        } else {
            $rating = null;
            $explanation = '';
        }

        $query = $request->getQueryParams();

        if (array_key_exists('rating', $query)) {
            $rating = $query['rating'];
        }

        return $response->send(200, $resources->phpview('feedback/supply')->capture([
            'rating' => $rating,
            'explanation' => $explanation,
            'contactmomentIdentifier' => $request->getAttribute('contactmomentIdentifier'),
            'star' => function (int $i, $rating) use ($resources) : string {
                if ($rating === null) {
                    $data = $resources->readAssetUnstar();
                } elseif ($i < $rating) {
                    $data = $resources->readAssetStar();
                } else {
                    $data = $resources->readAssetUnstar();
                }
                return 'data:image/png;base64,' . base64_encode($data);
            },
            'rateForm' => function (string $contactmomentIdentifier, $rating, string $explanation) use ($session) : void {
                ?><h1>Hoeveel sterren?</h1><?php
                for ($i = 0; $i < 5; $i ++) {
                    ?><a href="<?=$this->url('/feedback/%s/supply?rating=%s', $contactmomentIdentifier, $i + 1); ?>"><img
                        src="<?= $this->star($i, $rating); ?>" width="100"/></a><?php
                }
                if ($rating !== null) {
                    $this->form("post", $session->getCsrfToken()->getValue(), "Verzenden", '<h1>Waarom?</h1>
                        <input type="hidden" name="rating" value="' . $this->escape($rating) . '" />
                        <textarea rows="5" cols="75" name="explanation">' . $this->escape($explanation) . '</textarea>
                    ');
                }
            }
        ]));
    });

    $map->post('feedback.supply', '/feedback/{contactmomentIdentifier}/supply', function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Response $response) use ($resources) : \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();
        $session = $resources->session();
        $payload = $request->getParsedBody();

        $csrf_token = $session->getCsrfToken();
        if ($csrf_token->isValid($payload['__csrf_value']) === false) {
            return $response->send(403, "This looks like a cross-site request forgery.");
        } else {
            $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);
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
    });
};