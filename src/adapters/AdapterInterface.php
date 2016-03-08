<?php
namespace Teach\Adapters;

interface AdapterInterface
{
    /**
     * 
     * @param int $expectedCellCount
     * @param array $data
     */
    public function makeTableRow($expectedCellCount, array $data);
    
    /**
     * 
     * @param array $listitems
     */
    public function makeUnorderedList(array $listitems);
    
    /**
     * 
     * @param string $caption
     * @param array $rows
     */
    public function makeTable($caption, array $rows);
    
    /**
     * 
     * @param string $level
     * @param string $text
     * @return \Teach\Adapters\HTML\Element
     */
    public function makeHeader(string $level, string $text);
    
    /**
     * 
     * @return RenderableInterface
     */
    public function makeSection();
}