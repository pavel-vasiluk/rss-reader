<?php
/**
 * @author Pavel Vasiluk <pavel.vasiluk@gmail.com>
 * Date: 3/12/2020
 * Time: 8:31 PM
 */

namespace App\Service;

use ArrayIterator;
use DomainException;
use LimitIterator;
use SimpleXMLElement;

/**
 * Service that processes RSS feed data
 */
class RssFeedManager
{
    /**
     * RSS Feed source
     * @var string
     */
    private const FEED_SOURCE_URL = 'https://www.theregister.co.uk/software/headlines.atom';

    /**
     * @var CommonWordsSearcher
     */
    private $commonWordsSearcher;

    /**
     * @var array
     */
    private $rssFeed;

    /**
     * RssFeedManager constructor.
     *
     * @param CommonWordsSearcher $commonWordsSearcher CommonWordsSearcher service
     */
    public function __construct(CommonWordsSearcher $commonWordsSearcher)
    {
        $this->commonWordsSearcher = $commonWordsSearcher;
        $this->setup();
    }

    /**
     * Fetch most popular words from the RSS feed.
     *
     * @param int $limit Limit of most popular words to search (by default 10)
     *
     * @return array
     */
    public function fetchMostPopularWordsInFeed(int $limit = 10)
    {
        $feedWords = $this->fetchWordsFromFeedEntries($this->rssFeed['entry']);
        $feedWords = $this->filterFeedWordsFromCommonWords($feedWords);

        return $this->findMostPopularWordsInFeed($feedWords, $limit);
    }

    /**
     *  Fetch RSS Feed items (every item's title & summary)
     *
     * @return array
     */
    public function fetchAllFeedItems()
    {
        return array_map(
            static function (SimpleXMLElement $entry) {
                $entry = (array) $entry;
                return [
                    'title'   => $entry['title'],
                    'summary' => $entry['summary']
                ];
            },
            $this->rssFeed['entry']
        );
    }

    /**
     * Setup RSS Feed
     */
    private function setup()
    {
        $rssFeed = simplexml_load_file(static::FEED_SOURCE_URL);
        $this->rssFeed = (array) $rssFeed;

        // Throw exception if feed data entries element is not found or is empty
        if (empty($this->rssFeed['entry'])) {
            throw new DomainException('Unable to fetch RSS entries data.');
        }
    }

    /**
     * Fetch all non-unique words from feed data entries
     *
     * @param array $entries
     *
     * @return array
     */
    private function fetchWordsFromFeedEntries(array $entries): array
    {
        $feedWords = [];

        /** @var SimpleXMLElement $entry */
        foreach ($entries as $entry) {
            $entry     = (array) $entry;
            // Strip entry title & summary from HTML tags
            $title     = strip_tags($entry['title']);
            $summary   = strip_tags($entry['summary']);
            // Merge feed words with current entry words
            $feedWords = array_merge(
                $feedWords,
                str_word_count($title, 1),
                str_word_count($summary, 1)
            );
        }

        return $feedWords;
    }

    /**
     * Find most popular words in feed.
     *
     * Creates assoc array with words as keys and their count as values
     * Then array is sorted in descending order by word's popularity
     * Then search limit is applied
     *
     * @param array $words All non-unique words found in RSS feed
     * @param int   $limit Limit of most popular words to search
     *
     * @return array
     */
    private function findMostPopularWordsInFeed(array $words, int $limit)
    {
        $wordsFrequency = array_count_values($words);
        arsort($wordsFrequency);

        return iterator_to_array(new LimitIterator(new ArrayIterator($wordsFrequency), 0, $limit));
    }

    /**
     * Filter feed words from common words
     *
     * @param array $feedWords Feed words to be filtered
     *
     * @return array
     */
    private function filterFeedWordsFromCommonWords(array $feedWords)
    {
        $commonWords = $this->commonWordsSearcher->fetchMostPopularEnglishWords();

        return array_udiff($feedWords, $commonWords, 'strcasecmp');
    }
}