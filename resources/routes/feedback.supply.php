<?php return function(\rikmeijer\Teach\Resources $bootstrap, \Aura\Router\Map $map) {
    $map->get('feedback.prepare-supply', '/feedback/{contactmomentIdentifier}/supply', function (array $attributes, array $query) use ($bootstrap) : \Psr\Http\Message\ResponseInterface {
        $schema = $bootstrap->schema();
        $session = $bootstrap->session();

        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $attributes['contactmomentIdentifier']]);

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

        if (array_key_exists('rating', $query)) {
            $rating = $query['rating'];
        }

        return $bootstrap->response(200, $bootstrap->phpview('feedback/supply')->capture([
            'rating' => $rating,
            'explanation' => $explanation,
            'star' => function (int $i, $rating) use ($bootstrap) : string {
                if ($rating === null) {
                    $data = $bootstrap->readAssetUnstar();
                } elseif ($i < $rating) {
                    $data = $bootstrap->readAssetStar();
                } else {
                    $data = $bootstrap->readAssetUnstar();
                }
                return 'data:image/png;base64,' . base64_encode($data);
            },
            'rateForm' => function ($rating, $explanation) use ($session, $attributes) : void {
                ?><h1>Hoeveel sterren?</h1><?php
                for ($i = 0; $i < 5; $i ++) {
                    ?><a href="<?=$this->url('/feedback/%s/supply?rating=%s', $attributes['contactmomentIdentifier'], $i + 1); ?>"><img
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