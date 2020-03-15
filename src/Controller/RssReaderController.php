<?php

namespace App\Controller;

use App\Service\RssFeedManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RssReaderController extends AbstractController
{
    /**
     * @Route("/rss-reader", name="rss-reader")
     *
     * @param RssFeedManager $rssFeedManager RSS Feed Manager
     *
     * @return Response
     */
    public function rssReader(RssFeedManager $rssFeedManager): Response
    {
        $feedItems    = $rssFeedManager->fetchAllFeedItems();
        $rssWordsData = $rssFeedManager->fetchMostPopularWordsInFeed();

        return $this->render(
            'rss-reader.html.twig',
            [
                'feedItems'     => $feedItems,
                'feedWordsData' => $rssWordsData
            ]
        );
    }
}
