<?php return function (\Psr\Http\Message\RequestInterface $request, \rikmeijer\Teach\Resources $resources, \rikmeijer\Teach\Response $response): \Psr\Http\Message\ResponseInterface {
        $schema = $resources->schema();

        $contactmoment = $schema->readFirst('contactmoment', [], ['id' => $request->getAttribute('contactmomentIdentifier')]);

        $ipRating = $contactmoment->fetchFirstByFkRatingContactmoment([
            'ipv4' => $_SERVER['REMOTE_ADDR']
        ]);

        $rating = null;
        $explanation = '';
        if ($ipRating->waarde !== null) {
            $data = [
                'rating' => $ipRating->waarde,
                'explanation' => $ipRating->inhoud
            ];
            $rating = $data['rating'];
            $explanation = $data['explanation'] !== null ? $data['explanation'] : '';
        }

        $query = $request->getQueryParams();

        if (array_key_exists('rating', $query)) {
            $rating = $query['rating'];
        }

        return $response->make(200, $resources->phpview()->capture('feedback/supply', [
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
            'rateForm' => function (string $contactmomentIdentifier, $rating, string $explanation) : void {
                ?><h1>Hoeveel sterren?</h1><?php
                for ($i = 0; $i < 5; $i ++) {
                    ?><a href="<?=$this->url('/feedback/%s/supply?rating=%s', $contactmomentIdentifier, $i + 1); ?>"><img
                        src="<?= $this->star($i, $rating); ?>" width="100"/></a><?php
                }
                if ($rating !== null) {
                    $this->form("post", "Verzenden", '<h1>Waarom?</h1>
                        <input type="hidden" name="rating" value="' . $this->escape($rating) . '" />
                        <textarea rows="5" cols="75" name="explanation">' . $this->escape($explanation) . '</textarea>
                    ');
                }
            }
        ]));
};