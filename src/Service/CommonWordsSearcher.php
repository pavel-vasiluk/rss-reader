<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/12/2020
 * Time: 9:14 PM
 */

namespace App\Service;

use DomainException;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;

/**
 * Service that searches for the most popular English words in wiki source
 */
class CommonWordsSearcher
{
    private const WIKI_SOURCE_URL = 'https://en.wikipedia.org/wiki/Most_common_words_in_English';

    /**
     * Fetch most popular English words from wiki source
     *
     * @param int $limit Limit of words to be fetched (50 by default)
     *
     * @return array
     */
    public function fetchMostPopularEnglishWords(int $limit = 50): array
    {
        $document  = $this->getDOMDocument();
        $tableNode = $this->retrieveTableNodeFromDOM($document);

        return $this->retrieveWordsFromTableNode($tableNode, $limit);
    }

    /**
     * Get DOM document for Wiki source
     *
     * @return DOMDocument
     */
    private function getDOMDocument(): DOMDocument
    {
        $html      = file_get_contents(static::WIKI_SOURCE_URL);
        $document  = new DOMDocument();
        $document->loadHTML($html);
        $document->preserveWhiteSpace = false;

        return $document;
    }

    /**
     * Retrieve Table Node from DOM document
     *
     * @param DOMDocument $document DOM document
     *
     * @return DOMNode
     */
    private function retrieveTableNodeFromDOM(DOMDocument $document): DOMNode
    {
        // Most popular words data is located in page first table element
        $table     = $document->getElementsByTagName('table');
        $tableNode = $table->item(0);

        if ($tableNode === null) {
            throw new DomainException('Requested node has not been found in table element.');
        }

        return $tableNode;
    }

    /**
     * Retrieve Word column data from Table node
     *
     * @param DOMNode $tableNode Table node
     * @param int     $limit     Limit of words to retrieve from Table node
     *
     * @return array
     */
    private function retrieveWordsFromTableNode(DOMNode $tableNode, int $limit): array
    {
        $words = [];

        /** @var DOMNodeList $rows */
        $rows = $tableNode->getElementsByTagName('tr');

        /** @var DOMElement $row */
        foreach ($rows as $row) {
            $columns = $row->getElementsByTagName('td');

            if ($columns->count() !== 0) {
                // Word is located in the first column. Its value can be retrieved from textContent public field
                $wordColumn = $columns->item(0);
                $words[] = $wordColumn->textContent;

                if (count($words) === $limit) {
                    break;
                }
            }
        }

        return $words;
    }
}