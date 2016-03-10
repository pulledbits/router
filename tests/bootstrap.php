<?php
namespace Test;

/*
 * test specific bootstrapper
 */
$applicationBootstrap = require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'DomainTest.php';

class Helper {
    
    static function implementDocumenter() {
        return new class() implements \Teach\Interactions\Documenter {

            /**
             *
             * @param \Teach\Interactions\Documentable $documentable            
             * @return \Teach\Adapters\Renderable
             */
            public function makeDocument(\Teach\Interactions\Documentable $documentable): \Teach\Adapters\Renderable
            {
                return null;
            }

            /**
             *
             * @param string $title            
             * @param string $subtitle            
             * @return \Teach\Adapters\Renderable
             */
            public function makeFirstPage(string $title, string $subtitle): \Teach\Adapters\Renderable
            {
                return null;
            }

            /**
             *
             * @param int $expectedCellCount            
             * @param array $data            
             */
            public function makeTableRow($expectedCellCount, array $data): \Teach\Adapters\Renderable
            {
                return null;
            }

            /**
             *
             * @param array $listitems            
             */
            public function makeUnorderedList(array $listitems): \Teach\Adapters\Renderable
            {
                return null;
            }

            /**
             *
             * @param string $caption            
             * @param array $rows            
             */
            public function makeTable($caption, array $rows): \Teach\Adapters\Renderable
            {
                return new class($caption . ": " . serialize($rows)) implements \Teach\Adapters\Renderable {

                    public function __construct($content)
                    {
                        $this->content = $content;
                    }

                    public function render(): string
                    {
                        return $this->content;
                    }
                };
            }

            /**
             *
             * @param string $level            
             * @param string $text            
             * @return \Teach\Interactions\HTML\Element
             */
            public function makeHeader(string $level, string $text): \Teach\Adapters\Renderable
            {
                return null;
            }

            /**
             *
             * @return Renderable
             */
            public function makeSection(): \Teach\Adapters\Renderable
            {
                return null;
            }
        };
    }
    
}